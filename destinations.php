<?php
session_start();
include 'dbconnect.php';
include 'header.php';

$id = $_GET['id'] ?? 1;

$stmt = $conn->prepare("SELECT * FROM destinations WHERE destinationID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();
$facts = $destination['destinationFacts'];

if (!$destination) {
    die("Destination not found!");
}

$stmt2 = $conn->prepare("SELECT * FROM packages WHERE destinationID = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$packages = $result2->fetch_all(MYSQLI_ASSOC);

$imageFolder = "destinationImages/" . $destination['destinationName'];
$images = glob("$imageFolder/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
$totalImages = count($images);

$blocks = array_filter(
    array_map('trim', explode('* ', ltrim($facts, '* ')))
);

function formatCaption($filePath)
{
    $name = pathinfo($filePath, PATHINFO_FILENAME);
    $name = str_replace(['-', '_'], ' ', $name);
    return ucwords($name);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $destination['destinationName'] ?></title>
    <link rel="stylesheet" href="destinations.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php foreach ($images as $index => $image): ?>
                <div class="swiper-slide">
                    <div class="image"><img src="<?= $image ?>"></div>
                    <div class="text"><?= formatCaption($image) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <div class="textContainer">
        <h1 class="title"><?= $destination['destinationName'] ?></h1>
        <br>
        <div class="descContainer">
            <p class="description"><?= $destination['destinationDescription'] ?></p>
        </div>
        <br>
        <hr><br>
        <h3>Fun facts about <?= $destination['destinationName'] ?>:</h3>
        <div class="facts">
            <?php foreach ($blocks as $fact): ?>
                <p class="fact"><?= htmlspecialchars($fact) ?></p>
            <?php endforeach; ?>
        </div>
        <br>
        <hr>
    </div>

    <div class="packagesContainer">
        <h3>Here are some of the travel packages featuring <?= $destination['destinationName'] ?>:</h3>
        <br>
        <div class="packageGrid">
            <?php foreach ($packages as $package): ?>
                <a href="checkoutpackages.php?packageID=<?= urlencode($package['packageID']) ?>" class="packageCards">
                    <h4 class="packageName"><?= htmlspecialchars($package['packageName']) ?></h4>
                    <br>
                    <p class="packageDescription"><?= htmlspecialchars($package['packageDescription']) ?></p>
                    <br>
                    <p class="packgePrice">From RM <?= htmlspecialchars($package['packagePrice']) ?>.00 per pax.</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const swiper = new Swiper('.swiper', {
            autoplay: {
                delay: 3000,
                diableOnInteraction: false,
            },

            loop: true,

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

</body>

</html>