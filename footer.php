<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="footer.css?v=1.2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Footer</title>
</head>
<body>   
    <footer class="site-footer">
        <div class="footer-container">
            
            <!-- Quick Links & Contact -->
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="AboutUs.php">About Us</a>
                <a href="userpackages.php">Packages</a>
                <a href="contact.php">Contact</a>
                <a href="faq.php">FAQ</a>

                <h3>Contact Us</h3>
                <p>Email:<a href="mailto:TheMsiaTraveler@email.com">info@yourwebsite.com</a></p>
                <p>Phone: +60 12-345 6789</p>
            </div>

            <!-- Upcoming Events -->
            <div class="footer-section">
                <div class="upcomingevents">
                    <h3>Upcoming Events</h3>
                    <img src="Image/Upcomingevents.jpg" alt="Upcoming Event">
                    <p>Don’t miss our special travel fair this September!  
                    Amazing deals, live talks, and exclusive discounts await.</p>
                </div>
            </div>

            <div class="payment-icons">
                <!-- Font Awesome Icons -->
                <i class="fa-brands fa-cc-visa" title="Visa"></i>
                <i class="fa-brands fa-cc-mastercard" title="MasterCard"></i>
                <i class="fa-brands fa-cc-paypal" title="PayPal"></i>

                <!-- Custom Local Payment Icons -->
                <img src="Image/fpx.svg" alt="FPX" title="FPX">
                <img src="Image/touchngo.svg" alt="Touch 'n Go" title="Touch 'n Go">
            </div>
                <div class="payment-text">
                <p>Card payments, online banking, and e-wallets acceptted only.</p>
                </div>

        </div>

        <div class="footer-bottom">
            &copy; <?= date("Y") ?> The Malaysian Traveller. All Rights Reserved.
        </div>
    </footer>

</body>

</html>





