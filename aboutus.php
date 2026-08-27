<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="aboutus.css?v=1.2">
  <title>About Us</title>
</head>
<body>
  <main class="main-content"></main>
  <?php include 'header.php'; ?>

  <div class="img-container">
    <h2 class="main-title">About Us</h2>
    <img src="aboutus.jpg" alt="main-image" class="main-image">
  </div>

  <div class="whowearecontent"> 
      <div class="whowearep">
        <h1>Who We Are</h1>
        <br>
        <p>We are a dedicated team to p dedicated to making travel easy, 
          accessible, and memorable. From seamless flight bookings to curated tourism packages, 
          we help you explore the world with confidence.
        <br>
        <br>
        Thank you for visiting our site. We look forward to serving you!</p>
      </div>
      <div class="whowearepng">
        <img src="passports.png" alt="passports" class="passports-image">
      </div>
  </div>

  <div class="ourmission">
    <img src="ourmission.jpg" alt="ourmission" class="mission-image">
      <div class="mission-content">
      <h2 class="mission-title">Our Mission</h2>
      <p>Our mission is to simplify travel planning while offering reliable and affordable options for every kind of traveler.</p>
    </div>
  </div>
    
  <div class="whychooseus">
  <div class="whychooseuscontent">
    <div class="text-section">
      <h1>Why Choose Us?</h1>
      <p>We offer 
        transparent <strong>pricing</strong> , 
        <strong>secure bookings</strong>, 
        seasonal <strong>offers</strong> , 
        satisfied <strong>package deals</strong> while 
        working with <strong>trusted airlines and hotel partners.</strong></p>
    </div>
    <img src="whychooseus.jpg" alt="beach image" class="beach-image">
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

