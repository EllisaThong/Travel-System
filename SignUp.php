<?php 
if (isset($_POST['submit'])) {
    include 'dbconnect.php'; 
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];

// Check if username or email already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR userEmail = ? OR userPhone = ?");
$stmt->bind_param("sss", $username, $email, $phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $existing = $result->fetch_assoc();
    if ($existing['username'] === $username) {
        echo "<script>alert('❌ Username has been used. Please choose another.');</script>";
    } elseif ($existing['userEmail'] === $email) {
        echo "<script>alert('❌ Email has been used. Please use another email.');</script>";
    } elseif ($existing['userPhone'] === $phone) {
        echo "<script>alert('❌ Phone number has been used. Please use another phone number.');</script>";
    }
}
    else if ($password !== $repassword) {
        echo "<script>alert('❌ Passwords do not match.');</script>";
    } 
    else {
        $gender_char = $gender;

        $stmt = $conn->prepare("INSERT INTO users (username, userPassword, userPhone, userGender, userDOB, userEmail) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $password, $phone, $gender_char, $dob, $email);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Registration successful!');</script>";
        } else {
            echo "<script>alert('❌ Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
</head>
<body>

 
      <!-- Sign Up Modal -->
  <div class="modal" id="signupModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>SignUp Here</h2>
        <span class="close" id="closeSignupModal">&times;</span>
      </div>
      <form class="signup-form" method="POST" action="">
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label for="gender">Gender:</label>
          <select id="gender" name="gender" required>
            <option value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>
        <div class="form-group">
          <label for="dob">Date of Birth:</label>
          <input type="date" id="dob" name="dob" required>
        </div>
        <div class="form-group">
          <label for="phone">Phone No:</label>
          <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" 
       required pattern="\d+" inputmode="numeric" title="Only numbers allowed">
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <div style="position:relative;">
            <input type="password" id="password" name="password" placeholder="Enter Password" required style="padding-right:40px;">
            <button type="button" id="togglePassword" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1.2em;">👁</button>
          </div>
        </div>
        <div class="form-group">
          <label for="repassword">Renter Password:</label>
          <div style="position:relative;">
            <input type="password" id="repassword" name="repassword" placeholder="Renter Password" required style="padding-right:40px;">
            <button type="button" id="toggleRePassword" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:1.2em;">👁</button>
          </div>
        </div>
        <button type="submit" name="submit" class="submit-btn">Submit</button>
        <p class="login-link">Already have an account? <a href="#" id="loginLink">Login here.</a></p>
      </form>
    </div>
  </div>
</body>

</html>
