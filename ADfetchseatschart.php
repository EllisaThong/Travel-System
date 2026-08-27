<?php
header('Content-Type: application/json');
include "dbconnect.php";

// Query: count how many of each seat type
$sql = "SELECT seatType, COUNT(*) as total FROM flight_routes GROUP BY seatType";
$result = mysqli_query($conn, $sql);

// Prepare arrays for chart data
$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['seatType'];
    $data[] = (int)$row['total'];
}

// Return as JSON
echo json_encode([
    "labels" => $labels,
    "data" => $data
]);
?>
