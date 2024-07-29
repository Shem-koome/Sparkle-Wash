<?php
include 'dash.php'; // Assuming this file contains necessary includes and functions
ob_start(); // Start output buffering to prevent "headers already sent" issue

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Include necessary files
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
        header('Location: profile.php');
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
            header('Location: profile.php');
            exit();
        } else {
            echo "Failed to update profile picture in database.";
        }
    } else {
        echo "Failed to upload profile picture.";
    }
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

<!DOCTYPE html>
<html lang="en">
<head>
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

       
        <!-- Customized Bootstrap Stylesheet -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="../css/style.css" rel="stylesheet">
            <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
 
    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    <!-- Custom CSS -->
    <style>
           .content {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left; /* Ensure text inside content is left-aligned */
            margin-top: 40px; /* Adjust the top margin to move content down */
        }

        .user-type {
            font-size: 30px;
            color: #007bff;
        }

        .profile-info {
            margin-bottom: 30px;
        }

        .profile-info img {
            width: 150px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        #uploadForm {
            display: none; /* Initially hide the upload form */
        }

        /* Style for <p> elements */
        .content p {
            color: black; /* Set text color to black */
            font-size: 16px; /* Adjust font size as needed */
            margin-bottom: 10px; /* Example margin bottom */
        }
    </style>
</head>
<body>

    <div class="content" id="content">
        <h2>Your Profile</h2>
        <div class="profile-info">
            <?php if (!empty($profile_picture)): ?>
                <!-- Display existing profile picture with option to change -->
                <a href="#" onclick="showUploadForm()">
                    <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                </a>
                <br><br>
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
                    </form>
                    <!-- Form to remove profile picture -->
                    <form method="post">
                        <input type="hidden" name="profile_pic" value="1">
                        <button class="btn btn-outline-danger" type="submit">Remove Profile Picture</button>
                    </form>
                </div>
            <?php else: ?>
                <!-- Form to upload new profile picture if none exists -->
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
        <!-- Display user information -->
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Gender:</strong> <?php echo ucfirst(htmlspecialchars($gender)); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($dob); ?></p>
        <p><strong>User Type:</strong> <span class="user-type"><?php echo ucfirst(htmlspecialchars($user_type)); ?></span></p>
    </div>

<!-- JavaScript for showing/hiding upload form -->
<script>
    function showUploadForm() {
        var uploadForm = document.getElementById('uploadForm');
        if (uploadForm.style.display === 'none') {
            uploadForm.style.display = 'block';
        } else {
            uploadForm.style.display = 'none';
        }
    }
</script>

<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Template Javascript -->
<script src="../js/main.js"></script>

</body>
</html>