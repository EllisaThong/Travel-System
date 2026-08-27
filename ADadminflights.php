<?php
include("dbconnect.php");


// Get filter values safely
$departure = $_GET['departure'] ?? '';
$arrival = $_GET['arrival'] ?? '';
$searchFlight = $_GET['search'] ?? ''; // Search by airlineProvide

// Base query
$sql = "SELECT * FROM flight_routes WHERE 1=1";
$params = [];
$types = "";

// Append filters if set
if (!empty($searchFlight)) {
    $sql .= " AND airlineProvide LIKE ?";
    $params[] = "%$searchFlight%";
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

// Prepare and bind
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
    <title>Admin Flights</title>
    <link rel="stylesheet" href="ADadminflights.css?v=1.3">
</head>
<body>
    <h1 class="page-title">Manage Flights</h1>

    <div class="filter-form">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search Airline (e.g., AirAsia)" value="<?= htmlspecialchars($searchFlight) ?>">
            <input type="text" name="departure" placeholder="From (e.g., KLIA)" value="<?= htmlspecialchars($departure) ?>">
            <input type="text" name="arrival" placeholder="To (e.g., Penang)" value="<?= htmlspecialchars($arrival) ?>">
            <button type="submit">Search Flight</button>
        </form>
    </div>

    <div class="top-bar">
        <a href="ADaddflights.php" class="add-flight-btn">Add Flight</a>
    </div>

    <div class="flights-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="flight-card">
                    <h3><?= htmlspecialchars($row['airlineProvide']) ?></h3>
                    <p><strong>Departure:</strong> <?= htmlspecialchars($row['routeDeparturePoint']) ?></p>
                    <p><strong>Arrival:</strong> <?= htmlspecialchars($row['routeArrivalPoint']) ?></p>
                    <p><strong>Flight Duration:</strong> <?= htmlspecialchars($row['flightDuration']) ?></p>
                    <p><strong>Seat Type:</strong> <?= htmlspecialchars($row['seatType']) ?></p>
                    <p><strong>Seat Price:</strong> RM <?= htmlspecialchars($row['seatPrice']) ?></p>
                    <p><strong>Managing Agency:</strong> <?= htmlspecialchars($row['managingAgency']) ?></p>

                    <div class="admin-buttons">
                    <a href="ADeditflights.php?id=<?= $row['flightRouteID'] ?>" class="edit-btn">Edit</a>
                    <a href="deleteflights.php?id=<?= $row['flightRouteID'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this flight?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-results">No flights found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
