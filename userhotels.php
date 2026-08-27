<?php
session_start();
include("dbconnect.php");

// Check if search keyword is set via GET
$searchKeyword = "";
if (isset($_GET['search'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM hotels WHERE hotelName LIKE '%$searchKeyword%' OR hotelDescription LIKE '%$searchKeyword%'";
} else {
    $sql = "SELECT * FROM hotels";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotel List</title>
    <link rel="stylesheet" href="userhotels.css">
</head>
<body>
<?php include("header.php") ?>
    <div class="hotel-container">
        <h1 class="hotel-title">Hotel List</h1>

        <!-- Search Form -->
        <form method="GET" class="search-box">
            <input type="text" name="search" class="search-input" placeholder="Enter keyword to search..." value="<?php echo htmlspecialchars($searchKeyword); ?>" />
            <button type="submit" class="search-button">Start Search</button>
        </form>

        <!-- Hotel Cards -->
        <div class="hotel-list">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $hotelID = $row['hotelID'];

                    echo '<a href="checkouthotel.php?hotelID=' . urlencode($hotelID) . '" class="hotel-link">';
                    echo '<div class="hotel-card">';

                    $imagePath = "../capstone-main/" . htmlspecialchars($row["hotelImage"]);
                    if (!empty($row["hotelImage"]) && file_exists($imagePath)) {
                        echo "<img src='$imagePath' class='hotel-image' alt='Hotel Image'>";
                    } else {
                    echo "<img src='placeholder.jpg' class='hotel-image' alt='No Image Available'>";
                    }

                    echo "<div class='hotel-info'>";
                    echo "<h2 class='hotel-name'>" . htmlspecialchars($row["hotelName"]) . "</h2>";
                    echo "<p class='hotel-description'>" . htmlspecialchars($row["hotelDescription"]) . "</p>";
                    echo "<p class='hotel-room'>Room Type: " . htmlspecialchars($row["hotelRoomTypes"]) . "</p>";
                    echo "<p class='hotel-price'><strong>Price per Night:</strong> RM" . htmlspecialchars($row["pricePerNight"]) . "</p>";
                    echo "<p class='hotel-phone'><strong>Phone:</strong> " . htmlspecialchars($row["hotelPhone"]) . "</p>";
                    echo "<p class='hotel-email'><strong>Email:</strong> " . htmlspecialchars($row["hotelEmail"]) . "</p>";
                    echo "<p class='hotel-address'><strong>Address:</strong> " . htmlspecialchars($row["hotelAddress"]) . "</p>";
                    echo "</div>"; // hotel-info
                    echo "</div>"; // hotel-card
                    echo "</a>";
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

