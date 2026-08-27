<?php
header('Content-Type: application/json');
include "dbconnect.php";

// Count how many times each packageID appears in bookings
$sql = "SELECT packageID, COUNT(*) as total FROM bookings GROUP BY packageID";
$result = mysqli_query($conn, $sql);

// Prepare arrays
$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = "Package #" . $row['packageID']; // or fetch name with JOIN below
    $data[] = (int)$row['total'];
}

echo json_encode([
    "labels" => $labels,
    "data" => $data
]);
?>
