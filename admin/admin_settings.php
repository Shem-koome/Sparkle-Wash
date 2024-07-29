<?php
session_start();
include '../auth/config.php';
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

$username = $_SESSION['username'];

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
<?php include 'dash.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sparkle Wash - Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="../js/admin.js"></script>
</head>
<body>
<div id="alert-placeholder"></div>
<div class="content" id="content">
    <div class="container mt-5">
        <h2>Settings</h2>
        
        <!-- Button to show the change user type form -->
        <button class="btn btn-primary mb-4" id="show-change-form-button">Change User Type</button>

        <!-- Change User Type Form (hidden by default) -->
        <form method="POST" class="mb-4" id="change-user-type-form" style="display: none;">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="user_type">User Type</label>
                <select class="form-control" id="user_type" name="user_type" required>
                    <option value="user">User</option>
                    <option value="washer">Washer</option>
                    <option value="super">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

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
            <button type="submit" name="update_details" class="btn btn-primary">Update Details</button>
        </form>


        <?php if (isset($message)): ?>
            <script>
                showAlert('<?php echo $message; ?>', '<?php echo $alertType; ?>');
            </script>
        <?php endif; ?>
    </div>
</div>

<script>
    // Show the change user type form when the "Change User Type" button is clicked
    document.getElementById('show-change-form-button').addEventListener('click', function() {
        document.getElementById('change-user-type-form').style.display = 'block';
        this.style.display = 'none';
    });
</script>

</body>
</html>
