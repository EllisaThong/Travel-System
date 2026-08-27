<?php
include("dbconnect.php");

// Check if 'id' parameter is provided
if (isset($_GET['id'])) {
    $hotelID = $_GET['id'];

    // Step 1: Get the current hotel image name
    $select = "SELECT hotelImage FROM hotels WHERE hotelID = '$hotelID'";
    $result = mysqli_query($conn, $select);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $imageName = $row['hotelImage'];

        // Step 2: Delete the image file if it exists
        if (!empty($imageName)) {
            $imagePath = "../capstone-main/" . $imageName;
            if (file_exists($imagePath)) {
                unlink($imagePath); // delete the file
            }
        }
    }

    // Step: Delete the hotel record directly (no image involved)
    $sql = "DELETE FROM hotels WHERE hotelID = '$hotelID'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Hotel deleted successfully'); window.location.href='adminhotels.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // Redirect if no ID is provided
    header("Location: adminhotels.php");
    exit();
}
?>
