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
    <link rel="stylesheet" href="ADadmineditCSS.css">
</head>
<body>
    <h1 class="page-title">Edit Flight</h1>
    <div class="form-container">
        <form action="" method="POST">
                <label>Airline</label>
                <input type="text" name="airlineProvide" id="airlineProvide" value="<?= htmlspecialchars($flight['airlineProvide']) ?>" required>


                <label>Departure</label>
                <input type="text" name="routeDeparturePoint" id="routeDeparturePoint" value="<?= htmlspecialchars($flight['routeDeparturePoint']) ?>" required>
 

                <label>Arrival</label>
                <input type="text" name="routeArrivalPoint" id="routeArrivalPoint" value="<?= htmlspecialchars($flight['routeArrivalPoint']) ?>" required>
                
                <label>Flight Duration</label>
                <input type="number" name="flightDuration" id="flightDuration" value="<?= htmlspecialchars($flight['flightDuration']) ?>" required>


                <label>Seat Type</label>
                <select name="seatType" id="seatType" required>
                    <option value="">-- Select --</option>
                    <option value="Economy" <?= $flight['seatType'] == 'Economy' ? 'selected' : '' ?>>Economy</option>
                    <option value="Business" <?= $flight['seatType'] == 'Business' ? 'selected' : '' ?>>Business</option>
                    <option value="First Class" <?= $flight['seatType'] == 'First Class' ? 'selected' : '' ?>>First Class</option>
                </select>


                <label>Price (RM)</label>
                <input type="number" name="seatPrice" id="seatPrice" step="0.01" value="<?= htmlspecialchars($flight['seatPrice']) ?>" required>

                <label>Managing Agency</label>
                <input type="number" name="managingAgency" id="managingAgency" value="<?= htmlspecialchars($flight['managingAgency']) ?>" required>

                <button type="submit" class="submit">Update Flight</button>
        </form>
    </div>

</body>
</html>

