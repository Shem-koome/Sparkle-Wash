<?php
include('../auth/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $table = $_POST['table'];
    $details = [];

    if ($table == 'logins') {
        $stmt = $mysqli->prepare("SELECT username, email FROM logins WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
    } elseif ($table == 'orders') {
        $stmt = $mysqli->prepare("SELECT user_id, washer_id, vehicle_plate FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
    } elseif ($table == 'washers') {
        $stmt = $mysqli->prepare("SELECT name FROM washers WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_assoc();
    }

    echo json_encode($details);
}
?>
