<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    if ($password === false) {
    // Handle password hashing error
    die("Error hashing password");
}
    // Check if the email already exists in the database
    $check_email_query = "SELECT * FROM logins WHERE email=?";
    $check_stmt = $mysqli->prepare($check_email_query);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Email exists, set session variable for error message
        $_SESSION['error_message'] = "An account with this email already exists. Please Sign in instead.";
        // Redirect to login page
        header("Location: login.php");
        exit();
        }

    // Prepare the SQL statement
    $sql = "INSERT INTO logins (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $mysqli->error);
    }

    // Bind the parameters
    $stmt->bind_param("sss", $username, $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
// Redirect to login.php after successful registration after some seconds
header("Location: login.php");
exit();

    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>
