<?php
include('../auth/config.php');

// Check if order_id and status are set in the POST request
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Prepare the SQL statement to update the status
    $stmt = $mysqli->prepare('UPDATE orders SET status = ? WHERE order_id = ?');
    $stmt->bind_param('si', $status, $order_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo 'Status updated successfully.';
    } else {
        echo 'Error updating status: ' . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
}
?>