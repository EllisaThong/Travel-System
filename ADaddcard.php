<?php
session_start();
include ("dbconnect.php");

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

$sql = "INSERT INTO card_details (userID, cardNumber, cardName, cardDate) 
        VALUES ('$userID', '$cardNumber', '$cardName', '$cardDate')";

if ($conn->query($sql) === TRUE) {
    header("Location: userprofilepage.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
