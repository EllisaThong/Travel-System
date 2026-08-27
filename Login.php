<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['login-email']) && isset($_POST['login-password'])) {
    include 'dbconnect.php';

    $email = $_POST['login-email'];
    $password = $_POST['login-password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE userEmail = ? AND userPassword = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['Username'] = $user['username']; 
        $_SESSION['userID'] = $user['userID'];

        $redirect = $_SERVER['HTTP_REFERER'] ?? 'home.php';
        echo "<script>alert('✅ Login successful!'); window.location.href='$redirect';</script>";
    } else {
        echo "<script>alert('❌ Invalid email or password.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>


      <!-- Login Modal -->
  <div class="modal" id="loginModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Login</h2>
        <span class="close" id="closeLoginModal">&times;</span>
      </div>
     <form class="login-form" method="POST" action="">
        <div class="form-group">
          <label for="login-email">Email:</label>
          <input type="email" id="login-email" name="login-email" placeholder="Enter your Email" required>
        </div>
        <div class="form-group">
          <label for="login-password">Password:</label>
          <div style="position:relative;">
            <input type="password" id="login-password" name="login-password" placeholder="Enter Password" required style="padding-right:40px;">
            <button type="button" id="toggleLoginPassword" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1.2em;">👁</button>
          </div>
        </div>
        <button type="submit" class="submit-btn">Login</button>
        <p class="login-link">No account? <a href="#" id="signupLink">Sign up here.</a></p>
      </form>
    </div>
  </div>
</body>

</html>


