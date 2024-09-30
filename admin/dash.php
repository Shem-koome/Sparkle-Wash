<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit();
}
// Include necessary files
include '../auth/config.php';

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

$unreadCount = isset($_SESSION['unreadNotifications']) ? $_SESSION['unreadNotifications'] : 0;
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
       .notification-counter {
    display: none; /* Hide the counter by default */
    position: relative; /* Position it relative to the parent */
    top: 40%; /* Center vertically */
    right: 29%; /* Adjust right positioning */
    transform: translateY(-50%);
    background-color: #3def00 !important; /* Background color for the counter */
    color: white; /* Text color for the counter */
    padding: 3px 8px; /* Padding around the counter */
    border-radius: 50%; /* Rounded shape for the counter */
    font-size: 12px; /* Font size for the counter */
    z-index: 1000; /* Ensure it appears above other elements */
}

.has-notifications .notification-counter {
    display: inline-block; /* Show the counter */
}

.add-icon {
    font-size: 30px; /* Adjust the font size as needed */
    cursor: pointer; /* Add cursor pointer for interaction */
    margin-left: 10px; /* Optional: Add some spacing between icons */
}
.add-icon:hover {
    color:blue;
}
/* CSS to position the date-time picker */
#dateTimePicker {
    position: absolute;
    left: 30%;
    top: 120%; /* Position below the input */
    z-index: 1000; /* Ensure it appears above other content */
    background-color: #fff; /* Optional: Set a background color */
    padding: 10px; /* Optional: Add padding for better spacing */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add box shadow for depth */
}

</style>

<div class="header">
    <i class="fas fa-bars" id="sidebarToggle"></i>
    <h1>Sparkle Wash</h1>
    <!-- Notification icon and counter -->
    <a href="notifications.php" id="notificationLink" class="has-notifications">
        <i class="fas fa-bell"></i>
        <span id="notificationCounter" class="notification-counter"><?php echo $unreadCount; ?></span>
    </a>
    <div class="profile-container">
    <img src="<?php echo htmlspecialchars($profile_picture); ?>" class="profile-picture" id="profileIcon" alt="Profile Picture">
        <div class="options" id="options">
            <a href="../admin/admin_profile.php" id="profile">Profile</a>
            <a href="../admin/admin_settings.php" id="settings">Settings</a>
            <!--<a href="#" id="help">Help</a> -->
            <a href="../auth/logout.php" id="logout">Logout</a>
        </div>
    </div>
    
</div>

<!-- Sidebar start -->
<div class="sidebar collapsed" id="sidebar">
    <a href="admin_dash.php" class="nav-link"><i class="fas fa-home"></i><span class="sidebar-text">Home</span></a>
    <a href="admin_bookings.php" class="nav-link"><i class="fas fa-calendar-check"></i><span class="sidebar-text">Bookings</span></a>
    <a href="admin_washers.php" class="nav-link"><i class="fas fa-users"></i><span class="sidebar-text">Washers</span></a>
    <a href="admin_services.php" class="nav-link"><i class="fas fa-tools"></i><span class="sidebar-text">Services</span></a>
    <a href="admin_parking.php" class="nav-link"><i class="fas fa-parking"></i><span class="sidebar-text">Parking</span></a>
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
        // Update notification counter visibility based on count
const notificationCounter = document.getElementById("notificationCounter");
if (parseInt(notificationCounter.textContent.trim()) === 0) {
    notificationCounter.style.display = 'none';
} else {
    notificationCounter.style.display = 'inline-block';
}

    });
    
</script>
