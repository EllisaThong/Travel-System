<?php
session_start();
include 'dbconnect.php';

$error = ""; // store error here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE BINARY adminUsername = ? AND BINARY adminPassword = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['adminUsername'] = $username;
        header("Location: admindashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="adminlogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>
<body>
<form id="signInForm" action="adminlogin.php" method="POST" autocomplete="off">
    <div class="container">
        <div class="sign-in-left">
            <div class="left-content">
                <h1 class="left-title">Hello, <br> Welcome !</h1>
            </div>
        </div>
        <div class="sign-in-right">
            <h2 id="formTitle">Sign In</h2>
            <form id="signInForm" autocomplete="off">
                <div class="input-group">
                    <div class="input-icon-group">
                        <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                        <input type="text" id="sign-in-username" name="username" required placeholder="Username">
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-icon-group password">
                        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="sign-in-password" name="password"  required placeholder="Password">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('sign-in-password')">Show</button>
                    </div>
                </div>

                    <!-- Error message inside the form -->
                <?php if (!empty($error)): ?>
                    <div id="signInMessage" style="color:red; font-size:14px; margin-bottom:10px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Sign In</button>
                <div id="signInMessage"></div>
            </form>
        </div>
    </div>
</form>
    <script src="adminlogin.js"></script>
</body>
</html>
