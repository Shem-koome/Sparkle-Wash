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
?>
