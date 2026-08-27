<?php
include("dbconnect.php");
session_start();

if (!isset($_GET['hotelID'])) {
    die("No hotel selected.");
}

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$hotelID = intval($_GET['hotelID']);

$sql = "SELECT hotelName, hotelDescription, hotelRoomTypes, pricePerNight, hotelAddress FROM hotels WHERE hotelID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hotelID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Hotel not found.");
}

$roomType = strtolower($data['hotelRoomTypes']);
$maxPax = 1; 

if (strpos($roomType, 'single') !== false) {
    $maxPax = 1;
} elseif (strpos($roomType, 'double') !== false || strpos($roomType, 'twin') !== false) {
    $maxPax = 2;
} elseif (strpos($roomType, 'deluxe') !== false) {
    $maxPax = 5;
} elseif (strpos($roomType, 'suite') !== false) {
    $maxPax = 10;
}

$checkInDate = $checkOutDate = "";
$checkInError = $checkOutError = $paxError = "";
$numberOfPax = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];
    $numberOfPax = intval($_POST['numberOfPax']);
    $userID = $_SESSION['userID'];

    $today = date("Y-m-d");

    if ($checkInDate <= $today) {
        $checkInError = "Check-in date must be in the future.";
    }

    if (!empty($checkInDate) && !empty($checkOutDate)) {
        if ($checkOutDate <= $checkInDate) {
            $checkOutError = "Check-out date must be after check-in date.";
        }
    }

    if ($numberOfPax > $maxPax) {
        $paxError = "Maximum {$maxPax} guest(s) allowed for {$data['hotelRoomTypes']} room.";
    }

    if (empty($checkInError) && empty($checkOutError) && empty($paxError)) {
        $bookingDate = date("Y-m-d");
        $bookingTime = date("H:i:s");
        $duration = (strtotime($checkOutDate) - strtotime($checkInDate)) / 86400;
        $amountDue = $data['pricePerNight'] * $duration;

        $insert = $conn->prepare("
            INSERT INTO bookings 
            (userID, hotelID, bookingDate, bookingTime, numberOfPax, departureDate, returnDate, amountDue)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $insert->bind_param(
            "iississd",
            $userID,
            $hotelID,
            $bookingDate,
            $bookingTime,
            $numberOfPax,
            $checkInDate,
            $checkOutDate,
            $amountDue
        );

        if ($insert->execute()) {
            $bookingID = $insert->insert_id;
            header("Location: paymentshotel.php?bookingID=$bookingID");
            exit;
        } else {
            echo "<p style='color:red;'>Booking failed: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hotel Checkout</title>
    <link rel="stylesheet" href="checkout.css">
    <script>
        function autoFillCheckout() {
            const checkInInput = document.getElementById("checkInDate");
            const checkOutInput = document.getElementById("checkOutDate");

            checkInInput.addEventListener('change', function () {
                if (checkInInput.value) {
                    const checkIn = new Date(checkInInput.value);
                    checkIn.setDate(checkIn.getDate() + 1); // auto-fill next day
                    checkOutInput.valueAsDate = checkIn;
                }
            });
        }
        window.onload = autoFillCheckout;
    </script>
</head>
<body>
<?php include("header.php"); ?>
<div class="checkout-container">
    <h1>Hotel Checkout</h1>

    <div class="package-summary">
        <p><strong>Hotel Name:</strong> <?= htmlspecialchars($data['hotelName']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($data['hotelDescription']) ?></p>
        <p><strong>Room Type:</strong> <?= htmlspecialchars($data['hotelRoomTypes']) ?></p>
        <p><strong>Price per Night:</strong> RM <?= number_format($data['pricePerNight'], 2) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($data['hotelAddress']) ?></p>
        <p><strong>Max Occupancy:</strong> <?= $maxPax ?> guest<?= $maxPax > 1 ? "s" : "" ?></p>
    </div>

    <form method="POST">
        <label for="checkInDate"><strong>Check-in Date:</strong></label>
        <input type="date" name="checkInDate" id="checkInDate" value="<?= htmlspecialchars($checkInDate) ?>" required>
        <small class="error-message"><?= $checkInError ?></small>

        <label for="checkOutDate"><strong>Check-out Date:</strong></label>
        <input type="date" name="checkOutDate" id="checkOutDate" value="<?= htmlspecialchars($checkOutDate) ?>" required>
        <small class="error-message"><?= $checkOutError ?></small>

        <label for="numberOfPax"><strong>Number of Pax:</strong></label>
        <input type="number" name="numberOfPax" min="1" max="<?= $maxPax ?>" value="<?= htmlspecialchars($numberOfPax) ?>" required>
        <small class="error-message"><?= $paxError ?></small>

        <button type="submit" class="checkout-button">Proceed to Payment</button>
    </form>
</div>
</body>
</html>
