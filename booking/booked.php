<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sparkle Wash</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=PT+Serif:wght@400;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/animate/animate.min.css" rel="stylesheet">
    <link href="../lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <style>
/* Existing CSS */

h5 {
    color: blue;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: white;
    color: Black;
}

td {
    background-color: #f9f9f9;
}

tr:nth-child(even) td {
    background-color: #e9e9e9;
}

a {
    color: white;
    text-decoration: none;
}

.order-id-link {
    position: relative;
}

.order-id-link a {
    color: #3498db;
    text-decoration: none;
    padding: 5px 10px;
    display: inline-block;
}

.order-id-link a:hover {
    color: #fff;
    background-color: #3498db;
    border-radius: 4px;
    text-decoration: none;
}

/* Updated CSS for Status Alerts */
.status-pending {
    background-color: #ffc107 !important; /* yellow */
    color: black;
}

.status-in-progress {
    background-color: #fd7e14 !important; /* orange */
    color: black;
}

.status-completed {
    background-color: #28a745 !important; /* green */
    color: white;
}

</style>



<?php include '../home/dash.php'; ?>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->



    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb py-5">
        <div class="container text-center py-5">
            <h3 class="text-white display-3 mb-4">Your Bookings</h3>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="../home/Home.php">Home</a></li>
                <li class="breadcrumb-item"><a href="../service/service.php">Services</a></li>
                <li class="breadcrumb-item active text-white">Bookings</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <section>
        <div class="container-fluid services py-5">
            <div class="container py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 800px;">
                  <!--  <p class="fs-4 text-uppercase text-center text-primary">Your Cart</p>-->
                    <h1 class="display-3">Your Bookings</h1> 
                </div>

            
                <?php
include('../auth/config.php');

// Ensure username is in session
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Username not found in session"]);
    exit();
}

$user_id = $_SESSION['username'];

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch and display orders
function fetchOrders($mysqli, $user_id) {
    // Fetch orders from the database
    $stmt = $mysqli->prepare("SELECT order_id, washer_id, vehicle_plate, total_price,  payment_mode, phone_number, Time, status FROM orders WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any orders
    if ($result->num_rows === 0) {
        echo "<p style='font-size: 16px; color: #333;'>You haven't booked any Service yet <a href='../service/service.php' class='btn btn-primary btn-primary-outline-0 rounded-pill py-3 px-4 ms-4'>Book Now</a></p>";
    } else {
        // Display orders
        echo "<table border='1'>";
        echo "<tr><th>Order details</th><th>Washer Name</th><th>Vehicle Plate</th><th>Total Price</th><th>Payment Mode</th><th>Phone Number</th><th>Booking Time</th><th>Status</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $washerName = getWasherName($mysqli, $row['washer_id']);
            $vehiclePlate = $row['vehicle_plate']; // Assign vehicle plate here
            $paymentMode = $row['payment_mode']; // Assign booking time here
            $phoneNumber = $row['phone_number']; // Assign booking time here
            $BookingTime = $row['Time']; // Assign booking time here
            
            // Clean and format status
            $status = trim(strtolower($row['status']));

            echo "<tr>";
            echo "<td class='order-id-link'><a href='booked.php?order_id=" . $row['order_id'] . "'>View Order</a></td>";
            echo "<td>" . $washerName . "</td>";
            echo "<td>" . $vehiclePlate . "</td>";
            echo "<td>" . $row['total_price'] . "</td>";
            echo "<td>" . $paymentMode . "</td>";
            echo "<td>" . $phoneNumber . "</td>";
            echo "<td>" . $BookingTime . "</td>";
            echo "<td class='status-";
        
            switch ($status) {
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

            echo "</tr>";
        }

        echo "</table>";
    }

    $stmt->close();
}

// Function to fetch washer name
function getWasherName($mysqli, $washer_id) {
    $stmt = $mysqli->prepare("SELECT name FROM washers WHERE id = ?");
    $stmt->bind_param("i", $washer_id);
    $stmt->execute();
    $stmt->bind_result($washerName);
    $stmt->fetch();
    $stmt->close();
    return $washerName;
}

// Function to fetch and display order details
function fetchOrderDetails($mysqli, $order_id) {
    $stmt = $mysqli->prepare("SELECT washer_name, service_name, service_price FROM order_details WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display order details
    echo "<h5>Order Details for Order ID: $order_id</h5>";
    echo "<table border='1'>";
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

// Check if order_id is set in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    fetchOrderDetails($mysqli, $order_id);
} else {
    fetchOrders($mysqli, $user_id);
}

$mysqli->close();
?>

</section>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/wow/wow.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/counterup/counterup.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <script src="../js/script.js"></script>
    
</body>

<?php include '../home/footer.php'; ?>
</html>