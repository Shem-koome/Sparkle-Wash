<?php
include('../auth/config.php');

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Fetch order details
    $stmt = $mysqli->prepare("SELECT service_name, service_price FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orderDetails = [];
    while ($row = $result->fetch_assoc()) {
        $orderDetails['details'][] = $row;  // Store details in 'details' array
    }

    // Fetch total price and washer ID
    $stmt = $mysqli->prepare("SELECT total_price, washer_id FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($total_price, $washer_id);
    $stmt->fetch();
    $orderDetails['total_price'] = $total_price;
    $orderDetails['washer_id'] = $washer_id;
    $stmt->close();

    // Fetch washer name
    $washerName = getWasherName($mysqli, $washer_id);
    $orderDetails['washer_name'] = $washerName;

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode($orderDetails);

    $mysqli->close();
}

function getWasherName($mysqli, $washer_id) {
    $stmt = $mysqli->prepare("SELECT name FROM washers WHERE id = ?");
    $stmt->bind_param("i", $washer_id);
    $stmt->execute();
    $stmt->bind_result($washerName);
    $stmt->fetch();
    $stmt->close();
    return $washerName;
}
?>
