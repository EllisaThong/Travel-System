<?php
include 'dbconnect.php';

function getCount($conn, $table, $label, $isMoney = false) {
    $query = $isMoney ? "SELECT SUM(amount) AS total FROM $table" : "SELECT COUNT(*) AS total FROM $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $value = $isMoney ? ('RM ' . number_format($row['total'] ?? 0, 2)) : ($row['total'] ?? 0);
    echo "<div class='summary-card'><h3>$label</h3><p>$value</p></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="ADadminsummary.css?v=2.8">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <script src="adminsummary.js"></script>

</head>
<body>

<div class="welcomeboard">
  <div class="welcome-row">
    <h2>Quick Review</h2>
    <p class="todayDate"><strong><?php echo "Today's Date: " . date("l , d M Y"); ?></p></strong>
  </div>
</div>

<!-- Charts section -->
<div class="charts-row">
  <div class="charts">
    <canvas id="hotelChart" style="height:200px;max-width:200px"></canvas>
  </div>
  <div class="charts">
    <canvas id="seatChart" style="height:200px;max-width:200px"></canvas>
  </div>
  <div class="charts">
    <canvas id="bookingChart" style="height:200px;max-width:200px"></canvas>
  </div>
</div>


  <!--summary card-->
<div class="summary-section">
<?php
    getCount($conn, "users", "Total Users");
    getCount($conn, "bookings", "Total Bookings");
    getCount($conn, "packages", "Total Packages");
    getCount($conn, "flight_routes", "Total Flights");
    getCount($conn, "hotels", "Total Hotels");
    ?>
  </div>
</body>
</html>

