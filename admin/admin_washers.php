<?php
// Include configuration file
include('../auth/config.php');

// Use the same database connection credentials
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$washerAdded = false; // Flag to check if washer was added successfully
$washerUpdated = false; // Flag to check if washer was updated successfully

// Handle form submission to add or update a washer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_washer'])) {
        // Adding a new washer
        $name = $_POST['name'];
        $image = '../img/' . basename($_POST['image']); // Ensure the correct path format
        $commission = $_POST['commission'];

        $stmt = $pdo->prepare('INSERT INTO washers (name, image, commission) VALUES (:name, :image, :commission)');
        $stmt->execute(['name' => $name, 'image' => $image, 'commission' => $commission]);
        $washerAdded = true;

        // Redirect to avoid form resubmission
        header('Location: admin_washers.php?washer_added=1&name=' . urlencode($name));
        exit();
    } elseif (isset($_POST['update_washer'])) {
        // Updating an existing washer's commission
        $id = $_POST['id'];
        $commission = $_POST['commission'];

        $stmt = $pdo->prepare('UPDATE washers SET commission = :commission WHERE id = :id');
        $stmt->execute(['commission' => $commission, 'id' => $id]);
        $washerUpdated = true;

        // Redirect to avoid form resubmission
        header('Location: admin_washers.php?washer_updated=1&id=' . $id);
        exit();
    } elseif (isset($_POST['remove_washer'])) {
        // Removing a washer
        $id = $_POST['id'];
        
        // Fetch the washer name for the alert
        $stmt = $pdo->prepare('SELECT name FROM washers WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $washer = $stmt->fetch();
        $washerName = $washer['name'];

        $stmt = $pdo->prepare('DELETE FROM washers WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Redirect to avoid form resubmission
        header("Location: admin_washers.php?washer_removed=1&name=" . urlencode($washerName));
        exit();
    }
}

// Fetch washers from the database
$stmt = $pdo->query('SELECT id, name, image, commission, cars_washed, total_income FROM washers');
$washers = $stmt->fetchAll();

// Calculate total income
$totalIncome = array_sum(array_column($washers, 'total_income'));
?>

<?php include 'dash.php' ?>
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
</head>
<body>
<div id="alert-placeholder"></div>
<div class="content" id="content">

    <div class="container mt-5">
        <h2>Manage Washers</h2>
        
        <!-- Washer Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Commission (%)</th>
                        <th>Cars Washed </th>
                        <th>Total commision </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($washers as $washer): ?>
                        <tr>
                            <td><?= htmlspecialchars($washer['id']) ?></td>
                            <td><?= htmlspecialchars($washer['name']) ?></td>
                            <td><?= htmlspecialchars($washer['commission']) ?>%</td>
                            <td><?= htmlspecialchars($washer['cars_washed']) ?></td>
                            <td><?= htmlspecialchars($washer['total_income']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-weight-bold">Total commision given</td>
                        <td><?= number_format($totalIncome, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Button to show the add washer form -->
        <button class="btn btn-primary mb-4" id="show-add-form-button">Add Washer</button>

        <!-- Add Washer Form (hidden by default) -->
        <form method="POST" class="mb-4" id="add-washer-form" style="display: none;">
            <div class="form-group">
                <label for="name">Washer Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="image" name="image" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="choose-picture">Choose Picture</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="commission">Commission (%)</label>
                <input type="text" class="form-control" id="commission" name="commission" required>
            </div>
            <button type="submit" name="add_washer" class="btn btn-primary">Add Washer</button>
        </form>
        
        <!-- Washer List -->
        <div class="row" id="washer-list">
            <?php foreach ($washers as $washer): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="<?= htmlspecialchars($washer['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($washer['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($washer['name']) ?></h5>
                            <p class="card-description">Commission: <?= $washer['commission'] ?>%</p>
                            <!-- Update Washer Commission Form (hidden by default) -->
                            <form method="POST" class="mt-3" id="update-washer-form-<?= $washer['id'] ?>" style="display: none;">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?= $washer['id'] ?>">
                                    <label for="commission-<?= $washer['id'] ?>">Commission (%)</label>
                                    <input type="text" class="form-control" id="commission-<?= $washer['id'] ?>" name="commission" value="<?= $washer['commission'] ?>" required>
                                </div>
                                <button type="submit" name="update_washer" class="btn btn-primary">Update Commission</button>
                            </form>
                            <button class="btn btn-link card-description"  id="show-update-form-button-<?= $washer['id'] ?>">Edit Commission</button>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $washer['id'] ?>">
                                <button type="submit" name="remove_washer" class="btn btn-danger mt-2">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                    // Show the update washer commission form when the "Edit Commission" button is clicked
                    document.getElementById('show-update-form-button-<?= $washer['id'] ?>').addEventListener('click', function() {
                        document.getElementById('update-washer-form-<?= $washer['id'] ?>').style.display = 'block';
                        this.style.display = 'none';
                    });
                </script>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Function to handle file selection for image URL
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('choose-picture').addEventListener('click', function() {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.style.display = 'none';

                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('image').value = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                document.body.appendChild(input);
                input.click();
                document.body.removeChild(input);
            });
        });

        // Show the add washer form when the button is clicked
        document.getElementById('show-add-form-button').addEventListener('click', function() {
            document.getElementById('add-washer-form').style.display = 'block';
            this.style.display = 'none';
        });

        // Display alerts
        const urlParams = new URLSearchParams(window.location.search);
        const washerAdded = urlParams.get('washer_added');
        const washerUpdated = urlParams.get('washer_updated');
        const washerRemoved = urlParams.get('washer_removed');
        const washerName = urlParams.get('name');

        if (washerAdded === '1') {
            showAlert('Washer "' + washerName + '" added successfully!', 'success');
        } else if (washerUpdated === '1') {
            showAlert('Washer with ID ' + urlParams.get('id') + ' updated successfully!', 'success');
        } else if (washerRemoved === '1') {
            showAlert('Washer "' + washerName + '" removed successfully!', 'danger');
        }

        function showAlert(message, type) {
            const alertPlaceholder = document.getElementById('alert-placeholder');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.role = 'alert';
            alert.innerHTML = `
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;
            alertPlaceholder.appendChild(alert);
            setTimeout(() => {
                $(alert).alert('close');
            }, 5000);
        }
    </script>
</div>
<script src="../js/admin.js"></script>
</body>
</html>
