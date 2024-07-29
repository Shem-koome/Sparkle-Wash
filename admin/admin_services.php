<?php
// Include configuration file
include('../auth/config.php');

// Use the same database connection credentials
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$serviceAdded = false; // Flag to check if service was added successfully
$serviceUpdated = false; // Flag to check if service was updated successfully

// Handle form submission to add or update a service
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        // Adding a new service
        $serviceName = $_POST['service_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $imagePath = '../img/' . basename($_POST['image']); // Corrected to get just the file name

        $stmt = $pdo->prepare('INSERT INTO services (service_name, description, price, image_path) VALUES (:service_name, :description, :price, :image_path)');
        $stmt->execute(['service_name' => $serviceName, 'description' => $description, 'price' => $price, 'image_path' => $imagePath]);
        $serviceAdded = true;

        // Redirect to avoid form resubmission
        header('Location: admin_services.php?service_added=1&name=' . urlencode($serviceName));
        exit();
    } elseif (isset($_POST['update_service'])) {
        // Updating an existing service's price
        $id = $_POST['id'];
        $price = $_POST['price'];

        $stmt = $pdo->prepare('UPDATE services SET price = :price WHERE id = :id');
        $stmt->execute(['price' => $price, 'id' => $id]);
        $serviceUpdated = true;

        // Redirect to avoid form resubmission
        header('Location: admin_services.php?service_updated=1&id=' . $id);
        exit();
    } elseif (isset($_POST['remove_service'])) {
        // Removing a service
        $id = $_POST['id'];

        // Fetch the service name for the alert
        $stmt = $pdo->prepare('SELECT service_name FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $service = $stmt->fetch();
        $serviceName = $service['service_name'];

        $stmt = $pdo->prepare('DELETE FROM services WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Redirect to avoid form resubmission
        header("Location: admin_services.php?service_removed=1&name=" . urlencode($serviceName));
        exit();
    }
}

// Fetch services from the database
$stmt = $pdo->query('SELECT * FROM services');
$services = $stmt->fetchAll();
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
    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    
    <style>
        .notification-counter {
            display: none; /* Hide the counter by default */
            position: absolute; /* Position it relative to the parent */
            top: 50%; /* Center vertically */
            right: 0; /* Align to the right */
            transform: translateY(-50%);
            background-color: #3def00; /* Background color for the counter */
            color: white; /* Text color for the counter */
            padding: 3px 8px; /* Padding around the counter */
            border-radius: 50%; /* Rounded shape for the counter */
            font-size: 12px; /* Font size for the counter */
            z-index: 1000; /* Ensure it appears above other elements */
        }

        .has-notifications:hover .notification-counter {
            background-color: #2ecc71; /* Change background color on hover */
        }

        .services-item {
            transition: background-color 0.3s; /* Smooth transition for background color */
        }

        .services-item:hover {
            background-color: blue; /* Light gray background on hover */
        }

        .services-img {
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .services-img img {
            transition: transform 0.3s ease-in-out;
        }

        .services-img:hover img {
            transform: scale(1.1); /* Zoom in effect on hover */
        }
    </style>
</head>

<body>
<div id="alert-placeholder"></div>
<div class="content" id="content">

    <div class="container mt-5">
        <h2>Manage Services</h2>

        <!-- Button to show the add service form -->
        <button class="btn btn-primary mb-4" id="show-add-form-button">Add Service</button>

        <!-- Add Service Form (hidden by default) -->
        <form method="POST" class="mb-4" id="add-service-form" style="display: none;">
            <div class="form-group">
                <label for="service_name">Service Name</label>
                <input type="text" class="form-control" id="service_name" name="service_name" required>
            </div>
            <div class="form-group">
                <label for="description">Service Description</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="text" class="form-control" id="price" name="price" required>
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
            <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
        </form>

        <!-- Service List -->
        <div class="row g-4" id="service-list">
            <?php foreach ($services as $service): ?>
                <div class="col-lg-6">
                    <div class="services-item bg-light border-4 border-<?= ($service['id'] % 2 == 0 ? 'start' : 'end') ?> border-primary rounded p-4">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <div class="services-img d-flex align-items-center justify-content-center rounded">
                                    <img src="<?= htmlspecialchars($service['image_path']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($service['service_name']) ?>">
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="services-content text-<?= ($service['id'] % 2 == 0 ? 'start' : 'end') ?>">
                                    <h3><?= htmlspecialchars($service['service_name']) ?></h3>
                                    <p><?= htmlspecialchars($service['description']) ?></p>
                                    <p class="price">$<?= htmlspecialchars($service['price']) ?></p>
                                    <button onclick="editService(<?= $service['id'] ?>)" class="btn btn-link">Edit Price</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $service['id'] ?>">
                                        <button type="submit" name="remove_service" id="remove_service" class="btn btn-danger">Remove</button>
                                    </form>
                                </div>
                                <!-- Update Service Price Form (hidden by default) -->
                                <form method="POST" class="mt-3" id="update-service-form-<?= $service['id'] ?>" style="display: none;">
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="<?= $service['id'] ?>">
                                        <label for="price-<?= $service['id'] ?>">Price ($)</label>
                                        <input type="text" class="form-control" id="price-<?= $service['id'] ?>" name="price" value="<?= $service['price'] ?>" required>
                                    </div>
                                    <button type="submit" name="update_service" id="update_service" class="btn btn-primary">Update Price</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle file selection for image URL
        document.getElementById('choose-picture').addEventListener('click', function() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.style.display = 'none';

            input.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    document.getElementById('image').value = '../img/' + file.name;
                }
            });

            document.body.appendChild(input);
            input.click();
            document.body.removeChild(input);
        });

        // Show the add service form
        document.getElementById('show-add-form-button').addEventListener('click', function() {
            document.getElementById('add-service-form').style.display = 'block';
            this.style.display = 'none';
        });

        // Hide the add service form after submission
        document.getElementById('add-service-form').addEventListener('submit', function() {
            this.style.display = 'none';
            document.getElementById('show-add-form-button').style.display = 'block';
        });

        // Show alerts based on URL parameters
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('service_added') && urlParams.get('service_added') == 1 && urlParams.has('name')) {
            showAlert(urlParams.get('name') + ' added successfully!', 'success');
        }

        if (urlParams.has('service_updated') && urlParams.get('service_updated') == 1 && urlParams.has('id')) {
            showAlert('Service price updated successfully!', 'info');
        }

        if (urlParams.has('service_removed') && urlParams.get('service_removed') == 1 && urlParams.has('name')) {
            showAlert(urlParams.get('name') + ' removed successfully!', 'danger');
        }
    });

    function editService(id) {
        document.getElementById('update-service-form-' + id).style.display = 'block';
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
            alert.classList.remove('show');
            alert.addEventListener('transitionend', () => {
                alert.remove();
            });
        }, 3000);
    }
</script>
<script src="../js/admin.js"></script>
</body>
</html>
