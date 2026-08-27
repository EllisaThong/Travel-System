<?php
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotelName = $_POST['hotelName'];
    $hotelDescription = $_POST['hotelDescription'];
    $hotelRoomTypes = $_POST['hotelRoomTypes'];
    $pricePerNight = $_POST['pricePerNight'];
    $hotelPhone = $_POST['hotelPhone'];
    $hotelEmail = $_POST['hotelEmail'];
    $hotelAddress = $_POST['hotelAddress'];

    $imageName = $_FILES['hotelImage']['name'];
    $imageTmp = $_FILES['hotelImage']['tmp_name'];
    $uploadDir = '../capstone-main/';
    $targetPath = $uploadDir . basename($imageName);

    // Check if hotel name already exists
    $checkQuery = "SELECT * FROM hotels WHERE hotelName = '$hotelName'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Hotel already registered. Hotel was not added.'); window.location.href='adminhotels.php';</script>";
    } else {
        // Move uploaded image
        if (move_uploaded_file($imageTmp, $targetPath)) {
            $insertQuery = "INSERT INTO hotels (
                hotelName, hotelDescription, hotelRoomTypes, pricePerNight, hotelPhone, hotelEmail, hotelAddress, hotelImage
            ) VALUES (
                '$hotelName', '$hotelDescription', '$hotelRoomTypes', '$pricePerNight', '$hotelPhone', '$hotelEmail', '$hotelAddress', '$imageName'
            )";

            if (mysqli_query($conn, $insertQuery)) {
                echo "<script>alert('Hotel added successfully'); window.location.href='adminhotels.php';</script>";
            } else {
                echo "<script>alert('Database error. Hotel NOT added.'); window.location.href='adminhotels.php';</script>";
            }
        } else {
            echo "<script>alert('Image upload failed. Hotel NOT added.'); window.location.href='adminhotels.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Hotel</title>
    <link rel="stylesheet" href="addhotels.css">
</head>
<body>
    <?php include("header.php") ?>
    <div class="main-content">
    <div class="form-container">
        <h2>Add New Hotel</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="hotelName">Hotel Name:</label>
            <input type="text" name="hotelName" required>

            <label for="hotelDescription">Description:</label>
            <textarea name="hotelDescription" required></textarea>

            <label for="hotelRoomTypes">Room Type:</label>
            <input type="text" name="hotelRoomTypes" required>

            <label for="pricePerNight">Price Per Night (RM):</label>
            <input type="number" name="pricePerNight" step="0.01" required>

            <label for="hotelPhone">Phone:</label>
            <input type="text" name="hotelPhone">

            <label for="hotelEmail">Email:</label>
            <input type="text" name="hotelEmail" required>

            <label for="hotelAddress">Address:</label>
            <input type="text" name="hotelAddress" required>

            <label for="hotelImage">Hotel Image:</label>
            <input type="file" name="hotelImage" accept="image/*" required>

            <button type="submit">Add Hotel</button>
        </form>
    </div>
</div>
</body>
</html>

