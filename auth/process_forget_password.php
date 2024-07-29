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
include('config.php'); // Ensure you have your database connection setup in this file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to display Bootstrap alerts
function showAlert($message, $alertType = 'success', $redirect = 'verify_code.php') {
    echo '<div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">';
    echo $message;
    echo '</div>';
    echo '<script>';
    echo 'setTimeout(function() { window.location.href = "' . $redirect . '"; }, 3000);'; // Redirect after 3 seconds
    echo '</script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $math_answer = $_POST['math_answer'];
    
    // Validate math answer
    if (isset($_SESSION['math_answer']) && $math_answer == $_SESSION['math_answer']) {
        // Math answer is correct, proceed with password reset process
        $_SESSION['email'] = $email; // Store email in session
        
        // Generate a 6-digit reset code
        $reset_code = rand(100000, 999999);
        $_SESSION['reset_code'] = $reset_code; // Store reset code in session
        
        // Calculate expiration time (1 hour from now)
        $expiration_time = time() + 3600; // 3600 seconds = 1 hour
        $_SESSION['reset_code_expire'] = $expiration_time; // Store expiration time in session
        
        // Send the reset code to the user's email using PHPMailer
        require '../PHPMailer-master/src/Exception.php';
        require '../PHPMailer-master/src/PHPMailer.php';
        require '../PHPMailer-master/src/SMTP.php';
        

        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;
            $mail->Username = 'millionaireentreprise@gmail.com'; // Your email address
            $mail->Password = 'onrt rvzu biox vfvc'; // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('millionaireentreprise@gmail.com', 'Millionaire Entreprise');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body = "Your password reset code is: $reset_code <br> <br> <br> This code will expire in 1 hour.";

            $mail->send();

            // Redirect to the code verification page (e.g., verify_code.php)
            // Show success message via showAlert function and redirect
        showAlert("Reset code has been successfully sent to your email", "success", "verify_code.php");
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Failed to send reset code. Please try again. Mailer Error: {$mail->ErrorInfo}";
            header("Location: forget_password.php?email=" . urlencode($email));
            exit();
        }
    } else {
        // Math answer is incorrect
        $_SESSION['error_message'] = "Incorrect answer.";
        // Redirect back to forget_password.php with error message
        header("Location: forget_password.php?email=" . urlencode($email));
        exit();
    }
} else {
    // If the request method is not POST, redirect to forget_password.php
    header("Location: forget_password.php");
    exit();
}
?>
