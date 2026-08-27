<?php
session_start();
require 'dbconnect.php';

$debug = false;

if (!isset($_GET['paymentID'])) {
    if ($debug) echo "Debug: Payment ID is required.";
    else echo "Payment ID is required.";
    exit;
}

$paymentID = intval($_GET['paymentID']);
if ($debug) echo "Debug: Processing paymentID = $paymentID<br>";

if (isset($_POST['send_email'])) {
    session_start();
    if (!isset($_SESSION['userID'])) {
        echo "Please log in to send email.";
        exit;
    }
    
    $userEmailQuery = $conn->prepare("SELECT userEmail FROM users WHERE userID = ?");
    $userEmailQuery->bind_param("i", $_SESSION['userID']);
    $userEmailQuery->execute();
    $userEmail = $userEmailQuery->get_result()->fetch_assoc();
    
    if ($userEmail) {
        $to = $userEmail['userEmail']; 
        $subject = "Invoice #" . $_POST['invoice_id'] . " - The Malaysian Traveler";
        $message = "Dear " . $_POST['username'] . ",\n\n";
        $message .= "Thank you for your booking with The Malaysian Traveler.\n\n";
        $message .= "Invoice Details:\n";
        $message .= "Invoice ID: " . $_POST['invoice_id'] . "\n";
        $message .= "Booking Type: " . ucfirst($_POST['booking_type']) . "\n";
        $message .= "Total Paid: RM " . $_POST['total_paid'] . "\n";
        $message .= "Date: " . $_POST['invoice_date'] . "\n\n";
        $message .= "You can view your complete invoice at: " . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n\n";
        $message .= "Thank you for choosing The Malaysian Traveler!\n\n";
        $message .= "Best regards,\nThe Malaysian Traveler Team";
        
        $headers = "From: noreply@malayasiantraveler.com\r\n";
        $headers .= "Reply-To: support@malayasiantraveler.com\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Invoice sent to your email successfully!');</script>";
        } else {
            echo "<script>alert('Failed to send email. Please try again later.');</script>";
        }
    }
}

$typeQuery = "
    SELECT 
        p.paymentID,
        p.bookingID,
        p.userID,
        u.username,
        u.userEmail,
        b.numberOfPax,
        b.departureDate,
        b.returnDate,
        b.amountDue,
        CASE 
            WHEN b.packageID IS NOT NULL THEN 'package'
            WHEN b.flightRouteID IS NOT NULL THEN 'flight' 
            WHEN b.hotelID IS NOT NULL THEN 'hotel'
            ELSE 'unknown'
        END as booking_type,
        b.packageID,
        b.flightRouteID,
        b.hotelID
    FROM payments p
    JOIN users u ON p.userID = u.userID
    JOIN bookings b ON p.bookingID = b.bookingID
    WHERE p.paymentID = ?
";

