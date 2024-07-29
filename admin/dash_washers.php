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

<div class="header">
  <i class="fas fa-bars" id="sidebarToggle"></i>

 <h1>Sparkle Wash</h1>
    
    <div class="profile-container">
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="profile-picture" id="profileIcon" alt="Profile Picture">
        <div class="options" id="options">
            <a href="../admin/washer_profile.php" id="profile">Profile</a>
            <a href="../admin/washers_settings.php" id="settings">Settings</a>
            <a href="../auth/logout.php" id="logout">Logout</a>
        </div>
    </div>
</div>

<!-- Sidebar start -->
<div class="sidebar collapsed" id="sidebar">
        <a href="../admin/washers_dash.php" class="nav-link"><i class="fas fa-home"></i><span class="sidebar-text">Home</span></a>
       
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('login') && urlParams.get('login') === 'success') {
                showAlert('Login Successful. Welcome <?php echo $_SESSION['username']; ?>', 'success');
            }
        });

  

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            const sidebarToggle = document.getElementById("sidebarToggle");
            const navLinks = document.querySelectorAll(".nav-link");

            // Collapse the sidebar by default
            sidebar.classList.add("collapsed");
            content.classList.add("collapsed");

            sidebarToggle.addEventListener("click", function() {
                sidebar.classList.toggle("collapsed");
                content.classList.toggle("collapsed");
            });

            // Highlight the active page in the sidebar
            navLinks.forEach(link => {
                if (link.href === window.location.href) {
                    link.classList.add("active");
                }
            });
        });
    </script>