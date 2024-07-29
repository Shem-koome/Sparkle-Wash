<?php
include('../auth/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Delete the notification from the database
    $stmt = $mysqli->prepare("DELETE FROM notifications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>
