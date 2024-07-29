<?php

    // Database connection parameters
$host = 'localhost'; // Change this to your database host
$dbname = 'wash'; // Change this to your database name
$username = 'root'; // Change this to your database username
$password = ''; // Change this to your database password

// Create a new MySQLi instance
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

?>