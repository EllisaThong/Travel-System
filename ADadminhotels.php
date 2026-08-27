<?php
include("dbconnect.php");

// Handle search
$searchKeyword = "";
if (isset($_GET['search'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM hotels 
            WHERE hotelName LIKE '%$searchKeyword%' 
            OR hotelDescription LIKE '%$searchKeyword%' 
            OR hotelID LIKE '%$searchKeyword%'";
} else {
    $sql = "SELECT * FROM hotels";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Hotel Management</title>
    <link rel="stylesheet" href="ADadminhotels.css?v=2.5">
</head>
<body>
    <div class="hotel-container">
        <h1 class="hotel-title">Admin Hotel Management</h1>

        <!-- Search Form -->
        <form method="GET" class="search-box">
            <input type="text" name="search" class="search-input" placeholder="Search by package name..." value="<?php echo htmlspecialchars($searchKeyword); ?>">
            <button type="submit" class="search-button">Search</button>
        </form>

        <!-- Add Hotel Button -->
        <div class="top-bar">
            <a href="ADaddhotels.php" class="add-button">Add Hotel</a>
        </div>
        
        <!-- Hotel Cards -->
        <div class="hotel-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="hotel-card">';

                    $imagePath = htmlspecialchars($row["hotelImage"]);

                    if (!empty($row["hotelImage"]) && file_exists($imagePath)) {
                        echo "<img src='$imagePath' class='hotel-image' alt='Hotel Image'>";
                    } else {
                        echo "<img src='placeholder.jpg' class='hotel-image' alt='No Image Available'>";
                    }

                    echo "<div class='hotel-info'>";
                    echo "<h2 class='hotel-name'>" . htmlspecialchars($row["hotelName"]) . "</h2>";
                    echo "<p class='hotel-description'>" . htmlspecialchars($row["hotelDescription"]) . "</p>";
                    echo "<p class='hotel-room'><strong>Room Type:</strong> " . htmlspecialchars($row["hotelRoomTypes"]) . "</p>";
                    echo "<p class='hotel-price'><strong>Price per Night:</strong> RM" . htmlspecialchars($row["pricePerNight"]) . "</p>";
                    echo "<p class='hotel-phone'><strong>Phone:</strong> " . htmlspecialchars($row["hotelPhone"]) . "</p>";
                    echo "<p class='hotel-email'><strong>Email:</strong> " . htmlspecialchars($row["hotelEmail"]) . "</p>";
                    echo "<p class='hotel-address'><strong>Address:</strong> " . htmlspecialchars($row["hotelAddress"]) . "</p>";

                    // Edit/Delete Buttons
                    echo "<div class='admin-buttons'>";
                    echo "<a href='ADedithotels.php?id=" . $row["hotelID"] . "' class='admin-btn edit-btn'>Edit</a>";
                    echo "<a href='deletehotels.php?id=" . $row["hotelID"] . "' class='admin-btn delete-btn' 
                    onclick=\"return confirm('Are you sure you want to delete this hotel?');\">Delete</a>";
                    echo "</div>";

                    echo "</div>"; // .hotel-info
                    echo "</div>"; // .hotel-card
                }
            } else {
                echo "<p class='no-result'>No hotels found for '<strong>" . htmlspecialchars($searchKeyword) . "</strong>'.</p>";
            }
            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>
