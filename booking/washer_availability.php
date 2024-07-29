<?php
// Include configuration file
include('../auth/config.php');

// Check if washer ID is provided and valid
if (isset($_GET['washerId'])) {
    $washerId = $_GET['washerId'];

    // Prepare statement to check orders
    $stmt = $mysqli->prepare('SELECT COUNT(*) as count FROM orders WHERE washer_id = ? AND status IN ("pending", "in progress")');
    $stmt->bind_param('i', $washerId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Determine if the washer is busy
    $busy = ($result['count'] > 0) ? true : false;

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode(['busy' => $busy]);
} else {
    // Handle invalid request
    http_response_code(400);
    echo json_encode(['error' => 'Invalid washer ID']);
}
?>
