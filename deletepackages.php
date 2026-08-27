<?php
include("dbconnect.php");

// Check if 'id' parameter is provided
if (isset($_GET['id'])) {
    $packageID = $_GET['id'];

    // Delete the package record
    $sql = "DELETE FROM packages WHERE packageID = '$packageID'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Package deleted successfully'); window.location.href='adminpackages.php';</script>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // Redirect if no ID is provided
    header("Location: adminpackages.php");
    exit();
}
?>
