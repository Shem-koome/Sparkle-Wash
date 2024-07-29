<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Assuming password is not hashed in the database
    
    // Prepare the SQL statement
    $sql = "SELECT * FROM logins WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $mysqli->error);
    }

    // Bind the parameters
    $stmt->bind_param("s", $email);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the result
        $result = $stmt->get_result();
        
        // Check if a row was returned
        if ($result->num_rows === 1) {
            // Fetch the row
            $row = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Password is correct, set session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_type'] = $row['user_type']; // Store user type in session

                // Check user type and redirect accordingly
                if ($row['user_type'] == 'super') {
                    header("Location: ../admin/admin_dash.php?login=success"); // Redirect for super user
                }
                 elseif (($row['user_type'] == 'washer')) {
                    header("Location: ../admin/washers_dash.php?login=success"); // Redirect for washers user
                }                    
                else {
                    header("Location: ../home/Home.php?login=success"); // Redirect for regular user
                }
                exit();
            } else {
                // Invalid password
                header("Location: login.php?error=invalid_password");
                exit();
            }
        } else {
            // Account not found
            header("Location: create.php?error=account_not_found");
            exit();
        }
        
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>
