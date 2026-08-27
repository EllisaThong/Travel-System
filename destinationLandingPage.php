<?php
session_start();
include 'dbconnect.php';
include 'header.php';

$stmt = $conn->prepare("SELECT * FROM destinations");
$stmt->execute();
$result = $stmt->get_result();
$destinations = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <link rel="stylesheet" href="destinationLandingPage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body style="background-color: burlywood;">
    <div class="textContainer">
        <h1>Welcome Traveler, where would you like to go next?</h1>
    </div>
    <div class="destinationGrid">
        <?php foreach ($destinations as $destination):
            $imageFolder = "destinationImages/" . $destination['destinationName'];
            $images = glob("$imageFolder/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
            if (!empty($images)) {
                $backgroundImage = $images[array_rand($images)];
            } else {
                $backgroundImage = "destinationImages/Kuala Lumpur/Dataran-Merdeka.webp";
            }
        ?>
            <a href="destinations.php?id=<?= $destination['destinationID'] ?>"
                class="destinationCards"
                style="background: url('<?php echo $backgroundImage ?>'); 
                        background-size: auto 100%; 
                        background-position: center;">
                <div class="destinationName">
                    <p><?= htmlspecialchars($destination['destinationName']) ?></p>
                </div>
            </a>
        <?php endforeach ?>
    </div>
</body>

</html>