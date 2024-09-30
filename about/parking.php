<?php include '../home/dash.php'; ?>
<?php

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../auth/config.php';

// Handle car parking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['allocate'])) {
    $car_model = $_POST['car_model'];
    $car_color = $_POST['car_color'];
    $vehicle_plate = $_POST['vehicle_plate'];
    $owner_mobile = $_POST['owner_mobile'];
    $slot_id = $_POST['slot_id'];

    // Update the parking slot status
    $stmt = $mysqli->prepare("UPDATE parking_slots SET status = 'unavailable', car_model = ?, car_color = ?, vehicle_plate = ?, owner_mobile = ?, parked_at = CURRENT_TIMESTAMP WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt->bind_param("ssssi", $car_model, $car_color, $vehicle_plate, $owner_mobile, $slot_id);
    $stmt->execute();
    $stmt->close();

    // Insert into parking log
    $stmt = $mysqli->prepare("INSERT INTO parking_log (slot_id, car_model, car_color, vehicle_plate, owner_mobile, parked_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt->bind_param("issss", $slot_id, $car_model, $car_color, $vehicle_plate, $owner_mobile);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make_available'])) {
    $slot_id = $_POST['slot_id'];
    $total_amount = $_POST['total_amount'];
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

    // Update the parking slot status
    $stmt = $mysqli->prepare("UPDATE parking_slots SET status = 'available', car_model = NULL, car_color = NULL, vehicle_plate = NULL, owner_mobile = NULL, parked_at = NULL WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();
    $stmt->close();

    // Update the parking log with the leave time, amount, and payment method
    $stmt = $mysqli->prepare("UPDATE parking_log SET left_at = CURRENT_TIMESTAMP, total_amount = ?, payment_method = ? WHERE slot_id = ? AND left_at IS NULL");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }
    $stmt->bind_param("dsi", $total_amount, $payment_method, $slot_id);
    $stmt->execute();
    $stmt->close();

}

$availableSlots = [];
$result = $mysqli->query("SELECT * FROM parking_slots WHERE status = 'available' ORDER BY slot_number ASC");
while ($row = $result->fetch_assoc()) {
    $availableSlots[] = $row;
}
$result->close();

$allSlots = $mysqli->query("SELECT COUNT(*) AS total FROM parking_slots WHERE status = 'available'")->fetch_assoc();
$isParkingFull = $allSlots['total'] == 0;
?>

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
        .parking-lot {
            display: flex;
            flex-wrap: wrap;
        }
        .slot {
            width: 200px;
            height: 120px;
            margin: 10px;
            display: inline-block;
            position: relative;
            text-align: center;
            line-height: 100px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .available {
            background-color: green;
            color: white;
        }
        .unavailable {
            background-color: red;
            color: white;
        }
        .car-details {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 0;
            width: 100%;
            background-color: white;
            border: 1px solid #ccc;
            box-sizing: border-box;
            padding: 10px;
            color: black;
        }
        .unavailable:hover .car-details {
            display: block;
        }
        .car-details p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .form-container {
            margin-bottom: 20px;
        }
        .full-alert {
            color: red;
            font-weight: bold;
            font-size: 30px;
        }
        label {
            color: black;
        }
    </style>
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->
    <div id="alert-placeholder"></div>
    <div class="container-fluid about py-5">
        <?php if ($isParkingFull): ?>
            <marquee class="full-alert">Parking is full!!!</marquee>
        <?php endif; ?>

        <?php if (!$isParkingFull): ?>
            <div class="form-container">
                <form method="post" action="parking.php">
                    <label for="car_model">Vehicle Name & Model:</label>
                    <input type="text" id="car_model" name="car_model" required>
                    <br><br>
                    <label for="car_color">Vehicle Color:</label>
                    <input type="text" id="car_color" name="car_color" required>
                    <br><br>
                    <label for="vehicle_plate">Vehicle Plate:</label>
                    <input type="text" id="vehicle_plate" name="vehicle_plate" pattern="[A-Z]{3}[0-9]{3}[A-Z]" required oninput="convertToUpperCase(this)">
                    <script>
                        function convertToUpperCase(input) {
                            input.value = input.value.toUpperCase();
                        }
                    </script>
                    <br><br>
                    <label for="owner_mobile">Owner Mobile:</label>
                    <input type="text" id="owner_mobile" name="owner_mobile" required>
                    <br><br>
                    <label for="slot_id">Allocate to Parking:</label>
                    <select id="slot_id" name="slot_id" required>
                        <?php foreach ($availableSlots as $slot): ?>
                            <option value="<?php echo $slot['id']; ?>">Slot <?php echo $slot['slot_number']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br><br>
                    <button class="btn btn-primary btn-primary-outline-0 rounded-pill py-3 px-5" type="submit" name="allocate">Allocate Parking</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="parking-lot">
            <?php
            $allSlotsResult = $mysqli->query("SELECT * FROM parking_slots ORDER BY slot_number ASC");
            while ($slot = $allSlotsResult->fetch_assoc()) {
                $status = $slot['status'];
                $class = $status === 'available' ? 'available' : 'unavailable';
            ?>
                <div class="slot <?php echo $class; ?>">
                    Slot <?php echo $slot['slot_number']; ?>
                    <?php if ($status === 'unavailable'): ?>
                        <div class="car-details">
                            <p>Car Model: <?php echo $slot['car_model']; ?></p>
                            <p>Car Color: <?php echo $slot['car_color']; ?></p>
                            <p>Vehicle Plate: <?php echo $slot['vehicle_plate']; ?></p>
                            <p>Owner Mobile: <?php echo $slot['owner_mobile']; ?></p>
                            <button class="btn btn-success" onclick="showModal('<?php echo $slot['id']; ?>', '<?php echo $slot['parked_at']; ?>')">Make Available</button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Duration: <span id="modalDuration"></span></p>
                    <p>Total Amount: Ksh <span id="modalTotalAmount"></span></p>
                    <form id="checkoutForm" method="post" action="parking.php" onsubmit="return validatePaymentMethod()">
                        <input type="hidden" id="slotIdInput" name="slot_id">
                        <input type="hidden" id="totalAmountInput" name="total_amount">
                        <div class="form-group">
                            <label>Payment Method:</label>
                            <div>
                                <input type="radio" id="cash" name="payment_method" value="cash">
                                <label for="cash">Cash</label>
                            </div>
                            <div>
                                <input type="radio" id="mpesa" name="payment_method" value="mpesa">
                                <label for="mpesa">MPESA</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" name="make_available">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateDurationAndAmount(parkedAt) {
            const parkedAtDate = new Date(parkedAt);
            const currentDate = new Date();
            const duration = Math.ceil((currentDate - parkedAtDate) / (1000 * 60));
            const totalAmount = Math.round((duration / 60) * 300);
            return { duration, totalAmount };
        }

        function showModal(slotId, parkedAt) {
            const { duration, totalAmount } = calculateDurationAndAmount(parkedAt);
            document.getElementById('modalDuration').innerText = duration + ' minutes';
            document.getElementById('modalTotalAmount').innerText = totalAmount;
            document.getElementById('slotIdInput').value = slotId;
            document.getElementById('totalAmountInput').value = totalAmount;
            $('#checkoutModal').modal('show');
        }

        function validatePaymentMethod() {
            const paymentMethods = document.getElementsByName('payment_method');
            for (const method of paymentMethods) {
                if (method.checked) {
                    return true;
                }
            }
            showAlert('Please select 1 of the payment methods.', 'danger');
            return false;
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/isotope/isotope.pkgd.min.js"></script>
    <script src="../lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <<script src="../js/main.js"></script>
    <script src="../js/script.js"></script>
</body>
<?php
include '../home/footer.php';
?>
</html>
