<?php
include 'dbconnect.php'; // Database connection

// Fetch invoice data
$invoiceQuery = "SELECT * FROM invoices";
$invoiceResult = mysqli_query($conn, $invoiceQuery);

// Fetch payment data
$paymentQuery = "SELECT * FROM payments";
$paymentResult = mysqli_query($conn, $paymentQuery);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="ADadminreport.css">
  <title>Admin Report</title>
</head>
<body>

<h1>Admin Reports</h1>

<div class="tabs">
    <button class="tab-link active" onclick="openTab('invoiceTab')">Invoice Reports</button>
    <button class="tab-link" onclick="openTab('paymentTab')">Payment Reports</button>
</div>

<!-- Invoice Section -->
<div id="invoiceTab" class="tab-content" style="display:block;">
    <h2>Invoice Reports</h2>
    <div class="action-buttons">
        <button onclick="alert('Generating Invoice Report...')">Generate Report</button>
    </div>
    <table id="invoiceTable" border="0" cellpadding="10" cellspacing="0" class="invoiceTable">
        <tr>
            <th>Invoice ID</th>
            <th>Booking ID</th>
            <th>Payment ID</th>
            <th>User ID</th>
            <th>Package ID</th>
            <th>Package Name</th>
            <th>No. of Pax</th>
            <th>Total Paid</th>
            <th>Trip Duration</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($invoiceResult)): ?>
        <tr class="Table-row">
            <td><?= $row['invoiceID'] ?></td>
            <td><?= $row['bookingID'] ?></td>
            <td><?= $row['paymentID'] ?></td>
            <td><?= $row['userID'] ?></td>
            <td><?= $row['packageID'] ?></td>
            <td><?= $row['packageName'] ?></td>
            <td><?= $row['numberOfPax'] ?></td>
            <td><?= $row['totalPaid'] ?></td>
            <td><?= $row['tripDuration'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Payment Section -->
<div id="paymentTab" class="tab-content">
    <h2>Payment Reports</h2>
    <div class="action-buttons">
        <button onclick="alert('Generating Payment Report...')">Generate Report</button>
    </div>
    <table id="paymentTable" border="0" cellpadding="10" cellspacing="0" class="paymentTable">
        <tr>
            <th>Payment ID</th>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Payment Date</th>
            <th>Payment Time</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($paymentResult)): ?>
        <tr class="Table-row">
            <td><?= $row['paymentID'] ?></td>
            <td><?= $row['bookingID'] ?></td>
            <td><?= $row['userID'] ?></td>
            <td><?= $row['paymentDate'] ?></td>
            <td><?= $row['paymentTime'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
function openTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.querySelectorAll('.tab-link').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabId).style.display = 'block';
    event.target.classList.add('active');
}

</script>

</body>
</html>
