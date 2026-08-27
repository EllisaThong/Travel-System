<?php
include("dbconnect.php");

// Step 1: Validate ID
if (!isset($_GET['id'])) {
    echo "No booking ID provided.";
    exit();
}

$bookingID = $_GET['id'];

// Step 2: Fetch booking details
$stmt = $conn->prepare("SELECT * FROM bookings WHERE bookingID = ?");
$stmt->bind_param("i", $bookingID);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    echo "Booking not found.";
    exit();
}

// Step 3: Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numberOfPax = $_POST['numberOfPax'];
    $departureDate = $_POST['departureDate'];
    $returnDate = $_POST['returnDate'];

    $update = "UPDATE bookings SET numberOfPax = ?, departureDate = ?, returnDate = ? WHERE bookingID = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("issi", $numberOfPax, $departureDate, $returnDate, $bookingID);

    if ($stmt->execute()) {
        echo "<script>alert('Booking updated successfully!'); window.location.href='adminbookings.php';</script>";
    } else {
        echo "Error updating booking: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Flight</title>
    <link rel="stylesheet" href="ADadmineditCSS.css">
</head>
<body>
    <h2 class="page-title">Edit Bookings</h2>

<div class="form-container">

<form method="POST">
  <!-- Read-Only Fields -->
  <label>Booking ID</label>
  <input type="text" value="<?= $booking['bookingID'] ?>" disabled>

  <label>User ID</label>
  <input type="text" value="<?= $booking['userID'] ?>" disabled>

  <label>Booking Date</label>
  <input type="date" value="<?= $booking['bookingDate'] ?>" disabled>

  <label>Booking Time</label>
  <input type="time" value="<?= substr($booking['bookingTime'], 0, 8) ?>" disabled>

  <label>Package ID</label>
  <input type="text" value="<?= $booking['packageID'] ?>" disabled>

  <label>Hotel ID</label>
  <input type="text" value="<?= $booking['hotelID'] ?>" disabled>
  
  <label>Flight Route ID</label>
  <input type="text" value="<?= $booking['flightRouteID'] ?>" disabled>
  
  <label>Number of Pax</label>
  <input type="number" name="numberOfPax" value="<?= $booking['numberOfPax'] ?>">

  <label>Departure Date</label>
  <input type="date" name="departureDate" value="<?= $booking['departureDate'] ?>">

  <label>Return Date</label>
  <input type="date" name="returnDate" value="<?= $booking['returnDate'] ?>">

  <label>Amount Due</label>
  <input type="text" value="<?= $booking['amountDue'] ?>" disabled>
  
  <label>Invoice ID</label>
  <input type="text" value="<?= $booking['invoiceID'] ?>" disabled>
  
  <button type="submit" class="submit">Update Booking</button>
</form>
</div>
</body>

</html>
