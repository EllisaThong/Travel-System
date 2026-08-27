<?php
include("dbconnect.php");

$agencies = mysqli_query($conn, "SELECT agencyID, agencyName FROM agency");
$destinations = mysqli_query($conn, "SELECT destinationID, destinationName FROM destinations");
$hotels = mysqli_query($conn, "SELECT hotelID, hotelName FROM hotels");
$flight_routes = mysqli_query($conn, "SELECT flightRouteID, airlineProvide, routeDeparturePoint, routeArrivalPoint FROM flight_routes");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $packageName = $_POST['packageName'];
    $packageDescription = $_POST['packageDescription'];
    $packagePrice = $_POST['packagePrice'];
    $packageDuration = $_POST['packageDuration'];
    $agencyID = $_POST['agencyID'];
    $destinationID = $_POST['destinationID'];
    $hotelID = $_POST['hotelID'];
    $flightRouteID = $_POST['flightRouteID'];

    // Check if an identical package already exists
    $checkQuery = "SELECT * FROM packages WHERE 
        packageName = ? AND 
        agencyID = ? AND 
        destinationID = ? AND 
        hotelID = ? AND 
        flightRouteID = ? AND 
        packageDescription = ? AND 
        packagePrice = ? AND 
        packageDuration = ?";

    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("siiiisds", $packageName, $agencyID, $destinationID, $hotelID, $flightRouteID, $packageDescription, $packagePrice, $packageDuration);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Package already registered. Package was not added.'); window.location.href='adminpackages.php';</script>";
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO packages (agencyID, destinationID, hotelID, flightRouteID, packageName, packageDescription, packagePrice, packageDuration) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiissds", $agencyID, $destinationID, $hotelID, $flightRouteID, $packageName, $packageDescription, $packagePrice, $packageDuration);

    if ($stmt->execute()) {
        echo "<script>alert('Package added successfully!'); window.location.href='adminpackages.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Package</title>
    <link rel="stylesheet" href="addpackages.css">
</head>
<body>
    <?php include("header.php") ?>
    <h2 class="page-title">Add New Package</h2>
    <form method="POST" class="form-container">

        <label>Package Name
            <input type="text" name="packageName" required>
        </label><br>

        <label>Description
            <textarea name="packageDescription" required></textarea>
        </label><br>

        <label>Price (RM)
            <input type="number" name="packagePrice" step="0.01" required>
        </label><br>

        <label>Duration (Day)
            <input type="text" name="packageDuration" required>
        </label><br>

        <label for="agencyID">Agency</label>
        <select name="agencyID" required>
            <option value="">Select agency</option>
            <?php while ($agency = mysqli_fetch_assoc($agencies)) { ?>
                <option value="<?= $agency['agencyID'] ?>"><?= htmlspecialchars($agency['agencyName']) ?></option>
            <?php } ?>
        </select><br>

        <label for="destinationID">Destination</label>
        <select name="destinationID" required>
            <option value="">Select destination</option>
            <?php while ($dest = mysqli_fetch_assoc($destinations)) { ?>
                <option value="<?= $dest['destinationID'] ?>"><?= htmlspecialchars($dest['destinationName']) ?></option>
            <?php } ?>
        </select><br>

        <label for="hotelID">Hotel Name</label>
        <select name="hotelID" required>
            <option value="">Select hotel</option>
            <?php while ($hotel = mysqli_fetch_assoc($hotels)) { ?>
                <option value="<?= $hotel['hotelID'] ?>"><?= htmlspecialchars($hotel['hotelName']) ?></option>
            <?php } ?>
        </select><br>

        <label for="flightRouteID">Flight Route</label>
        <select name="flightRouteID" required>
            <option value="">Select flight</option>
            <?php while ($route = mysqli_fetch_assoc($flight_routes)) { ?>
                <option value="<?= $route['flightRouteID'] ?>">
                    <?= htmlspecialchars($route['airlineProvide'] . " (" . $route['routeDeparturePoint'] . " → " . $route['routeArrivalPoint'] . ")") ?>
                </option>
            <?php } ?>
        </select><br>

        <button type="submit" class="select-button">Add Package</button>
    </form>
</body>
</html>

