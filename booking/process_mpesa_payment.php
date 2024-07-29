<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure phoneNumber and totalPrice are set
    if (isset($_POST['phoneNumber']) && isset($_POST['totalPrice'])) {
        $amount = $_POST['totalPrice'];
        $phone = $_POST['phoneNumber'];

        // Validate PhoneNumber format (example: 2547XXXXXXXX)
        if (!preg_match('/^2547\d{8}$/', $phoneNumber)) {
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
                    'Amount' => $totalPrice,
                    'PartyA' => $phoneNumber,
                    'PartyB' => $shortcode,
                    'PhoneNumber' => $phoneNumber,
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
