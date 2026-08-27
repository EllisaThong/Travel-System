<?php
include("dbconnect.php");

function generateFlightRouteID($conn) {
    $query = "SELECT flightRouteID FROM flight_routes ORDER BY flightRouteID DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $lastID = $row['flightRouteID'];
        $number = intval(substr($lastID, 1)) + 1;
        return 'F' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        return 'F001';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flightRouteID = generateFlightRouteID($conn);
    $airline = $_POST['airlineProvide'];
    $departure = $_POST['routeDeparturePoint'];
    $arrival = $_POST['routeArrivalPoint'];
    $duration = $_POST['flightDuration'];
    $seatType = $_POST['seatType'];
    $price = $_POST['seatPrice'];
    $agency = $_POST['managingAgency'];

    $sql = "INSERT INTO flight_routes (flightRouteID, airlineProvide, routeDeparturePoint, routeArrivalPoint, flightDuration, seatType, seatPrice, managingAgency)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssds", $flightRouteID, $airline, $departure, $arrival, $duration, $seatType, $price, $agency);

    if ($stmt->execute()) {
        echo "<script>alert('Flight added successfully!'); window.location.href='admindashboard.php';</script>";
    } else {
        echo "Error inserting data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Flight Schedule</title>
    <link rel="stylesheet" href="ADadminaddCSS.css">
</head>
<body>
    <h1 class="page-title">Add New Flight</h1>
    <div class="form-container">
        <form action="addflights.php" method="POST">
            <div class="form-group">
                <label for="airlineProvide">Flight Name</label>
                <input type="text" name="airlineProvide" id="airlineProvide" required>
            </div>

            <div class="form-group">
                <label for="routeDeparturePoint">Departure Point</label>
                <input type="text" name="routeDeparturePoint" id="routeDeparturePoint" required>
            </div>

            <div class="form-group">
                <label for="routeArrivalPoint">Arrival Point</label>
                <input type="text" name="routeArrivalPoint" id="routeArrivalPoint" required>
            </div>

            <div class="form-group">
                <label for="flightDuration">Duration</label>
                <input type="text" name="flightDuration" id="flightDuration" required>
            </div>

            <div class="form-group">
                <label for="seatType">Seat Type</label>
                <select name="seatType" id="seatType" required>
                    <option value="">-- Select --</option>
                    <option value="Economy">Economy</option>
                    <option value="Business">Business</option>
                    <option value="First Class">First Class</option>
                </select>
            </div>

            <div class="form-group">
                <label for="seatPrice">Price (RM)</label>
                <input type="number" name="seatPrice" id="seatPrice" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="managingAgency">Managing Agency</label>
                <input type="text" name="managingAgency" id="managingAgency" required>
            </div>

                <button type="submit" class="submit">Add Flight</button>
            </div>
        </form>
    </div>

</body>
</html>

