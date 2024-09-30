<?php include 'dash.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../auth/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_slots'])) {
        $numSlots = intval($_POST['num_slots']);
        
        for ($i = 0; $i < $numSlots; $i++) {
            // Find the highest slot number currently in the database
            $result = $mysqli->query("SELECT MAX(slot_number) AS max_slot FROM parking_slots");
            $row = $result->fetch_assoc();
            $maxSlot = $row['max_slot'] ?? 0;

            // Insert a new slot with slot number incremented by 1
            $newSlotNumber = $maxSlot + 1;
            $stmt = $mysqli->prepare("INSERT INTO parking_slots (slot_number) VALUES (?)");
            $stmt->bind_param("i", $newSlotNumber);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST['delete_slot'])) {
        $slotId = $_POST['slot_id'];

        // Delete the selected slot
        $stmt = $mysqli->prepare("DELETE FROM parking_slots WHERE id = ?");
        $stmt->bind_param("i", $slotId);
        $stmt->execute();
        $stmt->close();
    }
}
// Handle car leaving
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make_available'])) {
    $slot_id = $_POST['slot_id'];

    // Update the parking slot status
    $stmt = $mysqli->prepare("UPDATE parking_slots SET status = 'available', car_model = NULL, car_color = NULL, vehicle_plate = NULL, owner_mobile = NULL WHERE id = ?");
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();
    $stmt->close();

    // Update the parking log with the leave time
    $stmt = $mysqli->prepare("UPDATE parking_log SET left_at = CURRENT_TIMESTAMP WHERE slot_id = ? AND left_at IS NULL");
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();
    $stmt->close();
}
$allSlots = $mysqli->query("SELECT COUNT(*) AS total FROM parking_slots WHERE status = 'available'")->fetch_assoc();
$isParkingFull = $allSlots['total'] == 0;

// Fetch all parking slots
$slotsResult = $mysqli->query("SELECT * FROM parking_slots ORDER BY slot_number ASC");

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <style>
.slot {
    width: 200px;
    height: 120px;
    margin: 10px;
    display: inline-block;
    position: relative;
    text-align: center;
    line-height: 100px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Ensures padding and border are included in the width/height */
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
    left: 0; /* Ensure it aligns properly */
    width: 100%; /* Full width of the slot */
    background-color: white;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Ensures padding and border are included in the width/height */
    padding: 10px; /* Adds space inside the container */
}

.unavailable:hover .car-details {
    display: block;
}

.delete-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: none;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.add-slots-form {
    margin-bottom: 20px;
}

/* Optional: Style for paragraphs inside .car-details to control spacing */
.car-details p {
    margin: 0; /* Remove default margin */
    padding: 0; /* Remove default padding */
    line-height: 1.5; /* Adjust line height for readability */
}
.full-alert {
            color: red;
            font-weight: bold;
            font-size: 30px;
        }

    </style>
</head>
<body>
<div id="alert-placeholder"></div>
<div class="content" id="content">
    <h1>Admin Parking Management</h1>
    <form method="POST" class="add-slots-form">
        <div class="form-group">
            <label for="num_slots">Number of Slots to Add:</label>
            <input type="number" id="num_slots" name="num_slots" class="form-control" min="1" required>
        </div>
        <button class="btn btn-primary mb-2" type="submit" name="add_slots">Add Slots</button>
    </form>
    <?php if ($isParkingFull): ?>
            <marquee class="full-alert">Parking is full!!!</marquee>
        <?php endif; ?>
    <div class="slots">
        <?php while ($slot = $slotsResult->fetch_assoc()): ?>
            <div class="slot <?php echo htmlspecialchars($slot['status']); ?>">
                <?php echo htmlspecialchars($slot['slot_number']); ?>
                <?php if ($slot['status'] === 'unavailable'): ?>
                    <div class="car-details">
                        <p>Model: <?php echo htmlspecialchars($slot['car_model']); ?></p>
                        <p>Color: <?php echo htmlspecialchars($slot['car_color']); ?></p>
                        <p>Reg No: <?php echo htmlspecialchars($slot['vehicle_plate']); ?></p>
                        <p>Mobile: <?php echo htmlspecialchars($slot['owner_mobile']); ?></p>
                        <form method="post" action="admin_parking.php">
                                <input type="hidden" name="slot_id" value="<?php echo $slot['id']; ?>">
                                <button class="btn btn-success" type="submit" name="make_available">Check Out</button>
                            </form>
                    </div>
                <?php endif; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="slot_id" value="<?php echo htmlspecialchars($slot['id']); ?>">
                    <button type="submit" name="delete_slot" class="delete-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
<script src="../js/admin.js"></script>
</html>