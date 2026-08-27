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
    
    move_uploaded_file($imageTmp, $targetPath);

    $insertQuery = "INSERT INTO hotels (
        hotelName, hotelDescription, hotelRoomTypes, pricePerNight, hotelPhone, hotelEmail, hotelAddress, hotelImage
    ) VALUES (
        '$hotelName', '$hotelDescription', '$hotelRoomTypes', '$pricePerNight', '$hotelPhone', '$hotelEmail', '$hotelAddress', '$imageName'
    )";

    if (mysqli_query($conn, $insertQuery)) {
        echo "<script>alert('Hotel added successfully'); window.location.href='adminhotels.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Hotel</title>
    <link rel="stylesheet" href="ADadminaddCSS.css">
</head>
<body>
    <h2 class="page-title">Add New Hotel</h2>    
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <label for="hotelName">Hotel Name:</label>
            <input type="text" name="hotelName" required>

            <label for="hotelDescription">Description:</label>
            <textarea name="hotelDescription" required></textarea>

            <label for="hotelRoomTypes">Room Type:</label>
            <select name="hotelRoomTypes" id="hotelRoomTypes" required>
                    <option value="">-- Select --</option>
                    <option value="SingleRoom">SingleRoom</option>
                    <option value="DoubleRoom">DoubleRoom</option>
                    <option value="StandardRoom">StandardRoom</option>
                    <option value="DeluxeRoom">DeluxeRoom</option>
                    <option value="AccessbibleRoom">AccessbibleRoom</option>
                    <option value="PresidentialSuite">PresidentialSuite</option>
                </select>

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

            <button type="submit" class="submit">Add Hotel</button>
        </form>
    </div>
</body>
</html>

