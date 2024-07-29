<?php
// check_email_existence.php

session_start();
include('config.php');

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the email exists in the logins table
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // Prepare SQL statement
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM logins WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Respond with JSON indicating if email exists
    $response = array();
    $response['exists'] = ($result['count'] > 0);

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $e) {
    // Handle database connection error
    $response = array('exists' => false, 'error' => 'Database connection error: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>