$stmt = $conn->prepare($typeQuery);
if (!$stmt) {
    if ($debug) echo "Debug: Prepare failed: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $paymentID);
$stmt->execute();
$result = $stmt->get_result();
$baseData = $result->fetch_assoc();

if (!$baseData) {
    if ($debug) echo "Debug: Payment not found for paymentID = $paymentID";
    else echo "Payment not found.";
    exit;
}

if ($debug) {
    echo "Debug: Base data found:<br>";
    print_r($baseData);
    echo "<br>";
}

$bookingType = $baseData['booking_type'];
$specificData = [];

if ($debug) echo "Debug: Booking type = $bookingType<br>";

switch ($bookingType) {
    case 'package':
        $packageQuery = "
            SELECT 
                pk.packageName,
                pk.packagePrice,
                pk.packageDuration,
                h.hotelName,
                h.hotelRoomTypes
            FROM packages pk
            JOIN hotels h ON pk.hotelID = h.hotelID
            WHERE pk.packageID = ?
        ";
        $stmt = $conn->prepare($packageQuery);
        if (!$stmt) {
            if ($debug) echo "Debug: Package query prepare failed: " . $conn->error;
            exit;
        }
        $stmt->bind_param("i", $baseData['packageID']);
        $stmt->execute();
        $specificData = $stmt->get_result()->fetch_assoc();
        break;

    case 'flight':
        $flightQuery = "
            SELECT 
                fr.routeDeparturePoint,
                fr.routeArrivalPoint,
                fr.seatPrice,
                fr.flightDuration
            FROM flight_routes fr
            WHERE fr.flightRouteID = ?
        ";
        $stmt = $conn->prepare($flightQuery);
        if (!$stmt) {
            if ($debug) echo "Debug: Flight query prepare failed: " . $conn->error;
            exit;
        }
        $stmt->bind_param("i", $baseData['flightRouteID']);
        $stmt->execute();
        $specificData = $stmt->get_result()->fetch_assoc();
        break;

    case 'hotel':
        $hotelQuery = "
            SELECT 
                h.hotelName,
                h.hotelRoomTypes,
                h.pricePerNight
            FROM hotels h
            WHERE h.hotelID = ?
        ";
        $stmt = $conn->prepare($hotelQuery);
        if (!$stmt) {
            if ($debug) echo "Debug: Hotel query prepare failed: " . $conn->error;
            exit;
        }
        $stmt->bind_param("i", $baseData['hotelID']);
        $stmt->execute();
        $specificData = $stmt->get_result()->fetch_assoc();
        break;

    default:
        if ($debug) echo "Debug: Unknown booking type: $bookingType";
        else echo "Unknown booking type.";
        exit;
}

if (!$specificData) {
    if ($debug) {
        echo "Debug: Booking details not found for this $bookingType booking.<br>";
        echo "Debug: Looking for ID: ";
        switch ($bookingType) {
            case 'package': echo $baseData['packageID']; break;
            case 'flight': echo $baseData['flightRouteID']; break;
            case 'hotel': echo $baseData['hotelID']; break;
        }
    } else {
        echo "Booking details not found for this " . $bookingType . " booking.";
    }
    exit;
}

if ($debug) {
    echo "Debug: Specific data found:<br>";
    print_r($specificData);
    echo "<br>";
}

$data = array_merge($baseData, $specificData);

$totalPaid = 0;
switch ($bookingType) {
    case 'package':
        $totalPaid = $data['numberOfPax'] * $data['packagePrice'];
        break;
    case 'flight':
        $totalPaid = $data['numberOfPax'] * $data['seatPrice'];
        break;
    case 'hotel':
        $checkIn = new DateTime($data['departureDate']);
        $checkOut = new DateTime($data['returnDate']);
        $nights = $checkIn->diff($checkOut)->days;
        $totalPaid = $nights * $data['pricePerNight'];
        break;
}

if ($debug) echo "Debug: Total paid calculated = $totalPaid<br>";

$invoiceDate = date("Y-m-d");

$checkInvoice = $conn->prepare("SELECT invoiceID FROM invoices WHERE paymentID = ?");
$checkInvoice->bind_param("i", $paymentID);
$checkInvoice->execute();
$checkResult = $checkInvoice->get_result();

if ($checkResult->num_rows == 0) {
    if ($debug) echo "Debug: Creating new invoice<br>";
    
    switch ($bookingType) {
        case 'package':
            $insert = $conn->prepare("
                INSERT INTO invoices (bookingID, paymentID, userID, packageID, numberOfPax, totalPaid, tripDuration, booking_type)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->bind_param(
                "iiisiids",
                $data['bookingID'],
                $paymentID,
                $data['userID'],
                $data['packageID'],
                $data['numberOfPax'],
                $totalPaid,
                $data['packageDuration'],
                $bookingType
            );
            break;

        case 'flight':
            $insert = $conn->prepare("
                INSERT INTO invoices (bookingID, paymentID, userID, flightRouteID, numberOfPax, totalPaid, tripDuration, booking_type)
                VALUES (?, ?, ?, ?, ?, ?, 0, ?)
            ");
            $insert->bind_param(
                "iiiiids",
                $data['bookingID'],
                $paymentID,
                $data['userID'],
                $data['flightRouteID'],
                $data['numberOfPax'],
                $totalPaid,
                $bookingType
            );
            break;

        case 'hotel':
            $checkIn = new DateTime($data['departureDate']);
            $checkOut = new DateTime($data['returnDate']);
            $nights = $checkIn->diff($checkOut)->days;
            $insert = $conn->prepare("
                INSERT INTO invoices (bookingID, paymentID, userID, hotelID, numberOfPax, totalPaid, tripDuration, booking_type)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insert->bind_param(
                "iiiiidis",
                $data['bookingID'],
                $paymentID,
                $data['userID'],
                $data['hotelID'],
                $data['numberOfPax'],
                $totalPaid,
                $nights,
                $bookingType
            );
            break;
    }
    
    if (!$insert->execute()) {
        if ($debug) echo "Debug: Insert failed: " . $insert->error;
        exit;
    }
    $invoiceID = $insert->insert_id;
    if ($debug) echo "Debug: New invoice created with ID = $invoiceID<br>";
} else {
    $row = $checkResult->fetch_assoc();
    $invoiceID = $row['invoiceID'];
    if ($debug) echo "Debug: Using existing invoice ID = $invoiceID<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $invoiceID ?></title>
    <link rel="stylesheet" href="invoice.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include("header.php")?>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Invoice</h1>
            <div>
                <p><strong>Invoice ID:</strong> <?= $invoiceID ?></p>
                <p><strong>Date:</strong> <?= $invoiceDate ?></p>
                <p><strong>User:</strong> <?= htmlspecialchars($data['username']) ?></p>
                <p><strong>Booking Type:</strong> <?= ucfirst($bookingType) ?></p>
            </div>
        </div>

        <div class="invoice-info-wrapper">
            <div class="invoice-info">
                <div class="column">
                    <strong><?= ucfirst($bookingType) ?> Name:</strong><br>
                    <?php
                    switch ($bookingType) {
                        case 'package':
                            echo htmlspecialchars($data['packageName']);
                            break;
                        case 'flight':
                            echo htmlspecialchars($data['routeDeparturePoint'] . ' → ' . $data['routeArrivalPoint']);
                            break;
                        case 'hotel':
                            echo htmlspecialchars($data['hotelName']);
                            break;
                    }
                    ?>
                </div>
                
                <?php if ($bookingType == 'package'): ?>
                <div class="column">
                    <strong>Trip Duration:</strong><br>
                    <?= htmlspecialchars($data['packageDuration']) ?> days
                </div>
                <?php elseif ($bookingType == 'flight'): ?>
                <div class="column">
                    <strong>Flight Duration:</strong><br>
                    <?= htmlspecialchars($data['flightDuration']) ?> hours
                </div>
                <?php elseif ($bookingType == 'hotel'): ?>
                <div class="column">
                    <strong>Room Type:</strong><br>
                    <?= htmlspecialchars($data['hotelRoomTypes']) ?>
                </div>
                <?php endif; ?>
                
                <div class="column">
                    <strong>Number of Pax:</strong><br>
                    <?= $data['numberOfPax'] ?>
                </div>
            </div>

            <div class="invoice-info">
                <div class="column">
                    <strong><?= ($bookingType == 'hotel') ? 'Check-in Date:' : 'Departure Date:' ?></strong><br>
                    <?= date('Y-m-d', strtotime($data['departureDate'])) ?>
                </div>
                <div class="column">
                    <strong><?= ($bookingType == 'hotel') ? 'Check-out Date:' : 'Return Date:' ?></strong><br>
                    <?= date('Y-m-d', strtotime($data['returnDate'])) ?>
                </div>
            </div>
        </div>

        <table class="hotel-table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Details</th>
                    <th>Rate (RM)</th>
                    <th>Quantity</th>
                    <th>Subtotal (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                switch ($bookingType) {
                    case 'package':
                        $service = htmlspecialchars($data['packageName']);
                        $details = htmlspecialchars($data['hotelName'] . ' (' . $data['hotelRoomTypes'] . ')');
                        $rate = $data['packagePrice'];
                        $quantity = $data['numberOfPax'];
                        break;
                    case 'flight':
                        $service = 'Flight Ticket';
                        $details = htmlspecialchars($data['routeDeparturePoint'] . ' → ' . $data['routeArrivalPoint']);
                        $rate = $data['seatPrice'];
                        $quantity = $data['numberOfPax'];
                        break;
                    case 'hotel':
                        $service = 'Hotel Accommodation';
                        $details = htmlspecialchars($data['hotelName'] . ' (' . $data['hotelRoomTypes'] . ')');
                        $rate = $data['pricePerNight'];
                        $checkIn = new DateTime($data['departureDate']);
                        $checkOut = new DateTime($data['returnDate']);
                        $quantity = $checkIn->diff($checkOut)->days;
                        break;
                }
                $subtotal = $rate * $quantity;
                ?>
                <tr>
                    <td><?= $service ?></td>
                    <td><?= $details ?></td>
                    <td><?= number_format($rate, 2) ?></td>
                    <td><?= $quantity ?></td>
                    <td><?= number_format($subtotal, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-bottom">
            <div class="invoice-total">
                Total Paid: RM <?= number_format($totalPaid, 2) ?>
            </div>
            
            <div class="button-container">
                <button onclick="printInvoice()" class="export-btn">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
                <button onclick="showEmailModal()" class="export-btn">
                    <i class="fas fa-envelope"></i> Email Invoice
                </button>
            </div>
        </div>
    </div>
    
    <div class="invoice-footer">
        <p style="text-align:center; color:#8b5e3c; margin-top: 40px;">
            Thank you for booking with The Malaysian Traveler
        </p>
        <br>
    </div>

    <div id="emailModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px);">
        <div style="background: #000000; margin: 15% auto; padding: 30px; border: 1px solid rgba(236, 240, 241, 0.2); border-radius: 20px; width: 400px; color: #ecf0f1; position: relative;">
            <span onclick="closeEmailModal()" style="color: #bdc3c7; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            <h3 style="color: #ecf0f1; margin-bottom: 20px;"><i class="fas fa-envelope"></i> Email Invoice</h3>
            <p style="color: #bdc3c7; margin-bottom: 20px;">Send invoice copy to: <strong><?= htmlspecialchars($data['userEmail']) ?></strong></p>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closeEmailModal()" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                <button onclick="sendEmail()" style="background: linear-gradient(135deg, #41a085 0%, #2c8968 100%); color: white; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer;">Send Email</button>
            </div>
        </div>
    </div>

    <form id="emailForm" method="POST" style="display: none;">
        <input type="hidden" name="send_email" value="1">
        <input type="hidden" name="invoice_id" value="<?= $invoiceID ?>">
        <input type="hidden" name="username" value="<?= htmlspecialchars($data['username']) ?>">
        <input type="hidden" name="booking_type" value="<?= $bookingType ?>">
        <input type="hidden" name="total_paid" value="<?= number_format($totalPaid, 2) ?>">
        <input type="hidden" name="invoice_date" value="<?= $invoiceDate ?>">
    </form>

    <script>
        function printInvoice() {
            window.print();
        }

        function showEmailModal() {
            document.getElementById('emailModal').style.display = 'block';
        }

        function closeEmailModal() {
            document.getElementById('emailModal').style.display = 'none';
        }

        function sendEmail() {
            document.getElementById('emailForm').submit();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('emailModal');
            if (event.target == modal) {
                closeEmailModal();
            }
        }
    </script>
</body>
</html>
