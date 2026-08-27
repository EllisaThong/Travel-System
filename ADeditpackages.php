<?php
include("dbconnect.php");

// Step 1: Validate ID
if (!isset($_GET['id'])) {
    echo "No package ID provided.";
    exit();
}

$packageID = $_GET['id'];
$success = "";

// Step 2: Fetch package details
$stmt = $conn->prepare("SELECT * FROM packages WHERE packageID = ?");
$stmt->bind_param("i", $packageID);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();

if (!$package) {
    echo "Package not found.";
    exit();
}

// Step 3: Fetch dropdown options
$hotels = $conn->query("SELECT hotelID, hotelName FROM hotels");
$flights = $conn->query("SELECT flightRouteID, airlineProvide FROM flight_routes");
$destinations = $conn->query("SELECT destinationID, destinationName FROM destinations");
$agencies = $conn->query("SELECT agencyID, agencyName FROM agency");

// Step 4: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['packageName'];
    $description = $_POST['packageDescription'];
    $price = $_POST['packagePrice'];
    $duration = $_POST['packageDuration'];
    $hotelID = $_POST['hotelID'];
    $flightRouteID = $_POST['flightRouteID'];
    $agencyID = $_POST['agencyID'];
    $destinationID = $_POST['destinationID'];

    $update = "UPDATE packages SET packageName=?, packageDescription=?, packagePrice=?, packageDuration=?, hotelID=?, flightRouteID=?, agencyID=?, destinationID=? WHERE packageID=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssdsiiiii", $name, $description, $price, $duration, $hotelID, $flightRouteID, $agencyID, $destinationID, $packageID);

    if ($stmt->execute()) {
        echo "<script>alert('Package updated successfully!'); window.location.href='adminpackages.php';</script>";
    } else {
        echo "Error updating package: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Package</title>
    <link rel="stylesheet" href="ADadmineditCSS.css">
</head>
<body>
<h2 class="page-title">Edit Package</h2>
<div class="form-container">
    <form method="POST">
        <label>Package Name</label>
        <input type="text" name="packageName" value="<?= htmlspecialchars($package['packageName']) ?>" required>

        <label>Description</label>
        <textarea name="packageDescription" required><?= htmlspecialchars($package['packageDescription']) ?></textarea>

        <label>Price (RM)</label>
        <input type="number" step="0.01" name="packagePrice" value="<?= $package['packagePrice'] ?>" required>

        <label>Duration (Day)</label>
        <input type="text" name="packageDuration" value="<?= htmlspecialchars($package['packageDuration']) ?>" required>

        <label>Hotel</label>
        <select name="hotelID" required>
            <?php while ($hotel = $hotels->fetch_assoc()): ?>
                <option value="<?= $hotel['hotelID'] ?>" <?= $hotel['hotelID'] == $package['hotelID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($hotel['hotelName']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Flight Route</label>
        <select name="flightRouteID" required>
            <?php while ($flight = $flights->fetch_assoc()): ?>
                <option value="<?= $flight['flightRouteID'] ?>" <?= $flight['flightRouteID'] == $package['flightRouteID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($flight['airlineProvide']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Agency</label>
        <select name="agencyID" required>
            <?php while ($agency = $agencies->fetch_assoc()): ?>
                <option value="<?= $agency['agencyID'] ?>" <?= $agency['agencyID'] == $package['agencyID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($agency['agencyName']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Destination</label>
        <select name="destinationID" required>
            <?php while ($destination = $destinations->fetch_assoc()): ?>
                <option value="<?= $destination['destinationID'] ?>" <?= $destination['destinationID'] == $package['destinationID'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($destination['destinationName']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" class="submit">Update Package</button>
    </form>
</div>
</body>
</html>

