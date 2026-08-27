<?php
session_start();
include("dbconnect.php");

// Get filter values safely
$departure = $_GET['departure'] ?? '';
$arrival = $_GET['arrival'] ?? '';
$seatType = $_GET['seatType'] ?? '';
$searchFlightName = $_GET['search'] ?? '';

// Base query
$sql = "SELECT * FROM flight_routes WHERE 1=1";
$params = [];
$types = "";

// Append filters
if (!empty($searchFlightName)) {
    $sql .= " AND airlineProvide LIKE ?";
    $params[] = "%$searchFlightName%";
    $types .= "s";
}
if (!empty($departure)) {
    $sql .= " AND routeDeparturePoint LIKE ?";
    $params[] = "%$departure%";
    $types .= "s";
}
if (!empty($arrival)) {
    $sql .= " AND routeArrivalPoint LIKE ?";
    $params[] = "%$arrival%";
    $types .= "s";
}
if (!empty($seatType)) {
    $sql .= " AND seatType = ?";
    $params[] = $seatType;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flight Routes</title>
    <link rel="stylesheet" href="userflights.css">
</head>
<body>
<?php include("header.php") ?>
<h1 class="page-title">Available Flights</h1>

<div class="filter-form">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search Airline (e.g., AirAsia)" value="<?= htmlspecialchars($searchFlightName) ?>">
        <input type="text" name="departure" placeholder="From (e.g., KLIA)" value="<?= htmlspecialchars($departure) ?>">
        <input type="text" name="arrival" placeholder="To (e.g., Penang)" value="<?= htmlspecialchars($arrival) ?>">
        <select name="seatType">
            <option value="">All Seat Types</option>
            <option value="Economy" <?= $seatType == 'Economy' ? 'selected' : '' ?>>Economy</option>
            <option value="Business" <?= $seatType == 'Business' ? 'selected' : '' ?>>Business</option>
        </select>
        <button type="submit" class="search-button">Search Flight</button>
    </form>
</div>

<div class="flights-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <a href="checkoutflights.php?flightRouteID=<?= urlencode($row['flightRouteID']) ?>" class="flight-link">
                <div class="flight-card">
                    <h3><?= htmlspecialchars($row['airlineProvide']) ?></h3>
                    <p><strong>Departure:</strong> <?= htmlspecialchars($row['routeDeparturePoint']) ?></p>
                    <p><strong>Arrival:</strong> <?= htmlspecialchars($row['routeArrivalPoint']) ?></p>
                    <p><strong>Flight Duration:</strong> <?= htmlspecialchars($row['flightDuration']) ?></p>
                    <p><strong>Seat Type:</strong> <?= htmlspecialchars($row['seatType']) ?></p>
                    <p><strong>Seat Price:</strong> RM <?= number_format($row['seatPrice'], 2) ?></p>
                    <p><strong>Managing Agency:</strong> <?= htmlspecialchars($row['managingAgency']) ?></p>
                </div>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-results">No flights found.</p>
    <?php endif; ?>
</div>

</body>
</html>

