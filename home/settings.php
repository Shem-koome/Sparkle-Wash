<?php
include 'dash.php';
include '../auth/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];

$message = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    if (isset($_POST['update_details'])) {
        // Update user details
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        
        $updateStmt = $mysqli->prepare("UPDATE logins SET phone = ?, gender = ?, dob = ? WHERE username = ?");
        $updateStmt->bind_param("ssss", $phone, $gender, $dob, $username);

        if ($updateStmt->execute()) {
            $message = "Details updated successfully.";
            $alertType = 'success';
        } else {
            $message = "Error updating details.";
            $alertType = 'danger';
        }
        $updateStmt->close();
    } 

    $mysqli->close();
}
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
    label {
        color: black;
    }
</style>
</head>
<body>
<div id="alert-placeholder"></div>
<div class="content" id="content">
    <div class="container mt-5">
        <h2>Settings</h2>
    
        <!-- Update User Details Form -->
        <form method="POST" class="mb-4">
            <h4>Update Your Info</h4>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="username" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob">
            </div>
            <br>
            <button type="submit" name="update_details" class="btn btn-primary">Update Details</button>
        </form>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $alertType; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
