<?php
session_start();

// Prevent cached pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['adminUsername'])) {
    header("Location: adminlogin.php");
    exit();
}

$adminName = $_SESSION['adminUsername'];
error_reporting(E_ALL);
ini_set('display_errors', 1);

$section = isset($_GET['section']) ? $_GET['section'] : 'adminsummary.php';
function isActive($current, $section) {
    return $current === $section ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="admindashboard.css?v=2">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Admin Dashboard</title>
</head>
<body>
  <div class="flex-container">
    <nav class="sidebar">
      <!-- Sidebar -->
      <div class="logo">
        <i class="fa-solid fa-plane" style="margin-left:15px"></i>
      </div>
      <ul>
        <li><a class="<?php echo isActive('adminsummary.php', $section); ?>" 
        href="admindashboard.php?section=adminsummary.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a class="<?php echo isActive('ADadminusers.php', $section); ?>" 
        href="admindashboard.php?section=ADadminusers.php"><i class="fas fa-user"></i>Users</a></li>
        <li><a class="<?php echo isActive('ADadminbookings.php', $section); ?>" 
        href="admindashboard.php?section=ADadminbookings.php"><i class="fas fa-ticket-alt"></i>Bookings</a></li>
        <li><a class="<?php echo isActive('ADadminpackages.php', $section); ?>" 
        href="admindashboard.php?section=ADadminpackages.php"><i class="fas fa-suitcase-rolling"></i>Travel Package</a></li>
        <li><a class="<?php echo isActive('ADadminhotels.php', $section); ?>" 
        href="admindashboard.php?section=ADadminhotels.php"><i class="fas fa-hotel"></i>Hotels</a></li>
        <li><a class="<?php echo isActive('ADadminflights.php', $section); ?>" 
        href="admindashboard.php?section=ADadminflights.php"><i class="fas fa-plane"></i>Flights</a></li>
        <li><a class="<?php echo isActive('ADadminreport.php', $section); ?>" 
        href="admindashboard.php?section=ADadminreport.php"><i class="fas fa-file-alt"></i>Report</a></li>
        <li><a href="adminlogout.php" onclick="return confirm('Are you sure you want to logout?');">
          <i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </nav>

    <div class="main-content">
      <header>
        <h2>Welcome to the Admin Dashboard, <?php echo htmlspecialchars($adminName); ?>!</h2>
      </header>
      <section id="content">
        <iframe id="content-frame" src="<?php echo htmlspecialchars($section); ?>"></iframe>
      </section>
    </div>
  </div>

  <script>
    window.addEventListener("pageshow", function(event) {
    if (event.persisted) {
        window.location.reload();
    }
    });

    function loadPage(page) {
      fetch(page)
        .then(response => response.text())
        .then(data => {
          document.getElementById("content").innerHTML = data;
        })
        .catch(error => {
          console.error("Error loading page:", error);
          document.getElementById("content").innerHTML = "<p>Error loading page.</p>";
        });
    }

    function editUser(id, editBtn) {
      const row = editBtn.closest('tr');
      const deleteBtn = row.querySelector('button[onclick^="deleteUser"]');
      deleteBtn.disabled = false;
      alert("Edit mode for user ID " + id + ". Now you can delete.");
    }

    function deleteUser(id) {
      if (confirm("Are you sure you want to delete user ID " + id + "?")) {
        alert("User deleted (placeholder)");
        // Implement actual deletion with AJAX if needed
      }
    }

        document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.sidebar ul li a');
        const currentSection = new URLSearchParams(window.location.search).get('section') || 'adminsummary.php';

        links.forEach(link => {
            link.classList.remove('active');
            if (link.href.includes(currentSection)) {
                link.classList.add('active');
            }
        });
    });
    
  </script>
</body>
</html>

