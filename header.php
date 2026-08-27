

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
<link rel="stylesheet" href="Login&SignUp/Login&SignUp.css?v=1.7">
<style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

    .header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow:  0px 3px 21px 2px rgba(0, 0, 0, 0.1);
        padding: 10px 30px;
        display: flex;
        justify-content: space-between; /* Left vs Right */
        align-items: center;
        position: relative;   /* Important: creates positioning context */
        z-index: 9999; 
    }

    .headerLeft h1 {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 27.5px;
        font-weight: 700;
        color: #2d6a4f;
    }

    .headerLeft h1 a {
        text-decoration: none;
        color: inherit;
    }

    /* Right section styling */
    .headerRight {
        display: flex;
        align-items: center;
        gap: 20px; /* space between nav and dropdown */
        font-size: 18px;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 20px;
    }

    .nav-menu li a {
        text-decoration: none;
        color: #333;
    }

    .nav-menu li a:hover {
        color: #2d6a4f;
        text-decoration:underline;
    }

    /* Dropdown styling */
    .dropdown {
        position: relative;
        z-index: 100;
    }

    .dropdown-toggle {
        text-decoration: none;
        color: #333;
        cursor: pointer;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        min-width: 150px;
        z-index: 9999;
    }

    .dropdown-menu .dropdown-item {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #2d6a4f;
        z-index: 9999;
    }

    .dropdown-menu .dropdown-item:hover {
        background: #f0f0f0;
        color: #2d6a4f;
    }

    .dropdown:hover .dropdown-menu {
        display: block;
    }


        ::-webkit-scrollbar {
        width: 3px;
        height: 3px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
        background-clip: padding-box;
        border: 5px solid transparent;
        width: 12px;
    }

    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @media (max-width: 600px) {
        .title {
            font-size: 20px;
        }

        .text {
            font-size: 14px;
        }
        
        .nav-menu {
            gap: 0px;
        }

    }
</style>
</head>
<body>
 <div class="header">
    <!-- Left Side: Logo + Title -->
    <div class="headerLeft">
        <h1>
            <i class="fa-solid fa-plane"></i>
            <a class="title"href="home.php">The Malaysian Traveler</a>
        </h1>
    </div>

    <!-- Right Side: Nav Links + Auth -->
    <div class="headerRight">
        <ul class="nav-menu">
            <li><a class="text" href="javascript:window.history.back();">Back</a></li>
            <li><a class="text" href="aboutus.php">About Us</a></li>
        </ul>

        <div class="auth-section">
            <div class="dropdown">
                <?php if (isset($_SESSION['Username'])): ?>
                    <a href="#" class="dropdown-toggle text">
                        Welcome! <?php echo htmlspecialchars($_SESSION['Username']); ?> <span class="arrow">▼</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="userprofilepage.php" class="dropdown-item text">Profile</a>
                        <a href="Logout.php" class="dropdown-item text">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="#" class="dropdown-toggle">
                        Signup/Login <span class="arrow">▼</span>
                    </a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item" id="signupBtn">Sign Up</a>
                        <a href="#" class="dropdown-item" id="loginBtn">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
    if (!isset($_SESSION['Username'])) {
        include 'SignUp.php';
        include 'Login.php';
    }
?>
  <script src="header/header.js"></script>
  <script src="Login&SignUp/Login&SignUp.js"></script>
</div>
   
</body>
</html>




