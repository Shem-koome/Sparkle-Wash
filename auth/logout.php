<?php
session_start();

include('config.php');

$_SESSION = array();

// Destroy all session data
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>