<?php
include("dbconnect.php");
session_start();

if (!isset($_GET['flightRouteID'])) {
    die("No flight selected.");
}

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$flightRouteID = intval($_GET['flightRouteID']);

$sql = "SELECT airlineProvide, routeDeparturePoint, routeArrivalPoint, flightDuration, seatType, seatPrice, managingAgency FROM flight_routes WHERE flightRouteID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $flightRouteID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Flight not found.");
}

$departureDate = $returnDate = "";
$departError = $returnError = "";
$numberOfPax = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departureDate = $_POST['departureDate'];
    $returnDate = $_POST['returnDate'];
    $numberOfPax = intval($_POST['numberOfPax']);
    $userID = $_SESSION['userID'];

    $today = date("Y-m-d");

    if ($departureDate <= $today) {
        $departError = "Departure date must be in the future.";
    }

    if (!empty($departureDate) && !empty($returnDate)) {
        if ($returnDate <= $departureDate) {
            $returnError = "Return date must be after departure date.";
        }
    }

    if (empty($departError) && empty($returnError)) {
        $bookingDate = date("Y-m-d");
        $bookingTime = date("H:i:s");
        $amountDue = $data['seatPrice'] * $numberOfPax;

        $insert = $conn->prepare("
            INSERT INTO bookings 
            (userID, flightRouteID, bookingDate, bookingTime, numberOfPax, departureDate, returnDate, amountDue)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $insert->bind_param(
            "iississd",
            $userID,
            $flightRouteID,
            $bookingDate,
            $bookingTime,
            $numberOfPax,
            $departureDate,
            $returnDate,
            $amountDue
        );

        if ($insert->execute()) {
            $bookingID = $insert->insert_id;
            header("Location: paymentsflights.php?bookingID=$bookingID");
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
    <title>Flight Checkout</title>
    <link rel="stylesheet" href="checkout.css">
    <script>
        function autoFillReturn() {
            const departInput = document.getElementById("departureDate");
            const returnInput = document.getElementById("returnDate");

            departInput.addEventListener('change', function () {
                if (departInput.value) {
                    const depart = new Date(departInput.value);
                    depart.setDate(depart.getDate() + 1);
                    returnInput.valueAsDate = depart;
                }
            });
        }
        window.onload = autoFillReturn;
    </script>
</head>
<body>
<?php include("header.php"); ?>
<div class="checkout-container">
    <h1>Flight Checkout</h1>

    <div class="package-summary">
        <p><strong>Airline:</strong> <?= htmlspecialchars($data['airlineProvide']) ?></p>
        <p><strong>Departure:</strong> <?= htmlspecialchars($data['routeDeparturePoint']) ?></p>
        <p><strong>Arrival:</strong> <?= htmlspecialchars($data['routeArrivalPoint']) ?></p>
        <p><strong>Flight Duration:</strong> <?= htmlspecialchars($data['flightDuration']) ?> hours</p>
        <p><strong>Seat Type:</strong> <?= htmlspecialchars($data['seatType']) ?></p>
        <p><strong>Seat Price:</strong> RM <?= number_format($data['seatPrice'], 2) ?></p>
        <p><strong>Managing Agency:</strong> <?= htmlspecialchars($data['managingAgency']) ?></p>
    </div>

    <form method="POST">
        <label for="departureDate"><strong>Departure Date:</strong></label>
        <input type="date" name="departureDate" id="departureDate" value="<?= htmlspecialchars($departureDate) ?>" required>
        <small class="error-message"><?= $departError ?></small>

        <label for="returnDate"><strong>Return Date:</strong></label>
        <input type="date" name="returnDate" id="returnDate" value="<?= htmlspecialchars($returnDate) ?>" required>
        <small class="error-message"><?= $returnError ?></small>

        <label for="numberOfPax"><strong>Number of Pax:</strong></label>
        <input type="number" name="numberOfPax" min="1" value="<?= htmlspecialchars($numberOfPax) ?>" required>

        <button type="submit" class="checkout-button">Proceed to Payment</button>
    </form>
</div>
</body>
</html>
