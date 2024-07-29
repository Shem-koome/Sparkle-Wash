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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

// Function to display Bootstrap alerts
function showAlert($message, $alertType = 'success', $redirect = '../home/Home.php') {
    echo '<div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">';
    echo $message;
    echo '</div>';
    echo '<script>';
    echo 'setTimeout(function() { window.location.href = "' . $redirect . '"; }, 3000);'; // Redirect after 3 seconds
    echo '</script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent injection attacks
    $name = htmlspecialchars($_POST['name']);
    $subject = htmlspecialchars($_POST['subject']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Validate email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showAlert("Invalid email format", 'danger');
        exit;
    }

    // Initialize PHPMailer
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

        // Sender and recipient details
        $mail->setFrom($email, $name); // Set the sender's email and name
        $mail->addReplyTo($email, $name); // User's email and name
        $mail->addAddress('millionaireentreprise@gmail.com', 'Millionaire Entreprise'); // Add yourself as the recipient
        
        // Email content
        $mail->isHTML(true); // Set email format to HTML

        // Email subject
        $mail->Subject = $subject;

        // HTML content
        $htmlContent = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>$subject</title>
            </head>
            <body>
                <h2>New Mail from Sparkle Wash</h2>
                <p><strong>Sender's Name:</strong> $name</p>
                <p><strong>Sender's Email:</strong> <a href='mailto:$email'>$email</a></p>
                <hr>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            </body>
            </html>
        ";

        // Set HTML body
        $mail->Body = $htmlContent;

        // Set plain text alternative (for clients that don't support HTML)
        $mail->AltBody = "Name: $name\nEmail: $email\nMessage:\n$message";

        // Send email
        $mail->send();

        // Show success message via showAlert function and redirect
        showAlert("Email sent successfully", "success", "../home/Home.php");

    } catch (Exception $e) {
        // Show error message via showAlert function and redirect
        showAlert("Email couldn't be sent. Mailer Error: " . $mail->ErrorInfo, "danger", "contact.php");
    }
} else {
    // Show invalid request method error via JavaScript alert
    echo '<script>';
    echo 'showAlert("Invalid request method.", "danger", "contact.php");';
    echo '</script>';
}
?>

