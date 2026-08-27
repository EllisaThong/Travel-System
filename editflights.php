<?php
include("dbconnect.php");

if (!isset($_GET['id'])) {
    echo "Invalid flight ID.";
    exit;
}

$flightRouteID = $_GET['id'];

// Step 1: Fetch existing data
$query = "SELECT * FROM flight_routes WHERE flightRouteID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $flightRouteID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Flight not found.";
    exit;
}

$flight = $result->fetch_assoc();

// Step 2: Handle update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $airline = $_POST['airlineProvide'];
    $departure = $_POST['routeDeparturePoint'];
    $arrival = $_POST['routeArrivalPoint'];
    $duration = $_POST['flightDuration'];
    $seatType = $_POST['seatType'];
    $price = $_POST['seatPrice'];
    $agency = $_POST['managingAgency'];

// Step 2.5: Check for duplicate (excluding current flightRouteID)
$checkDuplicate = "SELECT * FROM flight_routes 
                   WHERE airlineProvide = ? 
                   AND routeDeparturePoint = ?
                   AND routeArrivalPoint = ?
                   AND flightDuration = ?
                   AND seatType = ?
                   AND seatPrice = ?
                   AND managingAgency = ?
                   AND flightRouteID != ?";

$stmtDup = $conn->prepare($checkDuplicate);
$stmtDup->bind_param("sssssdss", $airline, $departure, $arrival, $duration, $seatType, $price, $agency, $flightRouteID);
$stmtDup->execute();
$resultDup = $stmtDup->get_result();

if ($resultDup->num_rows > 0) {
    echo "<script>alert('Flight already exists. Please try again.'); window.history.back();</script>";
    exit;
}


    // Step 3: Update DB (without image)
    $update = "UPDATE flight_routes SET airlineProvide=?, routeDeparturePoint=?, routeArrivalPoint=?, flightDuration=?, seatType=?, seatPrice=?, managingAgency=? WHERE flightRouteID=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssssdss", $airline, $departure, $arrival, $duration, $seatType, $price, $agency, $flightRouteID);

    if ($stmt->execute()) {
        echo "<script>alert('Flight updated successfully!'); window.location.href='adminflights.php';</script>";
    } else {
        echo "Error updating: " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Flight</title>
    <link rel="stylesheet" href="editflights.css">
</head>
<body>
    <?php include("header.php") ?>
    <h1 class="page-title">Edit Flight</h1>
    <div class="edit-flight-form">
        <form action="" method="POST">
            <div class="form-group">
                <label for="airlineProvide">Flight Name</label>
                <input type="text" name="airlineProvide" id="airlineProvide" value="<?= htmlspecialchars($flight['airlineProvide']) ?>" required>
            </div>

            <div class="form-group">
                <label for="routeDeparturePoint">Departure Destination</label>
                <input type="text" name="routeDeparturePoint" id="routeDeparturePoint" value="<?= htmlspecialchars($flight['routeDeparturePoint']) ?>" required>
            </div>

            <div class="form-group">
                <label for="routeArrivalPoint">Arrival Destination</label>
                <input type="text" name="routeArrivalPoint" id="routeArrivalPoint" value="<?= htmlspecialchars($flight['routeArrivalPoint']) ?>" required>
            </div>

            <div class="form-group">
                <label for="flightDuration">Duration</label>
                <input type="number" name="flightDuration" id="flightDuration" value="<?= htmlspecialchars($flight['flightDuration']) ?>" required>
            </div>

            <div class="form-group">
                <label for="seatType">Seat Type</label>
                <select name="seatType" id="seatType" required>
                    <option value="">-- Select --</option>
                    <option value="Economy" <?= $flight['seatType'] == 'Economy' ? 'selected' : '' ?>>Economy</option>
                    <option value="Business" <?= $flight['seatType'] == 'Business' ? 'selected' : '' ?>>Business</option>
                    <option value="First Class" <?= $flight['seatType'] == 'First Class' ? 'selected' : '' ?>>First Class</option>
                </select>
            </div>

            <div class="form-group">
                <label for="seatPrice">Price (RM)</label>
                <input type="number" name="seatPrice" id="seatPrice" step="0.01" value="<?= htmlspecialchars($flight['seatPrice']) ?>" required>
            </div>

            <div class="form-group">
                <label for="managingAgency">Managing Agency</label>
                <input type="number" name="managingAgency" id="managingAgency" value="<?= htmlspecialchars($flight['managingAgency']) ?>" required>
            </div>

            <div class="form-group">
                <button type="submit" name="updateFlight">Confirm</button>
            </div>
        </form>
    </div>

</body>
</html>

