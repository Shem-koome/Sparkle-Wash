<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sparkle Wash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function showSpinnerAndRedirect() {
                // Show the spinner
                document.getElementById('spinner').classList.add('show');
                // Redirect after 3 seconds
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 1000);
            }

            // Add click event listener to the SIGN UP link
            document.getElementById('signInLink').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                showSpinnerAndRedirect();
            });
        });
    </script>
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center" style="display: none;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->
    <form action="signup.php" method="post">
        <h1>SIGN UP</h1>
        <?php
session_start(); // Start the session

if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
    if ($error == 'invalid_password') {
        echo '<div class="alert alert-danger" role="alert">Invalid Email or password. Please try again.</div>';
    } elseif ($error == 'account_not_found') {
        echo '<div class="alert alert-danger" role="alert">Account not found. Please create an account.</div>';
    }
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']); // Clear the error message after displaying it
}


?>

        <div class="input-prepend">
            <span class="add-on"><i class="fas fa-user"></i></span>
            <input class="span2" type="text" name="username" id="username" placeholder="Username" required>
        </div>

        <div class="input-prepend">
            <span class="add-on"><i class="fas fa-envelope"></i></span>
            <input class="span2" type="email" name="email" id="email" placeholder="Email address" required>
        </div>

        <div class="input-prepend">
            <span class="add-on"><i class="fas fa-lock"></i></span>
            <input class="span2" type="password" name="password" id="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
            <span class="add-on toggle-password"><i class="fas fa-eye" id="togglePassword"></i></span>
        </div>

        <div class="input-prepend">
            <span class="add-on"><i class="fas fa-key"></i></span>
            <input class="span2" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
        </div>


        <button type="submit">Submit</button>

        <hr>
        Already have an account? <a href="login.php" id="signInLink">Sign In</a> <!-- Add ID for easier selection -->
    </form>

    <div id="message">
        <h3>Password must contain the following:</h3>
        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
        <p id="number" class="invalid">A <b>number</b></p>
        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
    </div>


</body>
</html>
