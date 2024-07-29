<?php
session_start();
include('../auth/config.php'); // Adjust the path as needed

$response = array(
    'carsWashed' => 0,
    'grossIncome' => 0,
    'netIncome' => 0,
    'numClients' => 0,
    'error' => ''
);

function formatDateForMySQL($date) {
    return date('Y-m-d', strtotime($date));
}

if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $startDate = formatDateForMySQL($_POST['startDate']);
    $endDate = formatDateForMySQL($_POST['endDate']);
    $dateFilter = " AND `date` BETWEEN '$startDate' AND '$endDate'";
} else {
    $dateFilter = ""; // Default: no date filter
}

$queryCarsWashed = "SELECT COUNT(DISTINCT vehicle_plate) AS carsWashed FROM orders WHERE 1 $dateFilter";
$queryGrossIncome = "SELECT SUM(total_price) AS grossIncome FROM orders WHERE 1 $dateFilter";
$queryNetIncome = "SELECT SUM(total_price - commission) AS netIncome FROM orders WHERE 1 $dateFilter";
$queryNumClients = "SELECT COUNT(DISTINCT user_id) AS numClients FROM orders WHERE 1 $dateFilter";

$resultCarsWashed = $mysqli->query($queryCarsWashed);
if ($resultCarsWashed) {
    $row = $resultCarsWashed->fetch_assoc();
    $response['carsWashed'] = $row['carsWashed'];
} else {
    $response['error'] .= 'Error fetching cars washed: ' . $mysqli->error . '\n';
}

$resultGrossIncome = $mysqli->query($queryGrossIncome);
if ($resultGrossIncome) {
    $row = $resultGrossIncome->fetch_assoc();
    $response['grossIncome'] = $row['grossIncome'];
} else {
    $response['error'] .= 'Error fetching gross Income: ' . $mysqli->error . '\n';
}

$resultNetIncome = $mysqli->query($queryNetIncome);
if ($resultNetIncome) {
    $row = $resultNetIncome->fetch_assoc();
    $response['netIncome'] = $row['netIncome'];
} else {
    $response['error'] .= 'Error fetching net Income: ' . $mysqli->error . '\n';
}

$resultNumClients = $mysqli->query($queryNumClients);
if ($resultNumClients) {
    $row = $resultNumClients->fetch_assoc();
    $response['numClients'] = $row['numClients'];
} else {
    $response['error'] .= 'Error fetching number of clients: ' . $mysqli->error . '\n';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
