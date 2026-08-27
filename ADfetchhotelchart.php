<?php
include "dbconnect.php"; 

$sql = "SELECT hotelRoomTypes, COUNT(*) as total FROM hotels GROUP BY hotelRoomTypes";
$result = mysqli_query($conn, $sql);

$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['hotelRoomTypes'];
    $data[] = (int)$row['total'];
}

echo json_encode([
    "labels" => $labels,
    "data" => $data
]);
?>
