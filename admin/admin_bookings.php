<?php include 'dash.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sparkle Wash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.js"></script>
    <script src="charts.js"></script>
  
</head>
<style>
    .container {
    max-width: 1200px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2, h3 {
    margin-bottom: 10px;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ccc;
}

th, td {
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #e9e9e9;
}

/* Button Styles */
.order-details-btn {
    padding: 6px 12px;
    font-size: 14px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

.order-details-btn:hover {
    background-color: #0056b3;
}
/* Updated CSS for Status Alerts */
.status-pending {
    background-color: #ffc107; /* yellow */
}

.status-in-progress {
    background-color: #fd7e14; /* orange */
}

.status-completed {
    background-color: #28a745; /* green */
    color: white;
}


/* Responsive Design */
@media (max-width: 560px) {
    .content {
        overflow-x: auto;
    }

    table {
        font-size: 14px;
        width: 50%;
    }
    .order-details-row {
        display: table-row !important; /* Ensure details are always shown on small screens */
    }
    .header h1 {
        font-size: 34px;
    }

    .sidebar {
        width: 133px;
        top: 70px;
        height:100%;
    }
    .sidebar.collapsed {
        width: 60px;
        height:100%;
    }

    .sidebar.collapsed .fas {
        padding: 6px;
    }

    .sidebar a {
        font-size: 13px;
        padding: 12px;
    }

    .content {
        margin-left: 90px;
        padding-top: 100px;
    }
    .content.collapsed {
        margin-left: 60px;
    }
    h2{
        font-size: 15px;
    }
    h3{
        font-size: 15px;
    }
    p{
        font-size: 15px;
    }
    th, td {
    padding: 6px;
}
}
</style>
<body>



    <div id="alert-placeholder"></div>
    <div class="content" id="content">
   
           
    <?php
include('../auth/config.php');


// Fetch orders from the database
$query = "SELECT order_id, user_id, washer_id, date, location, vehicle_plate, total_price AS order_total, status AS order_status, Time AS booking_time
          FROM orders
          ORDER BY order_id DESC"; // Assuming you want to display most recent orders first

$result = $mysqli->query($query);

if ($result->num_rows === 0) {
    echo "<p>No orders found.</p>";
} else {
    echo "<h2>All Bookings Information</h2>";

    while ($row = $result->fetch_assoc()) {
        echo "<h3>Order ID: " . $row['order_id'] . "</h3>";
        echo "<table class='order-table' border='1'>";
        echo "<tr>
                <th>Client's name</th>
                <th>Booking Date</th>
                <th>Location</th>
                <th>Vehicle Plate</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Booking Time</th>
                <th>Action</th>
              </tr>";

        echo "<tr>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['location'] . "</td>";
        echo "<td>" . $row['vehicle_plate'] . "</td>";
        echo "<td>" . $row['order_total'] . "</td>";
        echo "<td class='status-";
        
        switch ($row['order_status']) {
            case 'pending':
                echo "pending'>Pending";
                break;
            case 'in progress':
                echo "in-progress'>In Progress";
                break;
            case 'completed':
                echo "completed'>Completed";
                break;
            default:
                echo "unknown'>Unknown";
                break;
        }
        
        echo "</td>";
        echo "<td>" . $row['booking_time'] . "</td>";
        echo "<td><button class='order-details-btn' data-order-id='" . $row['order_id'] . "'>Order Details</button></td>";
        echo "</tr>";

        echo "<tr class='order-details-row' id='order-details-" . $row['order_id'] . "' style='display: none;'>";
        echo "<td colspan='9'>";
        fetchOrderDetails($mysqli, $row['order_id']); // Function call to fetch and display order details
        echo "</td>";
        echo "</tr>";

        echo "</table>";
        echo "<br>";
    }
}

$mysqli->close();

// Function to fetch and display order details
function fetchOrderDetails($mysqli, $order_id) {
    $stmt = $mysqli->prepare("SELECT washer_name, service_name, service_price FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='order-details-table' border='1'>";
    echo "<tr><th>Washer Name</th><th>Service Name</th><th>Service Price</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['washer_name'] . "</td>";
        echo "<td>" . $row['service_name'] . "</td>";
        echo "<td>" . $row['service_price'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    $stmt->close();
}
?>

<script>
    // JavaScript to toggle visibility of order details on button click
    document.addEventListener("DOMContentLoaded", function() {
        const orderDetailsBtns = document.querySelectorAll('.order-details-btn');

        orderDetailsBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const orderDetailsRow = document.getElementById('order-details-' + orderId);

                if (orderDetailsRow.style.display === 'none') {
                    orderDetailsRow.style.display = 'table-row';
                } else {
                    orderDetailsRow.style.display = 'none';
                }
            });
        });
    });
</script>


    </div>
    

</body>
<script src="../js/admin.js"></script>
</html>
