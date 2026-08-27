<?php
session_start();
include("dbconnect.php");
    if (!isset($_SESSION['userID'])) {
        header("Location: home.php");
        exit();
    }

    $username = $phonenumber = $gender = $dob = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateProfile'){
        $new_username = trim($_POST['username']);
        $new_phone = trim($_POST['phone']);
        $new_gender = trim($_POST['gender']);
        $new_dob = trim($_POST['dob']);
        $new_email = trim($_POST['email']);

        $checkStmt = $conn->prepare("SELECT userID FROM users WHERE (username = ? OR userEmail = ?) AND userID != ?");
        $checkStmt->bind_param("ssi", $new_username, $new_email, $_SESSION['userID']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "<script>
                alert('Username or email already taken. Please choose another.');
                window.history.back();
                </script>";
            exit();
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, userPhone=?, userGender=?, userDOB=?, userEmail=? WHERE userID=?");
            $stmt->bind_param("sssssi", $new_username, $new_phone, $new_gender, $new_dob, $new_email, $_SESSION['userID']);

            if ($stmt->execute()) {
                header("Location: userprofilepage.php");
                exit();
            }
            $stmt->close();
        }
        $checkStmt->close();
    }

    $stmt = $conn->prepare("SELECT username, userPhone, userGender, userDOB, userEmail FROM users WHERE userID = ?");
    $stmt->bind_param("i", $_SESSION['userID']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = htmlspecialchars($user['username']);
        $phonenumber = htmlspecialchars($user['userPhone']);
        $gender = htmlspecialchars($user['userGender']);
        $dob = htmlspecialchars($user['userDOB']);
        $email = htmlspecialchars($user['userEmail']);
    } else {
        echo "<script>
            alert('User information not found.');
            window.location.href = 'home.php';
        </script>";
        exit();
    }

    //ADD CARD
    if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['action']) && $_POST['action'] === 'addCard') {
        $userID = $_SESSION['userID'];
        $cardName = htmlspecialchars(trim($_POST['cardName']));
        $cardNumber = preg_replace('/\s+/', '', $_POST['cardNumber']);
        $cardDate = preg_replace('/\s+/', '', $_POST['cardExDate']);

        if (!preg_match('/^\d{16}$/', $cardNumber)) {
            echo "<script>
                alert('Card number must be exactly 16 digits.');
                window.history.back();
            </script>";
            exit();
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $cardDate)) {
            echo "<script>
                alert('Invalid card expiry date format. Use MM/YY.');
                window.history.back();
            </script>";
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO card_details (userID, cardNumber, cardName, cardDate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userID, $cardNumber, $cardName, $cardDate);
        $stmt->execute();
    }

    //DELETE CARD
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cardID'])) {
        $cardID = intval($_POST['cardID']);
        $userID = $_SESSION['userID'];

        $stmt = $conn->prepare("DELETE FROM card_details WHERE cardID = ? AND userID = ?");
        $stmt->bind_param("ii", $cardID, $userID);

        if ($stmt->execute()) {
            header("Location: userprofilepage.php");
            exit();
        } 

        $stmt->close();
    }

    //ADD RATING
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
        $bookingID = intval($_POST['bookingID']);
        $rating = intval($_POST['rating']);
        $userID = $_SESSION['userID'];
        
        $stmt = $conn->prepare("UPDATE bookings SET rating = ? WHERE bookingID = ? AND userID = ?");
        $stmt->bind_param("iii", $rating, $bookingID, $userID);
        
        if ($stmt->execute()) {
            header("Location: userprofilepage.php");
            exit();
        }
    }
    $stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Page</title>
    <link rel="stylesheet" href="userprofilepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"> 
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="pageContent">
    <div class="pageWrapper">

        <!-- USER PROFILE -->
        <div class="profileContainer">
            <form action="#" method="POST" autocomplete="off">
                <input type="hidden" name="action" value="updateProfile">
                <h2 style="margin: 0px 4px 8px 0px">Account Settings <i class="fa-solid fa-address-book"></i></h2>
                <div class="formGroup">
                <label for="username"><i class="fa-solid fa-circle-user"></i>Username:</label>
                    <div class="inputWrapper">
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required />
                    <span class="focusBar"></span>
                    </div>
                </div>

                <div class="formGroup">
                <label for="email"><i class="fa-solid fa-envelope"></i>Email:</label>
                    <div class="inputWrapper">
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" required />
                    <span class="focusBar"></span>
                    </div>
                </div>

                <div class="formGroup">
                <label for="phone"><i class="fa-solid fa-phone"></i>Phone Number:</label>
                    <div class="inputWrapper">
                    <input type="tel" id="phone" name="phone" inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')" pattern='[0-9]{9,12}' maxlength="12" value="<?php echo $phonenumber; ?>" required />
                    <span class="focusBar"></span>
                    </div>
                </div>
                                
                <div class="formGroup">
                <label for="dob"><i class="fa-solid fa-calendar-days"></i>Date of Birth:</label>
                    <div class="inputWrapper">
                    <input type="date" id="dob" name="dob" value="<?php echo $dob; ?>" required />
                    <span class="focusBar"></span>
                    </div>
                </div>

                <div class="formGroup">
                    <label>Gender:</label>
                    <div class="radioGroup">
                        <label>
                            <input type="radio" name="gender" value="m" <?php echo ($gender === 'm') ? 'checked' : ''; ?> />
                            <i class="fa-solid fa-person"></i> Male
                        </label>
                        <label>
                            <input type="radio" name="gender" value="f" <?php echo ($gender === 'f') ? 'checked' : ''; ?> />
                            <i class="fa-solid fa-person-dress"></i> Female
                        </label>
                        <label>
                            <input type="radio" name="gender" value="o" <?php echo (!in_array($gender, ['m', 'f'])) ? 'checked' : ''; ?> />
                            Other
                        </label>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button id="resetBtn" type="reset">Reset</button>
                    <button id="saveBtn" type="submit">Save Profile</button>
                </div>
            </form>
        </div>

        <!-- SAVED PAYMENTS -->
        <div class="paymentPanel">
            <h2 style="margin:0px 4px 20px 0px;">Payment Settings <i class="fa-solid fa-credit-card"></i></h2>
            <div class="cardWrapper">
                <?php
                    $cardStmt = $conn->prepare("SELECT * FROM card_details WHERE userID = ?");
                    $cardStmt->bind_param("i", $_SESSION['userID']);
                    $cardStmt->execute();
                    $cardResult = $cardStmt->get_result();

                    if ($cardResult->num_rows > 0) {
                        while ($cardRow = $cardResult->fetch_assoc()) {
                            $cardNumber = $cardRow['cardNumber'];
                            $cardName = $cardRow['cardName'];
                            $cardDate = $cardRow['cardDate'];
                            $maskedCard = str_repeat('•••• ', 3) . substr($cardNumber, -4);

                            echo '<div class="card">';
                                echo '<div class="cardNum">';
                                echo '<div class="cardNumber">' . htmlspecialchars($maskedCard) . '</div>';
                                    echo '<form method="POST" action="#">';
                                        echo '<div class="delButton"><input type="hidden" name="cardID" value="' . $cardRow['cardID'] . '">';
                                        echo '<button id="delButton" type="submit">Delete Card</button></div>';
                                    echo '</form>';
                                echo '</div>';
                                echo '<div class="cardDetails">' . htmlspecialchars($cardName) . ' - ' . htmlspecialchars($cardDate) . '</div>';
                            echo '</div>'; 
                        }
                    } else {
                        echo '<div class="noCards">You have no payment accounts registered, please add payment accounts by clicking on the "Add Payment Options" button below.</div>';
                    }
                ?>               
            </div>
            <div class="addPayment">
                <button id="addPayment"><i class="fa-solid fa-plus"></i>Add Payment Options</button>
            </div>

        </div>

        <!-- BOOKING HISTORY -->
        <div class="bookingHistory">
            <h2 style="margin:3px 0px 10px 0px;">Booking History <i class="fa-solid fa-plane-departure"></i></h2>
            <div class="bookingWrapper">
                <?php
                $userID = $_SESSION['userID'];

                $stmt = $conn->prepare("SELECT 
                    b.bookingID,
                    COALESCE(b.packageName, p.packageName) AS packageName,
                    COALESCE(b.packageHotelName, h.hotelName) AS hotelName,
                    h.hotelAddress,
                    b.numberOfPax,
                    b.departureDate,
                    b.returnDate,
                    b.rating,
                    fr.routeDeparturePoint AS flightDep,
                    fr.routeArrivalPoint   AS flightArr,
                    b.packageFlightRoute,
                    b.paymentID
                FROM bookings b
                LEFT JOIN packages p ON b.packageID = p.packageID
                LEFT JOIN hotels h ON COALESCE(b.hotelID, p.hotelID) = h.hotelID
                LEFT JOIN flight_routes fr ON COALESCE(b.flightRouteID, p.flightRouteID) = fr.flightRouteID
                WHERE b.userID = ?
                ORDER BY b.bookingID DESC");



                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                    $packageName = htmlspecialchars($row['packageName'] ?? 'N/A');

                    $hotelName = !empty($row['hotelName']) ? htmlspecialchars($row['hotelName']) : (!empty($row['packageHotelName']) ? htmlspecialchars($row['packageHotelName']) : 'N/A');

                    if (!empty($row['flightDep']) && !empty($row['flightArr'])) {
                        $flightRoute = htmlspecialchars($row['flightDep']) . " <i class='fa-solid fa-arrow-right'></i> " . htmlspecialchars($row['flightArr']);
                    } elseif (!empty($row['packageFlightRoute'])) {
                        $flightRoute = htmlspecialchars($row['packageFlightRoute']);
                    } else {
                        $flightRoute = 'N/A';
                    }

                    if (!empty($row['flightArr'])) {
                        $destination = htmlspecialchars($row['flightArr']);
                    } elseif (!empty($row['hotelAddress'])) {
                        $destination = htmlspecialchars($row['hotelAddress']);
                    } elseif (!empty($row['packageFlightRoute'])) {
                        $parts = explode('→', $row['packageFlightRoute']);
                        $destination = isset($parts[1]) ? htmlspecialchars(trim($parts[1])) : 'N/A';
                    } else {
                        $destination = 'N/A';
                    }


                    $pax = (int)($row['numberOfPax'] ?? 0);
                    $depDate = htmlspecialchars($row['departureDate'] ?? 'N/A');
                    $retDate = htmlspecialchars($row['returnDate'] ?? 'N/A');
                    $ratingText = is_null($row['rating']) ? "Not rated" : ((int)$row['rating']) . "/5";
                    $paymentID = htmlspecialchars($row['paymentID'] ?? 'N/A');




                        echo '<div class="bookingContainer">';
                            echo '<div class="bookingInfo">';
                                echo "<h4 style='margin: 3px 0px; font-size:18px;'>Destination<i class='fa-solid fa-map-pin'></i>: $destination</h4>";
                                echo "<p style='margin: 2px 2px;'>Flight Route: $flightRoute</p>";
                                echo "<p style='margin: 2px 2px;'>Hotel: $hotelName</p>";
                                echo "<p style='margin: 2px 2px;'>Package: $packageName</p>";
                                echo "<p style='margin: 2px 2px;'>Pax: $pax</p>";
                                echo "<p style='margin: 2px 2px;'>Date: $depDate to $retDate</p>";
                                if (!is_null($row['rating'])) {
                                    $ratingValue = (int)$row['rating'];
                                    $starsHTML = '';

                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $ratingValue) {
                                            $starsHTML .= "<i class='fa-solid fa-star' style='color:#eedd40ff;'></i>"; 
                                        } else {
                                            $starsHTML .= "<i class='fa-solid fa-star' style='color:#bfbfbf;'></i>";
                                        }
                                    }

                                    echo "<h4 style='margin: 3px 2px; font-weight: 550;'>Rating: $starsHTML</h4>";
                                }
                            echo '</div>';
                            echo '<div class="bookingInteracts">';
                                if ($paymentID !== 'N/A') {
                                        echo'<div class="invoiceButton">';
                                        echo "<a id='invoiceButton' href='invoice.php?paymentID=$paymentID'  target='_blank'>Invoice</a>";
                                    echo '</div>';
                                }

                                if (is_null($row['rating'])) {
                                    echo '<div class="rateButton">';
                                        echo '<button id="rateButton" data-booking-id="' . $row['bookingID'] . '">Rate Trip</button>';
                                    echo '</div>';
                                }
                            echo '</div>';

                        echo '</div>';
                    }
                } else {
                    echo '<div class="noBookings">You have not made any bookings before.</div>';
                }

                $stmt->close();
            ?>
            </div>
        </div>
    </div>

    <!-- PAYMENT POPUP -->
    <div id="paymentModal" class="paymentModal" action="addcard.php">
        <div class="pmContent">
            <span class="closeModal">&times;</span>
            <form method="POST" action="#" autocomplete="off">
                <input type="hidden" name="action" value="addCard">
                <h2 style="margin: 0px 4px 8px 0px">Add Card <i class="fa-regular fa-credit-card jumpAnimation"></i></h2>
                    <div class="formGroup">
                        <label for="cardName">Card Name:</label>
                        <div class="inputWrapper">
                            <input type="text" id="cardName" name="cardName" placeholder="Card Name (20 characters max)" maxlength="20" required />
                            <span class="focusBar"></span>
                        </div>
                    </div>

                    <div class="formGroup">
                        <label for="cardNumber">Card Number:</label>
                        <div class="inputWrapper">
                            <input type="text" id="cardNumber" name="cardNumber" inputmode="numeric" maxlength="19" placeholder="Enter 16-digit card number" required/>
                            <span class="focusBar"></span>
                        </div>
                    </div>

                    <div class="formGroup">
                        <label for="cardExDate">Card Expiry Date:</label>
                        <div class="inputWrapper">
                            <input type="text" id="cardExDate" name="cardExDate" inputmode="numeric" maxlength="5" placeholder="MM/YY" required/>
                            <span class="focusBar"></span>
                        </div>
                    </div>

                    <div class="addcardButton">
                        <button id="addCancel" type="button" onclick="closepModal()">Cancel</button>
                        <button id="addCard" type="submit">Add Card</button>
                    </div>
            </form>
        </div>
    </div>

    <!-- RATING POPUP -->
    <div id="ratingModal" class="ratingModal">
        <div class="rmContent">
            <span class="closeModal">&times;</span>
            <form method="POST" action="#" autocomplete="off">
                <h2>Rate Your Trip <i class="fa-regular fa-star spinAnimation"></i></h2>
                
                <input type="hidden" id="bookingId" name="bookingID" value="">
                
                <div class="formGroup">
                    <label>How was your experience?</label>
                    <div class="starRating">
                        <i class="fa-solid fa-star star" data-rating="1"></i>
                        <i class="fa-solid fa-star star" data-rating="2"></i>
                        <i class="fa-solid fa-star star" data-rating="3"></i>
                        <i class="fa-solid fa-star star" data-rating="4"></i>
                        <i class="fa-solid fa-star star" data-rating="5"></i>
                    </div>
                    <div class="ratingText" id="ratingText">Click on stars to rate</div>
                    <input type="hidden" id="selectedRating" name="rating" value="">
                </div>

                <div class="ratingButtons">
                    <button type="button" id="rateCancel">Cancel</button>
                    <button type="submit" id="submitRate" disabled>Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
    </div>
<script src="userprofilepage.js"></script>
<?php include 'footer.php'; ?>
</body>


