<?php
include("dbconnect.php");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_GET['packageID'])) {
    die("No package selected.");
}

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$packageID = intval($_GET['packageID']);

$sql = "
    SELECT 
        p.packageName,
        p.packageDescription,
        p.packagePrice,
        p.packageDuration,
        h.hotelName,
        f.airlineProvide
    FROM packages p
    LEFT JOIN hotels h ON p.hotelID = h.hotelID
    LEFT JOIN flight_routes f ON p.flightRouteID = f.flightRouteID
    WHERE p.packageID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $packageID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Package not found.");
}

$departureDate = $returnDate = "";
$departError = $returnError = "";
$numberOfPax = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $departureDate = $_POST['departureDate'];
    $returnDate = $_POST['returnDate'];
    $numberOfPax = isset($_POST['numberOfPax']) ? intval($_POST['numberOfPax']) : 1;
    $userID = $_SESSION['userID'];

    $today = date("Y-m-d");

    if ($departureDate <= $today) {
        $departError = "Departure date must be in the future.";
    }

    if (!empty($departureDate)) {
        $maxReturnDate = date('Y-m-d', strtotime("$departureDate +{$data['packageDuration']} days"));
    } else {
        $maxReturnDate = null;
    }

    if (!empty($returnDate) && !empty($departureDate)) {
        if ($returnDate <= $departureDate) {
            $returnError = "Return date must be after departure date.";
        } elseif ($maxReturnDate && $returnDate > $maxReturnDate) {
            $returnError = "Return date cannot exceed the package duration of {$data['packageDuration']} days.";
        }
    }

    if (empty($departError) && empty($returnError)) {
        $bookingDate = date("Y-m-d");
        $bookingTime = date("H:i:s");
        $amountDue = $data['packagePrice'] * $numberOfPax;
        $packageName = $data['packageName'];
        $packageHotelName = $data['hotelName'] ?: 'N/A';
        $packageFlightRoute = $data['airlineProvide'] ?: 'N/A';

        $insert = $conn->prepare("INSERT INTO bookings 
            (userID, packageID, packageName, packageHotelName, packageFlightRoute, bookingDate, bookingTime, numberOfPax, departureDate, returnDate, amountDue)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $insert->bind_param(
            "iissssisssd",
            $userID,
            $packageID,
            $packageName,
            $packageHotelName,
            $packageFlightRoute,
            $bookingDate,
            $bookingTime,
            $numberOfPax,
            $departureDate,
            $returnDate,
            $amountDue
        );

        if ($insert->execute()) {
            $bookingID = $insert->insert_id;
            header("Location: payments.php?bookingID=$bookingID");
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
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css">
    <script>
        function autoFillReturn() {
            const departInput = document.getElementById("departureDate");
            const returnInput = document.getElementById("returnDate");
            const duration = <?= $data['packageDuration'] ?>;

            departInput.addEventListener('change', function () {
                if (departInput.value) {
                    const departDate = new Date(departInput.value);
                    departDate.setDate(departDate.getDate() + duration);
                    returnInput.valueAsDate = departDate;
                }
            });

            returnInput.addEventListener('change', function () {
                if (returnInput.value && !departInput.value) {
                    const returnDate = new Date(returnInput.value);
                    returnDate.setDate(returnDate.getDate() - duration);
                    departInput.valueAsDate = returnDate;
                }
            });
        }
        window.onload = autoFillReturn;
    </script>
</head>
<body>
<?php include("header.php"); ?>
<div class="checkout-container">
    <h1>Checkout</h1>

    <div class="package-summary">
        <p><strong>Package Name:</strong> <?= htmlspecialchars($data['packageName']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($data['packageDescription']) ?></p>
        <p><strong>Price:</strong> RM <?= number_format($data['packagePrice'], 2) ?></p>
        <p><strong>Duration:</strong> <?= htmlspecialchars($data['packageDuration']) ?> days</p>
        <p><strong>Hotel:</strong> <?= htmlspecialchars($data['hotelName']) ?: 'N/A' ?></p>
        <p><strong>Flight:</strong> <?= htmlspecialchars($data['airlineProvide']) ?: 'N/A' ?></p>
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
