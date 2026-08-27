<?php
include("dbconnect.php");

// Handle search input
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// SQL query with optional search
$sql = "SELECT 
            p.packageID,
            p.packageName,
            p.packageDescription,
            p.packagePrice,
            p.packageDuration,
            h.hotelName,
            f.airlineProvide
        FROM packages p
        LEFT JOIN hotels h ON p.hotelID = h.hotelID
        LEFT JOIN flight_routes f ON p.flightRouteID = f.flightRouteID";

if (!empty($search)) {
    $sql .= " WHERE p.packageName LIKE '%$search%'";
}

$result = $conn->query($sql);

if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin View Packages</title>
    <link rel="stylesheet" href="adminpackages.css"> <!-- Reuse same design -->
</head>
<body>
<?php include("header.php") ?>
<h1>Admin: Manage Travel Packages</h1>
<div class="search-form">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by package name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<div class="button-container">
    <a href="addpackages.php" class="add-button">Add New Package</a>
</div>

<div class="package-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="package-card">';
            echo '<h2>' . htmlspecialchars($row['packageName']) . '</h2>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($row['packageDescription']) . '</p>';
            echo '<p><strong>Price:</strong> RM ' . number_format($row['packagePrice'], 2) . '</p>';
            echo '<p><strong>Duration:</strong> ' . htmlspecialchars($row['packageDuration']) . '</p>';

            // Link to adminhotels.php
            if (!empty($row['hotelName'])) {
                $hotelName = htmlspecialchars($row['hotelName']);
                echo '<p><strong>Hotel:</strong> <a href="adminhotels.php?search=' . urlencode($hotelName) . '">' . $hotelName . '</a></p>';
            } else {
                echo '<p><strong>Hotel:</strong> N/A</p>';
            }

            // Link to adminflights.php
            if (!empty($row['airlineProvide'])) {
                $flightName = htmlspecialchars($row['airlineProvide']);
                echo '<p><strong>Flight:</strong> <a href="adminflights.php?search=' . urlencode($flightName) . '">' . $flightName . '</a></p>';
            } else {
                echo '<p><strong>Flight:</strong> N/A</p>';
            }

            echo '<div class="button-actions">';
            echo '<a href="editpackages.php?id=' . $row['packageID'] . '" class="btn-edit">Edit</a>';
            echo '<a href="deletepackages.php?id=' . $row['packageID'] . '" class="btn-delete">Delete</a>';
            echo '</div>';

            echo '</div>';
        }
    } else {
        echo '<p>No packages found.</p>';
    }

    $conn->close();
    ?>
</div>

</body>
</html>
