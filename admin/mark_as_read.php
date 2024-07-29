<?php
include('../auth/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Update the status of the notification to 'read'
    $stmt = $mysqli->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>

