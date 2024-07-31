<?php
session_start();
include('../auth/config.php');

// Ensure username is in session
if (!isset($_SESSION['username'])) {
    echo '<p>Error: User not logged in.</p>';
    exit;
}

$name = $_SESSION['username'];

// Retrieve washer ID based on username
$stmt = $mysqli->prepare('SELECT id FROM washers WHERE name = ?');
$stmt->bind_param('s', $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $washer = $result->fetch_assoc();
    $washer_id = $washer['id'];
} else {
    echo '<p>Error: Washer not found.</p>';
    exit;
}

// Fetch the number of cars washed from the database
$stmt = $mysqli->prepare('SELECT cars_washed FROM washers WHERE id = ?');
$stmt->bind_param('i', $washer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $carsWashed = $row['cars_washed'];
} else {
    $carsWashed = 0;
}

// Query to calculate total commission earned by the washer
$stmtCommission = $mysqli->prepare('SELECT SUM(commission) AS total_commission FROM order_details WHERE washer_id = ?');
$stmtCommission->bind_param('i', $washer_id);
$stmtCommission->execute();
$resultCommission = $stmtCommission->get_result();

if ($resultCommission->num_rows == 1) {
    $commissionData = $resultCommission->fetch_assoc();
    $totalCommission = $commissionData['total_commission'];
} else {
    $totalCommission = 0;
}

$stmtCommission->close();
$mysqli->close();
?>
<?php include 'dash_washers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Washer Sparkle Wash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
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
    @media (max-width: 768px) {
        table {
            font-size: 14px;
        }
        .order-details-row {
            display: table-row !important; /* Ensure details are always shown on small screens */
        }
    }
</style>
<body>

<div id="alert-placeholder"></div>
<div class="content" id="content">
    <div class="card-container">
        <div class="card">
            <div class="card-content">
                <h2 class="card-title">Income</h2>
                <p class="card-description" id="income"><?php echo number_format($totalCommission, 2); ?></p>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <h2 class="card-title">Cars Washed</h2>
                <p class="card-description" id="income"><?php echo number_format($carsWashed, 2); ?></p>
            </div>
        </div>
    </div>
    <br>
    <?php
    include('../auth/config.php');

    // Assuming username is stored in session
    if (!isset($_SESSION['username'])) {
        echo '<p>Error: User not logged in.</p>';
        exit;
    }

    $name = $_SESSION['username'];

    // Retrieve washer ID based on username
    $stmt = $mysqli->prepare('SELECT id FROM washers WHERE name = ?');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $washer = $result->fetch_assoc();
        $washer_id = $washer['id'];
    } else {
        echo '<p>Error: Washer not found.</p>';
        exit;
    }

    // Prepare the SQL query to fetch orders related to the logged-in washer
    $stmt = $mysqli->prepare('SELECT order_id, date, vehicle_plate, status FROM orders WHERE washer_id = ? ORDER BY date DESC');
    $stmt->bind_param('i', $washer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any orders
    if ($result->num_rows > 0) {
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Date</th><th>Vehicle Plate</th><th>Status</th><th>Action</th></tr></thead>';
        echo '<tbody>';
        while ($order = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($order['date']) . '</td>';
            echo '<td>' . htmlspecialchars($order['vehicle_plate']) . '</td>';
            echo '<td>';
            echo '<select class="status-dropdown" data-order-id="' . $order['order_id'] . '">';
            echo '<option value="pending"' . ($order['status'] == 'pending' ? ' selected' : '') . '>Pending</option>';
            echo '<option value="in progress"' . ($order['status'] == 'in progress' ? ' selected' : '') . '>In Progress</option>';
            echo '<option value="completed"' . ($order['status'] == 'completed' ? ' selected' : '') . '>Completed</option>';
            echo '</select>';
            echo '</td>';
            echo "<td><button class='order-details-btn' data-order-id='" . $order['order_id'] . "'>Order Details</button></td>";
            echo '</tr>';

            echo "<tr class='order-details-row' id='order-details-" . $order['order_id'] . "' style='display: none;'>";
            echo "<td colspan='5'>";
            fetchOrderDetails($mysqli, $order['order_id']); // Function call to fetch and display order details
            echo "</td>";
            echo "</tr>";
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No orders found for this washer.</p>';
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();

    // Function to fetch and display order details
    function fetchOrderDetails($mysqli, $order_id) {
        $stmt = $mysqli->prepare("SELECT service_name, commission FROM order_details WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<table class='order-details-table' border='1'>";
        echo "<tr><th>Services Needed</th>
        <th>Commission</th>
        </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['service_name'] . "</td>";
            echo "<td>" . $row['commission'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        $stmt->close();
    }
    ?>
</div>

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

    // JavaScript to handle status change
    const statusDropdowns = document.querySelectorAll('.status-dropdown');

    statusDropdowns.forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            const orderId = this.getAttribute('data-order-id');
            const newStatus = this.value;

            // AJAX request to update the status in the database
            $.ajax({
                url: 'status_update.php',
                method: 'POST',
                data: { order_id: orderId, status: newStatus },
                success: function(response) {
                    console.log(response);
                    // Optionally, display a success message or handle the response
                    showAlert('Status updated successfully!', 'success');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Optionally, display an error message or handle the error
                }
            });
        });
    });
});
</script>

<script src="../js/admin.js"></script>
</body>
</html>
