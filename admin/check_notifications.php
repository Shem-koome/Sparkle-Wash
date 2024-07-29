<?php
session_start(); // Start the session

include('../auth/config.php');

// Function to get the latest ID count from a table
function getLatestCount($mysqli, $table) {
    $stmt = $mysqli->prepare("SELECT COUNT(*) as count FROM $table");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to update the notifications tracker
function updateTracker($mysqli, $table, $count) {
    $stmt = $mysqli->prepare("INSERT INTO notifications_tracker (table_name, last_count) VALUES (?, ?) ON DUPLICATE KEY UPDATE last_count = ?");
    $stmt->bind_param("sii", $table, $count, $count);
    $stmt->execute();

    // Debug statement
    if ($stmt->affected_rows > 0) {
        error_log("Tracker updated for table $table with count $count");
    } else {
        error_log("Failed to update tracker for table $table with count $count");
    }
}

// Function to generate notifications
function generateNotifications($mysqli) {
    $tables = ['logins', 'orders', 'washers'];
    $notifications = [];

    foreach ($tables as $table) {
        $currentCount = getLatestCount($mysqli, $table);
        $stmt = $mysqli->prepare("SELECT last_count FROM notifications_tracker WHERE table_name=?");
        $stmt->bind_param("s", $table);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $lastCount = $row ? $row['last_count'] : 0;

        // Debug statement
        error_log("Table: $table, Current Count: $currentCount, Last Count: $lastCount");

        if ($currentCount != $lastCount) {
            $timestamp = date('Y-m-d H:i:s');
            if ($table == 'logins') {
                $message = $currentCount > $lastCount ? "New Client has created an account ($timestamp)" : "A client has deleted their account ($timestamp)";
            } elseif ($table == 'orders') {
                $message = "New order has been made ($timestamp)";
            } elseif ($table == 'washers') {
                $message = $currentCount > $lastCount ? "New washer has been added ($timestamp)" : "A washer has been removed ($timestamp)";
            }

            // Insert notification into the notifications table
            $stmt = $mysqli->prepare("INSERT INTO notifications (table_name, message, timestamp, status) VALUES (?, ?, ?, 'unread')");
            $stmt->bind_param("sss", $table, $message, $timestamp);
            $stmt->execute();

            $notifications[] = [
                'table' => $table,
                'message' => $message,
                'timestamp' => $timestamp,
                'status' => 'unread'
            ];

            // Update the notifications tracker
            updateTracker($mysqli, $table, $currentCount);
        }
    }
    return $notifications;
}


// Function to fetch all notifications and count unread ones
function fetchNotifications($mysqli) {
    $stmt = $mysqli->prepare("SELECT * FROM notifications ORDER BY timestamp DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $notifications = [];
    $unreadCount = 0; // Initialize unread count
    
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            'id' => $row['id'],
            'table' => $row['table_name'],
            'message' => $row['message'],
            'timestamp' => $row['timestamp'],
            'status' => $row['status']
        ];

        // Count unread notifications
        if ($row['status'] == 'unread') {
            $unreadCount++;
        }
    }

    // Store unread notifications count in session
    $_SESSION['unreadNotifications'] = $unreadCount;

    return ['notifications' => $notifications, 'unreadCount' => $unreadCount];
}

// Generate notifications
generateNotifications($mysqli);

// Return notifications as JSON
$notificationsData = fetchNotifications($mysqli);
$notifications = $notificationsData['notifications'];
$unreadCount = $notificationsData['unreadCount'];

// Return notifications and unread count as JSON
echo json_encode(['notifications' => $notifications, 'unreadCount' => $unreadCount]);
?>
