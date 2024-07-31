<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../auth/config.php';

$username = $_SESSION['username'];

// Handle profile picture removal
if (isset($_POST['remove_profile_pic'])) {
    $updateQuery = "UPDATE logins SET profile_picture = NULL WHERE username = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("s", $username);
    if ($stmt->execute()) {
        // Successfully removed profile picture
        // Redirect to refresh the profile page
        header('Location: washer_profile.php');
        exit();
    } else {
        echo "Failed to remove profile picture.";
    }
}

// Handle file upload
if (isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $uploadDir = '../img/';
    $uploadFile = $uploadDir . basename($file['name']);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (!in_array($imageFileType, $allowedExtensions)) {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
        exit();
    }

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        // Update profile picture path in database
        $updateQuery = "UPDATE logins SET profile_picture = ? WHERE username = ?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param("ss", $uploadFile, $username);
        if ($stmt->execute()) {
            header('Location: washer_profile.php');
            exit();
        } else {
            echo "Failed to update profile picture in database.";
        }
    } else {
        echo "Failed to upload profile picture.";
    }
}

// Assuming that 'id' in the `washers` table corresponds to 'username'
$stmt = $mysqli->prepare('SELECT commission FROM washers WHERE name = ?');
$stmt->bind_param('s', $username);  // Use 's' for string if username is a string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $commission = $row['commission'];
} else {
    $commission = 'N/A';  // Change to something more informative
}


// Fetch user profile information including profile picture
$query = "SELECT username, email, phone, gender, dob, user_type, profile_picture FROM logins WHERE username = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($username, $email, $phone, $gender, $dob, $user_type, $profile_picture);
$stmt->fetch();
$stmt->close();
?>
<?php include 'dash_washers.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sparkle Wash - Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
    <script src="../js/admin.js"></script>
    <style>
        .user-type {
            font-size: 30px;
            color: #007bff; /* blue color example */
        }
        .profile-info {
            margin-bottom: 50px;
        }
               .profile-info img {
            width: 150px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer; /* Add pointer cursor to indicate clickability */
                    }
        #uploadForm {
            display: none; /* Initially hide the upload form */
        }
    </style>
</head>
<body>
<div class="content" id="content">
    <h2>Your Profile</h2>
    <div class="profile-info">
        <?php if (!empty($profile_picture)): ?>
            <!-- Wrap profile picture in a clickable link to show form -->
            <a href="#" onclick="showUploadForm()">
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
            </a>
             <br>
            <!-- Hidden form to upload new profile picture -->
            <div id="uploadForm">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="profile-picture">Upload New Profile Picture</label>
                        <input type="file" class="form-control-file" id="profile-picture" name="profile_picture" accept="image/*">
                        <br>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                     <!-- Button to remove profile picture -->
            <form method="post">
                <input type="hidden" name="remove_profile_pic" value="1">
                <button class="btn btn-outline-danger" type="submit">Remove Profile Picture</button>
            </form>
                </form>
            </div>
        <?php else: ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile-picture">Upload Profile Picture</label>
                    <input type="file" class="form-control-file" id="profile-picture" name="profile_picture" accept="image/*">
                    <br>
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
    <p><strong>Gender:</strong> <?php echo ucfirst(htmlspecialchars($gender)); ?></p>
    <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
    <p><strong>Commission:</strong> <?php echo htmlspecialchars($commission); ?>%</p>
    <p><strong>User Type:</strong> <span class="user-type"><?php echo ucfirst(htmlspecialchars($user_type)); ?></span></p>
</div>

<script>
    function showUploadForm() {
        // Toggle the visibility of the upload form
        var uploadForm = document.getElementById('uploadForm');
        if (uploadForm.style.display === 'none') {
            uploadForm.style.display = 'block';
        } else {
            uploadForm.style.display = 'none';
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
