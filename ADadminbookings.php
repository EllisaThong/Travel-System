<?php
include("dbconnect.php");

// Handle search input (e.g. by username, booking ID, or package name)
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// SQL query
$sql = "SELECT 
            b.bookingID,
            b.bookingDate,
            b.bookingTime,
            b.numberOfPax,
            b.departureDate,
            b.returnDate,
            b.amountDue,
            b.rating,
            u.username,
            p.packageName,
            h.hotelName,
            f.airlineProvide
        FROM bookings b
        LEFT JOIN users u ON b.userID = u.userID
        LEFT JOIN packages p ON b.packageID = p.packageID
        LEFT JOIN hotels h ON b.hotelID = h.hotelID
        LEFT JOIN flight_routes f ON b.flightRouteID = f.flightRouteID";

// Add search filters
if (!empty($search)) {
    $sql .= " WHERE 
                u.username LIKE '%$search%' OR
                p.packageName LIKE '%$search%' OR
                b.bookingID LIKE '%$search%'";
}

$result = $conn->query($sql);

// Handle query errors
if (!$result) {
    if (is_object($conn)) {
        die("Query Failed: " . $conn->error);
    } else {
        die("Query Failed: Database connection is invalid.");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin View Bookings</title>
    <link rel="stylesheet" href="ADadminbookings.css?v=1.8"> 
</head>
<body>
<div class="booking-container">

    <h1 class="user-title">Admin Booking Management</h1>
    <form method="GET" class="search-box">
        <input type="text" name="search" class="search-input" placeholder="Search by username or package..."
        value="<?php echo htmlspecialchars($search); ?>" />

        <button type="submit" class="search-button">Search</button>
    </form>

    <table border="0" cellpadding="8" cellspacing="0" class="bookings-table">
        <thead class="table-header">
            <tr>
                <th>Booking ID</th>
                <th>Username</th>
                <th>Package</th>
                <th>Hotel</th>
                <th>Flight</th>
                <th>Booking Date</th>
                <th>Departure</th>
                <th>Return</th>
                <th>Pax</th>
                <th>Amount Due (RM)</th>
                <th>Rating</th>
                <th>Invoice</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="table-row">
                        <td><strong><?= htmlspecialchars($row['bookingID'] ?? '-') ?></strong></td>
                        <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['packageName'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['hotelName'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['airlineProvide'] ?? '-') ?></td>
                        <td><?= htmlspecialchars(($row['bookingDate'] ?? '-') . ' ' . ($row['bookingTime'] ?? '-')) ?></td>
                        <td><?= htmlspecialchars($row['departureDate'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['returnDate'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['numberOfPax'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['amountDue'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['rating'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($row['invoiceID'] ?? '-') ?></td>
                        <td>
                            <div class='admin-buttons'>
                                <a href='ADeditbookings.php?id=<?= $row["bookingID"] ?>' class='btn-edit'>Edit</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="12">No bookings found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



</body>