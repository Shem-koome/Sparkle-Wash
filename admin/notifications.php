<style>
    /* Styles for notification items */
    .notification-item {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        position: relative; /* Ensure position context for absolute positioning */
        cursor: pointer; /* Add cursor pointer to indicate clickable */
        left: 0;
    }
    
    .notification-item.unread { 
        font-weight: bold; /* Bold font for unread notifications */
    }
    
    .unread-dot { 
        color: #3def00; /* Green dot for unread notifications */
        margin-left: 10px; /* Space between text and dot */
        position: absolute;
        top: 50%; /* Center dot vertically */
        transform: translateY(-50%);
        right: 25px; /* Right side of the notification item */
    }
    
    .delete-notification { 
        margin-left: 10px; /* Space between text and delete icon */
        cursor: pointer; /* Pointer cursor for delete icon */
        color: #888; /* Default color for delete icon */
        position: absolute;
        right: 0; /* Right side of the notification item */
    }
    
    /* Modal styles */  
    .modal {
        display: none; /* Hidden by default */
        position: absolute; /* Fixed position to overlay content */
        top: 10%; /* Position from the top */
        left: 0; /* Align left */
        z-index: 1050; /* Ensure modal appears on top of other content */
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scrolling if needed */
    }
    
    .modal-dialog {
        position: absolute; /* Relative positioning for modal content */
        max-width: 200px; /* Maximum width of the modal */
        margin: 30px auto; /* Center modal vertically and horizontally */
        top: 40%; /* Position from the top */
    }
    
    .modal-content {
        position: absolute; /* Relative positioning for modal content */
        background-color: #fff; /* White background */
        border: 1px solid rgba(0, 0, 0, 0.2); /* Border with transparency */
        border-radius: 0.3rem; /* Rounded corners */
        box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5); /* Box shadow for depth */
        outline: 0; /* No outline */
    }
    
    .modal-header,
    .modal-body {
        padding: 15px; /* Padding for content areas */
    }
    
    /* Responsive adjustments */
    @media (max-width: 560px) {
        .notification-item {
            font-size: 14px; /* Adjust font size for smaller screens */
        }
        .modal-dialog {
            left: 30%; /* Align left */
            max-width: 50%; /* Full width on smaller screens */
            top: 30%;
        }
        .modal-header,
    .modal-body {
        padding: 9px; /* Padding for content areas */
        font-size: 14px; /* Adjust font size for smaller screens */
    }
    }
</style>
<?php

// Include necessary files
include 'dash.php';
include '../auth/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sparkle Wash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
</head>
<body>
<div id="alert-placeholder"></div>
<div class="content" id="content">
    <h1>Notifications</h1>
    <!-- Notification counter -->
    <div id="notificationCounter" class="notification-counter"><?php echo $unreadCount; ?></div>
    <ul id="notificationList">
        <!-- Notifications will be loaded here -->
    </ul>
</div>

<!-- Notification Popup Modal -->
<div class="modal fade" id="notificationPopup" tabindex="-1" role="dialog" aria-labelledby="notificationPopupLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationPopupLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Notification message will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to update notification counter
        function updateNotificationCounter(count) {
            $('#notificationCounter').text(count);
            if (count > 0) {
                $('#notificationCounter').addClass('has-notifications');
            } else {
                $('#notificationCounter').removeClass('has-notifications');
            }
        }

        // Fetch and update notification count on page load
        updateNotificationCounter(<?php echo $unreadCount; ?>);

        // Function to fetch notifications
        function fetchNotifications() {
            $.ajax({
                url: 'check_notifications.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var notifications = data.notifications;
                    var unreadCount = data.unreadCount;

                    var notificationList = $('#notificationList');
                    notificationList.empty();

                    if (notifications.length > 0) {
                        notifications.forEach(function (notification) {
                            var statusClass = notification.status === 'unread' ? 'unread' : '';
                            var dot = notification.status === 'unread' ? '<i class="fas fa-circle unread-dot"></i>' : '';
                            var timestamp = new Date(notification.timestamp).toLocaleDateString('en-US', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: 'numeric',
                                second: 'numeric'
                            }); // Format timestamp to day month year
                            var notificationItem = '<li class="notification-item ' + statusClass + '" data-id="' + notification.id + '" data-table="' + notification.table + '">' +
                                notification.message +
                                ' (' + timestamp + ')' + // Append timestamp here
                                dot +
                                '<i class="fas fa-trash delete-notification" data-id="' + notification.id + '"></i></li>';
                            notificationList.append(notificationItem);
                        });
                    } else {
                        notificationList.append('<li>No new notifications.</li>');
                    }

                    // Update notification counter after fetching notifications
                    updateNotificationCounter(unreadCount);
                }
            });
        }

        // Fetch notifications on page load
        fetchNotifications();

        // Fetch notifications periodically (e.g., every 30 seconds)
        setInterval(fetchNotifications, 30000); // 30000 milliseconds = 30 seconds

        // Delete notification
        $('#notificationList').on('click', '.delete-notification', function (event) {
            event.stopPropagation(); // Prevent triggering the parent click event
            var id = $(this).data('id');

            $.ajax({
                url: 'delete_notification.php',
                method: 'POST',
                data: {id: id},
                success: function () {
                    fetchNotifications();
                }
            });
        });

        // Show popup with notification details and mark as read
        $('#notificationList').on('click', '.notification-item', function () {
            var id = $(this).data('id');
            var table = $(this).data('table');

            $.ajax({
                url: 'fetch_notification_details.php',
                method: 'POST',
                data: {id: id, table: table},
                success: function (data) {
                    var details = JSON.parse(data);
                    var modalBody = '';

                    if (table === 'logins') {
                        modalBody = 'New Client: ' + details.username + ' (' + details.email + ')';
                    } else if (table === 'orders') {
                        modalBody = 'Order Details: ' + details.user_id + ' of Vehicle Plate ' + details.vehicle_plate + ' has assigned washer with ID ' + details.washer_id;
                    } else if (table === 'washers') {
                        modalBody = 'New Washer: ' + details.name;
                    }

                    $('#notificationPopup .modal-body').text(modalBody);
                    $('#notificationPopup').modal('show'); // Show modal

                    // Mark as read
                    $.ajax({
                        url: 'mark_as_read.php',
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            fetchNotifications();
                        }
                    });
                }
            });
        });
    });
</script>
</body>
</html>
