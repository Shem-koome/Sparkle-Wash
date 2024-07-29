<?php
// Include configuration file
include('../auth/config.php');

// Use the same database connection credentials
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Fetch washers from the database
$stmt = $pdo->query('SELECT * FROM washers');
$washers = $stmt->fetchAll();

// Return washers data as JSON
header('Content-Type: application/json');
echo json_encode($washers);
?>
