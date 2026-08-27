<?php
include("dbconnect.php");

// Step 1: Get hotelID from URL
if (isset($_GET['id'])) {
    $hotelID = $_GET['id'];

    // Step 2: Fetch hotel data
    $sql = "SELECT * FROM hotels WHERE hotelID = '$hotelID'";
    $result = mysqli_query($conn, $sql);
    $hotel = mysqli_fetch_assoc($result);

    if (!$hotel) {
        echo "Hotel not found.";
        exit();
    }
} else {
    header("Location: adminhotels.php");
    exit();
}

// Step 3: Handle form submission
if (isset($_POST['update_hotel'])) {
    $hotelName = $_POST['hotelName'];
    $hotelDescription = $_POST['hotelDescription'];
    $hotelRoomTypes = $_POST['hotelRoomTypes'];
    $pricePerNight = $_POST['pricePerNight'];
    $hotelPhone = $_POST['hotelPhone'];
    $hotelEmail = $_POST['hotelEmail'];
    $hotelAddress = $_POST['hotelAddress'];

    $imageName = $_FILES['hotelImage']['name'];
    $imageTmp = $_FILES['hotelImage']['tmp_name'];

    // Check for duplicates (excluding current hotelID)
    $checkDuplicate = "SELECT * FROM hotels WHERE hotelName = '$hotelName' AND hotelID != '$hotelID'";
    $duplicateResult = mysqli_query($conn, $checkDuplicate);

    if (mysqli_num_rows($duplicateResult) > 0) {
        echo "<script>alert('Hotel already exists. Please try again.'); window.history.back();</script>";
        exit();
    }


    if (!empty($imageName)) {
        $uploadDir = '../capstone-main/';
        $targetPath = $uploadDir . basename($imageName);

        if (move_uploaded_file($imageTmp, $targetPath)) {
            $update = "UPDATE hotels 
                    SET hotelName='$hotelName', 
                        hotelDescription='$hotelDescription',
                        hotelRoomTypes='$hotelRoomTypes',
                        pricePerNight='$pricePerNight',
                        hotelPhone='$hotelPhone',
                        hotelEmail='$hotelEmail',
                        hotelAddress='$hotelAddress', 
                        hotelImage='$imageName'
                    WHERE hotelID='$hotelID'";
        } else {
            echo "Failed to upload image.";
            exit();
        }
    
        } else {
        $update = "UPDATE hotels 
                    SET hotelName='$hotelName', 
                        hotelDescription='$hotelDescription',
                        hotelRoomTypes='$hotelRoomTypes',
                        pricePerNight='$pricePerNight',
                        hotelPhone='$hotelPhone',
                        hotelEmail='$hotelEmail',
                        hotelAddress='$hotelAddress'
                    WHERE hotelID='$hotelID'";
        }

        if (mysqli_query($conn, $update)) {
            echo "<script>alert('Hotel updated successfully'); window.location.href='adminhotels.php';</script>";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Hotel Details</title>
    <link rel="stylesheet" href="edithotels.css">
</head>
<body>
    <?php include("header.php") ?>
    <div class="main-content">
    <div class="form-container">
        <h2>Edit Hotel</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Hotel Name:</label>
            <input type="text" name="hotelName" value="<?= htmlspecialchars($hotel['hotelName']) ?>" required>

            <label>Description:</label>
            <textarea name="hotelDescription" required><?= htmlspecialchars($hotel['hotelDescription']) ?></textarea>

            <label>Room Types:</label>
            <input type="text" name="hotelRoomTypes" value="<?= htmlspecialchars($hotel['hotelRoomTypes']) ?>" required>

            <label>Price Per Night (RM):</label>
            <input type="number" name="pricePerNight" value="<?= htmlspecialchars($hotel['pricePerNight']) ?>" required>

            <label>Phone:</label>
            <input type="text" name="hotelPhone" value="<?= htmlspecialchars($hotel['hotelPhone']) ?>">

            <label>Email:</label>
            <input type="text" name="hotelEmail" value="<?= htmlspecialchars($hotel['hotelEmail']) ?>" required>

            <label>Address:</label>
            <input type="text" name="hotelAddress" value="<?= htmlspecialchars($hotel['hotelAddress']) ?>" required>

            <label>Current Image File:</label>
            <input type="text" value="<?= htmlspecialchars($hotel['hotelImage']) ?>" readonly style="margin-bottom: 15px;">

            <label>Upload New Image (optional):</label>
            <input type="file" name="hotelImage" accept="image/*">

            <button type="submit" name="update_hotel">Confirm</button>
        </form>
    </div>
</div>
</body>
</html>


