<?php
session_start();
include 'dbconnect.php'; // your connection file

$query = "SELECT * FROM destinations";
$resultDestinations = mysqli_query($conn, $query);

$destinations = [];
while ($rowDest = mysqli_fetch_assoc($resultDestinations)) {
    $destinations[] = $rowDest;
}

$sql = "SELECT packageID, packageName, packageDescription, packagePrice, packageDuration
        FROM packages
        ORDER BY packageID DESC
        LIMIT 3";

$result = $conn->query($sql);

// Pre-process image paths for each package
if ($result && $result->num_rows > 0) {
    // Store all rows in an array with imagePath added
    $packages = [];
    while ($row = $result->fetch_assoc()) {
        $imageName = str_replace(' ', '_', $row['packageName']) . ".jpg";
        $imagePath = $imageName;

        if (!file_exists("Image/" . $imagePath)) {
            $imagePath = "default.jpg";
        }

        $row['imagePath'] = $imagePath;
        $packages[] = $row;
    }
}

$query = "SELECT hotelID, hotelName, hotelDescription, hotelRoomTypes, pricePerNight, hotelPhone, hotelEmail, hotelAddress, hotelImage 
          FROM hotels";
$result = mysqli_query($conn, $query);
$hotels = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
    <title>Homepage</title>
    <link rel="stylesheet" href="home.css?v=1.5">
    <link rel="stylesheet" href="Login&SignUp.css?v=1.7">

</head>
<body>
<main class="main-content">
  <?php include 'header.php'; ?>
    <div class="Homepage-video-container">
      <video autoplay muted loop playsinline class="Homepage-video">
        <source src="Image/homepage.mp4" type="video/mp4">      </video>
      <div class="Homepage-overlay">
        <div class="Homepage-content">
          <h1>Welcome to 
            <br>The Malaysian Traveler!</h1>
          <p>Unlock your journey to experience Malaysia's beauty with us !</p>
        </div>
      </div>
    </div>
  </main>

  <section class="travel-section">
    <div class="travel-section-inner">
      <h2>Wander More, Worry Less</h2>
      <p class="travel-desc">
        Malaysia welcomes you with open arms, stunning landscapes, and budget-friendly adventures. Get inspired and start exploring!
      </p>
      <div class="travel-cards">
        <div class="travel-card">
          <img src="Image/batucaves.webp" alt="Beyond the hotspots">
          <h3>Beyond the Brochure</h3>
          <p>Discover hidden gems off the tourist trail.</p>
        </div>
        <div class="travel-card">
          <img src="Image/Theanhou.jpg" alt="Behind the scenes of MALAYSIA">
          <h3>Behind the scenes of KL</h3>
          <p>Uncover local life beyond the city skyline.</p>
        </div>
        <div class="travel-card">
          <img src="Image/kundasang.jpg" alt="Norwegian Scenic Routes">
          <h3>Where Malaysia Meets Sky</h3>
          <p>Explore Malaysia’s wild beauty and calm shores.</p>
        </div>
      </div>
    </div>   
  </section>

  <div class="Destination-slider">
    <div class="slider-header">
      <h1>Explore Our Destinations</h1>
      <br>
      <p>Discover the beauty of Malaysia
      <br>  
      through our featured destinations.</p>
    </div>

    <div class="button-wrapper">
    <button id="destination-prev" class="prev-btn"><</button>

    <img src="Image/camera.png" alt="Camera" class="flatlay camera">
    <img src="Image/sunglasses.png" alt="Sunglasses" class="flatlay sunglasses">

    <div class="slider-container" id="slider-destination">
      <?php foreach ($destinations as $destination): ?>
        <?php
          $imageFolder = "destinationImages/" . $destination['destinationName'];
            $images = glob("$imageFolder/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
            if (!empty($images)) {
                $backgroundImage = $images[array_rand($images)];
            } else {
                $backgroundImage = "destinationImages/Kuala Lumpur/Dataran-Merdeka.webp";
            }
        ?>
        <div class="slide">
          <img src="<?php echo $backgroundImage?>" alt="<?= htmlspecialchars($destination['destinationName']) ?>">
          <div class="caption">
            <h2><?= htmlspecialchars($destination['destinationName']) ?></h2>
            <p><?=htmlspecialchars(formatCaption($backgroundImage))?></p>
            <br>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button id="destination-next" class="next-btn">></button>

          <div>
            <a href="destinationLandingPage.php" class="d-view-all-btn">View All Destination</a>
          </div>
    </div>
  </div>

<section class="popular-packages-section">  
    <div class="popular-packages-section-inner">
        <h2>
            Top Getaways <span class="highlight-red">This Season</span>
        </h2>
        <p class="popular-desc">
            Trending Now in Malaysia
        </p>

        <div class="package-images">
            <?php if (!empty($packages)): ?>
              <?php foreach ($packages as $row): ?>
                <div class="package-card">
                  <img src="Image/<?= $row['imagePath'] ?>" class="package-main-img">
                      <div class="package-card-overlay">
                          <span class="package-card-title"><?= htmlspecialchars($row['packageName']); ?></span>
                          <p class="package-price">RM <?= number_format($row['packagePrice'], 2); ?></p>
                          <p class="package-duration"><?= htmlspecialchars($row['packageDuration']); ?> days</p>
                      </div>
                  </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No packages available at the moment.</p>
            <?php endif; ?>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
            <a href="userpackages.php" class="view-all-btn">View All Package</a>
        </div>
    </div>
</section>

<div class="hotel-slider">
    <div class="button-wrapper">
        <button id="hotel-prev" class="prev-btn"><</button>

        <div class="slider-container" id="hotel-slider">
            <?php foreach ($hotels as $hotel): ?>
                <div class="slide">
                    <img src="<?= htmlspecialchars($hotel['hotelImage']) ?>" 
                        alt="<?= htmlspecialchars($hotel['hotelName']) ?>">
                    <div class="caption">
                        <h2><?= htmlspecialchars($hotel['hotelName']) ?></h2>
                        <p><?= htmlspecialchars($hotel['hotelDescription']) ?></p>
                        <small><strong>Room Types:</strong> <?= htmlspecialchars($hotel['hotelRoomTypes']) ?></small><br>
                        <small><strong>Price Per Night:</strong> RM <?= htmlspecialchars($hotel['pricePerNight']) ?></small><br>
                        <small><strong>Address:</strong> <?= htmlspecialchars($hotel['hotelAddress']) ?></small>
                    </div> 
                </div>
            <?php endforeach; ?>
        </div>

        <button id="hotel-next" class="next-btn">></button>
        
    <div class="hotel-slider-header">
        <h1>Malaysia's Finest
          <br> Hotels</h1>
        <br>
        <p>Sleep in Style: Malaysia’s Finest Stays,
        <br>  
        Handpicked for You.</p>

        <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
              <a href="userhotels.php" class="h-view-all-btn">View more hotels</a>
      </div>
    </div>

  </div>
</div>

<div class="flight-section">
  <div class="flight-section-content">
    <div class="text-section">
      <h1>Your Journey, Our Wings</h1>
      <p>Fly into the heart of Malaysia — skyscrapers,
         jungles, beaches, and flavours all waiting beyond the clouds.</p>

        <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
              <a href="userflights.php" class="f-view-all-btn">View more flights</a>
      </div>
    </div>
    <img src="Image/flights.jpg" alt="beach image" class="flight-image">
  </div>
</div>

<?php include 'footer.php'; ?>
<script src= "home.js"></script>
</body>

</html>
