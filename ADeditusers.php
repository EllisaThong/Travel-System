<?php
include"dbconnect.php";

// Step 1: Validate ID
if (!isset($_GET['id'])) {
    echo "No user ID provided.";
    exit();
}

$userID = $_GET['id'];

// Step 2: Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Step 3: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['userPassword'];
    $phone = $_POST['userPhone'];
    $gender = $_POST['userGender'];
    $dob = $_POST['userDOB'];
    $email = $_POST['userEmail'];

    $update = "UPDATE users SET username=?, userPassword=?, userPhone=?, userGender=?, userDOB=?, userEmail=? WHERE userID=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ssssssi", $username, $password, $phone, $gender, $dob, $email, $userID);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='adminusers.php';</script>";
    } else {
        echo "Error updating user: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="ADadmineditCSS.css">
</head>
<body>
    <h2 class="page-title">Edit User Info</h2>

<div class="form-container">
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Password</label>
        <input type="text" name="userPassword" value="<?= htmlspecialchars($user['userPassword']) ?>" required>

        <label>Phone</label>
        <input type="text" name="userPhone" value="<?= htmlspecialchars($user['userPhone']) ?>" required>

        <label>Gender</label>
        <select name="userGender" required>
            <option value="Male" <?= $user['userGender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $user['userGender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $user['userGender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label>Date of Birth</label>
        <input type="date" name="userDOB" value="<?= $user['userDOB'] ?>" required>

        <label>Email</label>
        <input type="email" name="userEmail" value="<?= htmlspecialchars($user['userEmail']) ?>" required>

        <button type="submit" class="submit">Update User</button>
    </form>
</div>
</body>
</html>

