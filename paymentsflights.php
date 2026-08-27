<?php
session_start();
require 'dbconnect.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION['userID'];
$bookingID = isset($_GET['bookingID']) ? intval($_GET['bookingID']) : 0;

if ($bookingID <= 0) die("Invalid booking ID.");

$bookingQuery = $conn->prepare("SELECT b.*, f.routeDeparturePoint, f.routeArrivalPoint FROM bookings b JOIN flight_routes f ON b.flightRouteID = f.flightRouteID WHERE b.bookingID = ? AND b.userID = ?");
$bookingQuery->bind_param("ii", $bookingID, $userID);
$bookingQuery->execute();
$bookingResult = $bookingQuery->get_result();
$booking = $bookingResult->fetch_assoc();

if (!$booking) die("Booking not found or access denied.");

$cardQuery = $conn->prepare("SELECT * FROM card_details WHERE userID = ?");
$cardQuery->bind_param("i", $userID);
$cardQuery->execute();
$cardResult = $cardQuery->get_result();

$paymentID = 0;
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cardID']) && $_POST['cardID'] !== '' && $_POST['cardID'] !== 'add_new_card') {
    $cardID = intval($_POST['cardID']);
    
    if ($cardID <= 0) {
        $error = "Please select a valid card.";
    } else {
        $verifyCard = $conn->prepare("SELECT * FROM card_details WHERE cardID = ? AND userID = ?");
        $verifyCard->bind_param("ii", $cardID, $userID);
        $verifyCard->execute();
        $card = $verifyCard->get_result()->fetch_assoc();

        if (!$card) {
            $error = "Invalid card selected or card does not belong to you.";
        } else {
            $amount = $booking['amountDue'];
            $paymentDate = date('Y-m-d');
            $paymentTime = date('H:i:s');

            $conn->begin_transaction();
            try {
                $insertPayment = $conn->prepare("INSERT INTO payments (bookingID, userID, paymentDate, paymentTime, cardID) VALUES (?, ?, ?, ?, ?)");
                $insertPayment->bind_param("iissi", $bookingID, $userID, $paymentDate, $paymentTime, $cardID);

                if (!$insertPayment->execute()) {
                    throw new Exception("Failed to insert payment: " . $insertPayment->error);
                }

                $paymentID = $insertPayment->insert_id;

                // Update booking with paymentID and status
                $updateBooking = $conn->prepare("UPDATE bookings SET status = 'paid', paymentID = ? WHERE bookingID = ?");
                $updateBooking->bind_param("ii", $paymentID, $bookingID);

                if (!$updateBooking->execute()) {
                    throw new Exception("Failed to update booking: " . $updateBooking->error);
                }

                $conn->commit();
                $success = true;
                
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Payment failed: " . $e->getMessage();
                error_log("Payment Error: " . $e->getMessage());
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = "Please select a card to proceed with payment.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment</title>
    <link rel="stylesheet" href="payments.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="payment-container" style="margin: 50px auto; max-width: 600px; background: #e9ecef; padding: 30px; border-radius: 12px;">
    <h1 style="margin-bottom: 20px;">Payment</h1>

    <div class="summary-item"><strong>Booking ID:</strong> <?= $bookingID ?></div>
    <div class="summary-item"><strong>Departure:</strong> <?= htmlspecialchars($booking['routeDeparturePoint']) ?></div>
    <div class="summary-item"><strong>Arrival:</strong> <?= htmlspecialchars($booking['routeArrivalPoint']) ?></div>
    <div class="summary-item"><strong>Amount Due:</strong> RM <?= number_format($booking['amountDue'], 2) ?></div>

    <?php if ($error): ?>
        <div class="alert error" style="color: red; margin: 10px 0; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 10px; margin: 10px 0; border-radius: 4px;">
            <strong>Debug Info:</strong><br>
            POST Data: <?= htmlspecialchars(print_r($_POST, true)) ?><br>
            Card ID: <?= isset($_POST['cardID']) ? htmlspecialchars($_POST['cardID']) : 'Not set' ?><br>
            User ID: <?= $userID ?><br>
            Booking ID: <?= $bookingID ?><br>
            Amount Due: RM <?= number_format($booking['amountDue'], 2) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="cardID" style="margin-top: 20px;">Select Payment Method:</label>
        <div class="card-selector-wrapper">
            <select name="cardID" id="cardSelector" class="form-control" required>
                <option value="">-- Select a Card --</option>
                <?php $cardResult->data_seek(0); while ($row = $cardResult->fetch_assoc()): ?>
                    <option value="<?= $row['cardID'] ?>">
                        **** **** **** <?= substr($row['cardNumber'], -4) ?> (<?= htmlspecialchars($row['cardName']) ?>)
                    </option>
                <?php endwhile; ?>
                <option value="add_new_card" class="add-card-option">
                    <i class="fa-solid fa-plus"></i> Add New Card
                </option>
            </select>
        </div>
        <br>
        <button type="submit" class="btn-primary" style="margin-top: 10px;">Pay Now</button>
    </form>
</div>

<div id="paymentModal" class="paymentModal">
    <div class="pmContent">
        <span class="closeModal">&times;</span>
        <form method="POST" action="ADaddcard.php" autocomplete="off">
            <h2 style="margin: 0px 4px 8px 0px">Add Card <i class="fa-regular fa-credit-card"></i></h2>
            <div class="formGroup">
                <label for="cardName">Card Name:</label>
                <div class="inputWrapper">
                    <input type="text" id="cardName" name="cardName" maxlength="20" placeholder="Card Name" required />
                    <span class="focusBar"></span>
                </div>
            </div>

            <div class="formGroup">
                <label for="cardNumber">Card Number:</label>
                <div class="inputWrapper">
                    <input type="text" id="cardNumber" name="cardNumber" inputmode="numeric" maxlength="16" placeholder="1234567812345678" required />
                    <span class="focusBar"></span>
                </div>
            </div>

            <div class="formGroup">
                <label for="cardExDate">Card Expiry Date (MM/YY):</label>
                <div class="inputWrapper">
                    <input type="text" id="cardExDate" name="cardExDate" inputmode="numeric" maxlength="5" placeholder="MM/YY" required />
                    <span class="focusBar"></span>
                </div>
            </div>

            <div class="addcardButton">
                <button type="button" id="addCancel">Cancel</button>
                <button type="submit" id="addCard">Add Card</button>
            </div>
        </form>
    </div>
</div>

<?php if ($success): ?>
<script>
    alert("Payment successful!");
    window.location.href = "invoice.php?paymentID=<?= $paymentID ?>";
</script>
<?php endif; ?>

<script>
    const modal = document.getElementById("paymentModal");
    const cardSelector = document.getElementById("cardSelector");
    const closeBtn = document.querySelector(".closeModal");
    const cancelBtn = document.getElementById("addCancel");
    const payForm = document.querySelector('form[method="POST"]');

    cardSelector.addEventListener('change', function() {
        if (this.value === 'add_new_card') {
            this.value = '';
            
            modal.style.display = "block";
            modal.classList.add("showModal");
        }
    });

    function closeModal() {
        modal.classList.remove("showModal");
        modal.style.display = "none";
        cardSelector.value = '';
    }

    closeBtn.onclick = closeModal;
    cancelBtn.onclick = closeModal;

    payForm.addEventListener('submit', function(e) {
        if (cardSelector.value === 'add_new_card' || cardSelector.value === '') {
            e.preventDefault();
            if (cardSelector.value === 'add_new_card') {
                alert('Please select a valid card or add a new card first.');
                cardSelector.value = '';
            } else {
                alert('Please select a card to proceed with payment.');
            }
        }
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
</script>

</body>
</html>