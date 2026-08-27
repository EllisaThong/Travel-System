<?php
include"dbconnect.php";

// Handle search
$searchKeyword = "";
if (isset($_GET['search'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM users 
            WHERE userID LIKE '%$searchKeyword%' 
            OR username LIKE '%$searchKeyword%' 
            OR userPassword LIKE '%$searchKeyword%' 
            OR userPhone LIKE '%$searchKeyword%' 
            OR userGender LIKE '%$searchKeyword%' 
            OR userDOB LIKE '%$searchKeyword%' 
            OR userEmail LIKE '%$searchKeyword%'";
} else {
    $sql = "SELECT * FROM users";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users</title>
    <link rel="stylesheet" href="ADadminusers.css">
</head>
<body>
    <div class="user-container">
        <h1 class="user-title">Admin User Management</h1>
        <form method="GET" class="search-box">
            <input type="text" name="search" class="search-input" placeholder="Enter Username to search..." 
            value="<?php echo htmlspecialchars($searchKeyword); ?>" />
            <button type="submit" class="search-button">Search</button>
        </form>

        <table border="0" cellpadding="10" cellspacing="0" class="user-table">
  <thead class="table-header">
    <tr>
      <th>User ID</th>
      <th>Username</th>
      <th>Password</th>
      <th>Phone</th>
      <th>Gender</th>
      <th>Date of Birth</th>
      <th>Email</th>
      <th> </th>
    </tr>
  </thead>
  <tbody>
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr class="table-row">
            <td><strong><?php echo htmlspecialchars($row['userID']); ?></strong></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['userPassword']); ?></td>
            <td><?php echo htmlspecialchars($row['userPhone']); ?></td>
            <td><?php echo htmlspecialchars($row['userGender']); ?></td>
            <td><?php echo htmlspecialchars($row['userDOB']); ?></td>
            <td><?php echo htmlspecialchars($row['userEmail']); ?></td>

            <td>
                <?php
                    echo "<div class='admin-buttons'>";
                    echo "<a href='ADeditusers.php?id=" . $row["userID"] . "' class='btn-edit'>Edit</a>";
                    echo "<a href='deleteusers.php?id=" . $row["userID"] . "' class='btn-delete' 
                    onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";
                    echo "</div>";
                ?>
            </td>

        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td colspan="7">No users found.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
    </div>
</body>
</html>