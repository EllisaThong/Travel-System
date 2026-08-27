<?php
session_start();
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
    <title>View Packages</title>
    <link rel="stylesheet" href="userpackages.css">
</head>
<body>
<?php include("header.php") ?>
<h1 class="packageTitle">Available Travel Packages</h1>
<div class="search-form">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by package name..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<div class="package-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="checkoutpackages.php?packageID=' . urlencode($row['packageID']) . '" class="package-link">';
            echo '<div class="package-card">';
            echo '<h2>' . htmlspecialchars($row['packageName']) . '</h2>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($row['packageDescription']) . '</p>';
            echo '<p><strong>Price:</strong> RM ' . number_format($row['packagePrice'], 2) . '</p>';
            echo '<p><strong>Duration:</strong> ' . htmlspecialchars($row['packageDuration']) . ' days</p>';

            if (!empty($row['hotelName'])) {
                $hotelName = htmlspecialchars($row['hotelName']);
                echo '<p><strong>Hotel:</strong> <a href="userhotels.php?search=' . urlencode($hotelName) . '">' . $hotelName . '</a></p>';
            } else {
                echo '<p><strong>Hotel:</strong> N/A</p>';
            }

            if (!empty($row['airlineProvide'])) {
                $flightName = htmlspecialchars($row['airlineProvide']);
                echo '<p><strong>Flight:</strong> <a href="userflights.php?search=' . urlencode($flightName) . '">' . $flightName . '</a></p>';
            } else {
                echo '<p><strong>Flight:</strong> N/A</p>';
            }

            echo '</div>';
            echo '</a>';
        }

    } else {
        echo '<p>No packages found.</p>';
    }

    $conn->close();
    ?>
</div>

</body>
</html>

