<style>
                /* Alert Styles */
.alert {
    position: fixed;
    top: 10px; /* Adjust as needed */
    right: 10px; /* Adjust as needed */
    z-index: 2000;
    width: 200px; /* Square width */
    height: 100px; /* Square height */
    padding: 10px; /* Padding inside the alert */
    border-radius: 10px; /* Rounded corners */
    overflow: hidden; /* Ensure content does not overflow */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, 0.9); /* White background with opacity */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Shadow for depth */
}

/* Alert Colors */
.alert-success {
    background-color: rgba(40, 167, 69, 0.9); /* Green with 90% opacity */
    color: white;
}

.alert-warning {
    background-color: rgba(255, 193, 7, 0.9); /* Yellow with 90% opacity */
    color: black; /* Adjust text color for visibility */
}
.alert-info {
    background-color: rgba(7, 69, 255, 0.9); /* Yellow with 90% opacity */
    color: black; /* Adjust text color for visibility */
}

.alert-danger {
    background-color: rgba(220, 53, 69, 0.9); /* Red with 90% opacity */
    color: white;
}

/* Close Button */
.alert .btn-close {
    color: inherit;
    cursor: pointer;
    position: absolute;
    top: 5px; /* Adjust position */
    right: 5px; /* Adjust position */
    padding: 5px;
}

/* Animation */
@keyframes slideDown {
    0% {
        transform: translateY(-100%);
    }
    100% {
        transform: translateY(0);
    }
}

@keyframes slideUp {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-100%);
    }
}

.fade {
    animation-duration: 0.5s;
}

.alert-dismissible.fade.show {
    animation-name: slideDown;
}

.alert-dismissible.fade {
    animation-name: slideUp;
}
 </style>
<?php
session_start();
include('config.php');

if (!isset($_SESSION['email'])) {
    header("Location: forget_password.php");
    exit();
}
// Function to display Bootstrap alerts
function showAlert($message, $alertType = 'success', $redirect = 'login.php') {
    echo '<div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">';
    echo $message;
    echo '</div>';
    echo '<script>';
    echo 'setTimeout(function() { window.location.href = "' . $redirect . '"; }, 3000);'; // Redirect after 3 seconds
    echo '</script>';
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = mysqli_real_escape_string($mysqli, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    $email = $_SESSION['email'];

    $sql = "UPDATE logins SET password='$hashed_password' WHERE email='$email'";

    if (mysqli_query($mysqli, $sql)) {
        session_destroy();
        showAlert("Password reset successfully", "success", "login.php");
    } else {
        $error_message = "Error updating password: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php
        if (isset($error_message)) {
            echo '<p class="error">' . $error_message . '</p>';
        }
        ?>
        <form action="" method="POST">
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
