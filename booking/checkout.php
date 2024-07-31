<?php
session_start();
include('../auth/config.php');

// Ensure username is in session
if (!isset($_SESSION['username'])) {
    echo json_encode([
        "success" => false,
        "message" => "<div class='alert alert-danger' role='alert'>Username not found in session</div>"
    ]);
    exit();
}

$user_id = $_SESSION['username'];

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch commission rate by washer ID
function getCommissionRate($mysqli, $washerId) {
    $stmt = $mysqli->prepare("SELECT commission FROM washers WHERE id = ?");
    $stmt->bind_param("i", $washerId);
    $stmt->execute();
    $stmt->bind_result($commission);
    $stmt->fetch();
    $stmt->close();
    return $commission;
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get basic checkout data from POST request
    $washerId = $_POST['washerId'];
    $washerName = $_POST['washerName'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $vehiclePlate = $_POST['vehiclePlate'];
    $totalPrice = $_POST['totalPrice'];
    $paymentMode = $_POST['paymentMode'];
    $phoneNumber = $_POST['phoneNumber'] ?? ''; // Use null coalescing to handle optional phone number

    // Ensure that paymentMode is correctly processed and inserted into the orders table
    $stmtOrders = $mysqli->prepare("INSERT INTO orders (user_id, washer_id, date, location, vehicle_plate, payment_mode, phone_number, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmtOrders->bind_param("sissssss", $user_id, $washerId, $date, $location, $vehiclePlate, $paymentMode, $phoneNumber, $totalPrice);
    
    // Check if the query executed successfully
    if ($stmtOrders->execute()) {
        // Retrieve the order ID of the inserted row
        $orderId = $stmtOrders->insert_id;

        // Insert services data into the order_details table
        // Decode the JSON string of services sent from the client-side
        $services = json_decode($_POST['services'], true);

        // Prepare INSERT statement for order_details outside the loop
        $stmtServices = $mysqli->prepare("INSERT INTO order_details (user_id, order_id, washer_id, washer_name, service_name, vehicle_plate, service_price, commission, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");

        // Check if the prepare statement failed
        if ($stmtServices === false) {
            echo json_encode([
                "success" => false,
                "message" => "<div class='alert alert-danger' role='alert'>Failed to prepare statement: " . $mysqli->error . "</div>"
            ]);
            exit();
        }

        $totalCommission = 0; // Initialize total commission

        foreach ($services as $service) {
            $serviceName = $service['name'];
            $servicePrice = $service['price'];

            // Calculate commission based on washer's commission rate
            $commissionRate = getCommissionRate($mysqli, $washerId);
            $commission = $servicePrice * ($commissionRate / 100);

            $totalCommission += $commission; // Add to total commission

            // Bind parameters for the current service
            if (!$stmtServices->bind_param("siissssd", $user_id, $orderId, $washerId, $washerName, $serviceName, $vehiclePlate, $servicePrice, $commission)) {
                echo json_encode([
                    "success" => false,
                    "message" => "<div class='alert alert-danger' role='alert'>Failed to bind parameters: " . $stmtServices->error . "</div>"
                ]);
                exit();
            }

            // Execute the statement for the current service
            if (!$stmtServices->execute()) {
                // If any service insertion fails, rollback and return error
                $mysqli->query("DELETE FROM orders WHERE order_id = '$orderId'");
                echo json_encode([
                    "success" => false,
                    "message" => "<div class='alert alert-danger' role='alert'>Error inserting service: " . $stmtServices->error . "</div>"
                ]);
                exit();
            }

            // Reset the statement parameters for next iteration
            $stmtServices->reset();
        }

        // Close statement
        $stmtServices->close();

        // Update the orders table with total commission
        $stmtUpdateOrders = $mysqli->prepare("UPDATE orders SET commission = ? WHERE order_id = ?");
        $stmtUpdateOrders->bind_param("di", $totalCommission, $orderId);
        if ($stmtUpdateOrders->execute()) {
            // If all inserts and update were successful, return success message as JSON
            echo json_encode([
                "success" => true,
                "message" => "<div class='alert alert-success' role='alert'>Booking successful!</div>"
            ]);
        } else {
            // If updating orders fails, return error message as JSON
            echo json_encode([
                "success" => false,
                "message" => "<div class='alert alert-danger' role='alert'>Error updating orders: " . $stmtUpdateOrders->error . "</div>"
            ]);
        }

        // Close statement
        $stmtUpdateOrders->close();

    } else {
        // If an error occurred while inserting orders, return error message as JSON
        echo json_encode([
            "success" => false,
            "message" => "<div class='alert alert-danger' role='alert'>Error: " . $stmtOrders->error . "</div>"
        ]);
    }

    // Close statement and connection
    $stmtOrders->close();
    $mysqli->close();

} else {
    // If request method is not POST, return an error message
    echo json_encode([
        "success" => false,
        "message" => "<div class='alert alert-danger' role='alert'>Invalid request method</div>"
    ]);
}

//process mpesa payment these are the codes 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure phoneNumber and totalPrice are set
    if (isset($_POST['phoneNumber']) && isset($_POST['totalPrice'])) {
        $amount = $_POST['totalPrice'];
        $phone = $_POST['phoneNumber'];

        // Validate and round the amount to the nearest whole number
        if (!is_numeric($amount) || floatval($amount) <= 0) {
            $logMessage = 'Invalid Amount format';
            echo json_encode(['status' => 'error', 'message' => $logMessage]);
            echo "<script>console.error('" . addslashes($logMessage) . "');</script>";
            exit;
        }

        $amount = round(floatval($amount)); // Round the amount to the nearest whole number

        // Normalize and validate PhoneNumber
        $phone = preg_replace('/\D/', '', $phone); // Remove non-numeric characters
        if (strlen($phone) == 10 && substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1); // Convert local number to international format
        } elseif (strlen($phone) == 12 && substr($phone, 0, 3) == '254') {
            // It's already in international format
        } else {
            $logMessage = 'Invalid PhoneNumber format';
            echo json_encode(['status' => 'error', 'message' => $logMessage]);
            echo "<script>console.error('" . addslashes($logMessage) . "');</script>";
            exit;
        }

        // Your M-Pesa credentials and API details
        $consumerKey = 'Ee9S1AVHrHGUEd4gGyXd7SpuyAFhwdpETS4E7YlGMhHAM4gw';
        $consumerSecret = 'Tgp9O5jjSwga1J525kbBm2GAfMMwpOLpw8GE9phiM8JF3bwUElZNsBz1xrAAR7GP';
        $shortcode = '174379'; // This is your Paybill or Buy Goods Till Number
        $passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'; // Replace with your actual passkey

        // Log credentials
        $logMessage = "Using credentials: consumerKey=$consumerKey, consumerSecret=$consumerSecret, shortcode=$shortcode, passkey=$passkey";
        echo "<script>console.log('" . addslashes($logMessage) . "');</script>";

        // Access token URL
        $token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        // Initialize curl session
        $ch = curl_init($token_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode($consumerKey . ':' . $consumerSecret)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute and get the response
        $token_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP status code
        curl_close($ch);

        // Check if token request was successful (HTTP status 200)
        if ($http_code == 200) {
            // Decode token response
            $token_data = json_decode($token_response);

            // Check if access_token is set
            if (isset($token_data->access_token)) {
                $access_token = $token_data->access_token;

                // Log access token
                $logMessage = "Obtained access token: $access_token";
                echo "<script>console.log('" . addslashes($logMessage) . "');</script>";

                // Payment request URL
                $payment_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

                // Prepare request data
                $timestamp = date('YmdHis');
                $password = base64_encode($shortcode . $passkey . $timestamp);

                $stk_request = array(
                    'BusinessShortCode' => $shortcode,
                    'Password' => $password,
                    'Timestamp' => $timestamp,
                    'TransactionType' => 'CustomerPayBillOnline',
                    'Amount' => $amount,
                    'PartyA' => $phone,
                    'PartyB' => $shortcode,
                    'PhoneNumber' => $phone,
                    'CallBackURL' => "https://mydomain.com/path", // Replace with your callback URL
                    'AccountReference' => 'SparkleWash', // Replace with your transaction reference
                    'TransactionDesc' => 'Payment for services'
                );

                // Initiate STK push request
                $stk_curl = curl_init();
                curl_setopt($stk_curl, CURLOPT_URL, $payment_url);
                curl_setopt($stk_curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $access_token));
                curl_setopt($stk_curl, CURLOPT_POST, true);
                curl_setopt($stk_curl, CURLOPT_POSTFIELDS, json_encode($stk_request));
                curl_setopt($stk_curl, CURLOPT_RETURNTRANSFER, true);
                $stk_response = curl_exec($stk_curl);
                curl_close($stk_curl);

                // Handle the response
                $response = json_decode($stk_response, true);

                // Log STK response
                $logMessage = "STK push response: " . json_encode($response);
                echo "<script>console.log('" . addslashes($logMessage) . "');</script>";

                echo json_encode(['status' => 'success', 'response' => $response]);

            } else {
                $logMessage = 'Access token not found in response';
                echo json_encode(['status' => 'error', 'message' => $logMessage]);
                echo "<script>console.error('" . addslashes($logMessage) . "');</script>";
            }

        } else {
            $logMessage = 'Failed to obtain access token';
            echo json_encode(['status' => 'error', 'message' => $logMessage]);
            echo "<script>console.error('" . addslashes($logMessage) . "');</script>";
        }

    } else {
        $logMessage = 'Missing parameters: phoneNumber or totalPrice';
        echo json_encode(['status' => 'error', 'message' => $logMessage]);
        echo "<script>console.error('" . addslashes($logMessage) . "');</script>";
    }
} else {
    $logMessage = 'Invalid request method';
    echo json_encode(['status' => 'error', 'message' => $logMessage]);
    echo "<script>console.error('" . addslashes($logMessage) . "');</script>";
}
?>
