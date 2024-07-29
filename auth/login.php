<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MILLIONAIRE'S EMPLOYEES FORM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/script.js"></script>
    <style>
        /* Your custom styles for alerts */
        .alert {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 2000;
            width: 200px;
            height: 100px;
            padding: 10px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .alert-warning {
            background-color: rgba(255, 193, 7, 0.9);
            color: black;
        }

        .alert-info {
            background-color: rgba(7, 69, 255, 0.9);
            color: black;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.9);
            color: white;
        }

        .alert .btn-close {
            color: inherit;
            cursor: pointer;
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 5px;
        }

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
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center" style="display: none;">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->
    <div id="alert-placeholder"></div>
    <form action="signin.php" method="post">
        <h1>SIGN IN</h1>
        <!-- PHP code for displaying error messages -->
        <?php
            if (isset($_GET['error'])) {
                $error = htmlspecialchars($_GET['error']);
                if ($error == 'invalid_password') {
                    echo '<div class="alert alert-danger" role="alert">Invalid Email or password. Please try again.</div>';
                } elseif ($error == 'account_not_found') {
                    echo '<div class="alert alert-danger" role="alert">Account not found. Please create an account.</div>';
                }
            }
        ?>
        <div class="input-prepend">
            <span class="add-on"><i class="fas fa-envelope"></i></span>
            <input class="span2" type="email" name="email" id="email" placeholder="Email address" required>
        </div>
        <div class="input-prepend">
            <span class="add-on"><i class="fas fa-lock"></i></span>
            <input class="span2" type="password" name="password" id="password" placeholder="Password" required>
            <span class="add-on toggle-password"><i class="fas fa-eye" id="togglePassword"></i></span>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="#" id="forgotPasswordLink">Forgot Password</a>
        </div>
        <button type="submit">Submit</button>
        <hr>
        Don't have an account? <a href="create.php" id="signUpLink">Sign Up</a>
    </form>

    <!-- JavaScript section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to show alerts
            function showAlert(message, type) {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.role = 'alert';
                alert.innerHTML = `${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                alertPlaceholder.appendChild(alert);
    
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.addEventListener('transitionend', () => {
                        alert.remove();
                    });
                }, 3000);
            }

            // Event listener for Forgot Password link
            document.getElementById('forgotPasswordLink').addEventListener('click', function(event) {
                event.preventDefault();
                
                var email = document.getElementById('email').value;
                if (email.trim() === '') {
                    showAlert('Please input your email address first.', 'warning');
                } else {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'check_email.php');
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.exists) {
                                window.location.href = 'forget_password.php?email=' + encodeURIComponent(email);
                            } else {
                                showAlert('Email does not exist. Please try again.', 'danger');
                            }
                        } else {
                            showAlert('Error checking email existence. Please try again.', 'info');
                        }
                    };
                    xhr.send('email=' + encodeURIComponent(email));
                }
            });
        });
    </script>
</body>
</html>
