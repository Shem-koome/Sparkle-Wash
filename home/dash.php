<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Include necessary files
include '../auth/config.php';

// Get the user details from the session
$username = $_SESSION['username'];
echo "<script>console.log('Your name is: " . htmlspecialchars($username) . "');</script>";

// Fetch user profile information including profile picture
$sql = "SELECT profile_picture FROM logins WHERE username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $username); // Corrected binding here
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Log the profile picture URL to console
echo "<script>console.log('Your profile picture is: " . htmlspecialchars($profile_picture) . "');</script>";
?>
    <style>
        .profile-container {
            position: relative;
            display: inline-block;
        }

        .profile-picture {
            width: 50px;
            height: 60px;
            border-radius: 50%;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            object-fit: cover; /* Ensure image fits well in the container */
            z-index: 1050;
        }

        .options {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1;
        }

        .options a {
            display: block;
            text-decoration: none;
            color: #333;
            padding: 5px 0;
        }

        .options a:hover {
            background-color: #f0f0f0;
        }

        @media (max-width: 560px) {
            .profile-container {
                position: absolute;
                bottom: 410px;
                left: 280px;
                top: 40px;
            }

            .profile-picture {
                position: relative;
                margin-left: auto;
                margin-right: 20px;
            }

            .options {
                top: 50px;
            }
        }

        /* Alert Styles */
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

    <div id="alert-placeholder"></div>

    <!-- Navbar start -->
    <div class="container-fluid bg-light">
        <div class="container px-0">
            <nav class="navbar navbar-light navbar-expand-xl">
                <h1 class="text-primary display-4">Sparkle Wash</h1>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-light py-3" id="navbarCollapse">
                    <div class="navbar-nav mx-auto border-top">
                        <a href="../home/Home.php" class="nav-item nav-link">Home</a>
                        <a href="../about/about.php" class="nav-item nav-link">About</a>
                        <a href="../service/service.php" class="nav-item nav-link">Services</a>
                        <a href="../booking/cart.php" class="nav-item nav-link">Cart</a>
                        <a href="../booking/booked.php" class="nav-item nav-link">Bookings</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="../about/team.php" class="dropdown-item">Team</a>
                                <a href="../about/testimonial.php" class="dropdown-item">Testimonial</a>
                            </div>
                        </div>
                        <a href="../about/parking.php" class="nav-item nav-link">Parking</a>
                        <a href="../about/contact.php" class="nav-item nav-link">Contact Us</a>
                    </div>

                    <div class="profile-container">
                        <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="profile-picture" id="profileIcon" alt="Profile Picture">
                        <div class="options" id="options">
                            <a href="../home/profile.php" id="profile">Profile</a>
                            <a href="../home/settings.php" id="settings">Settings</a>
                            <a href="../auth/logout.php" id="logout">Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const profileIcon = document.getElementById("profileIcon");
            const options = document.getElementById("options");

            profileIcon.addEventListener("click", function () {
                options.style.display = options.style.display === "block" ? "none" : "block";
            });

            const profileOption = document.getElementById("profile");
            profileOption.addEventListener("click", function () {
                console.log("Profile option clicked");
            });

            const settingsOption = document.getElementById("settings");
            settingsOption.addEventListener("click", function () {
                console.log("Settings option clicked");
            });

            const logoutOption = document.getElementById("logout");
            logoutOption.addEventListener("click", function () {
                console.log("Logout option clicked");
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('login') && urlParams.get('login') === 'success') {
                showAlert('Login Successful. Welcome <?php echo $_SESSION['username']; ?>', 'success');
            }
        });
        function showAlert(message, type) {
            var alertDiv = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            document.getElementById('alert-placeholder').innerHTML = alertDiv;
        }
    </script>
