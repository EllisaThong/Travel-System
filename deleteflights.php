<?php
include("dbconnect.php");

if (isset($_GET['id'])) {
    $flightID = $_GET['id'];

    // First: Check if the flightRouteID is used in any package
    $checkQuery = "SELECT COUNT(*) FROM packages WHERE flightRouteID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $flightID);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // Used in packages, cannot delete
        echo "<script>
            alert('Cannot delete this flight. It is still used in one or more packages.');
            window.location.href='adminflights.php';
        </script>";
        exit();
    }

    // Safe to delete
    $deleteQuery = "DELETE FROM flight_routes WHERE flightRouteID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $flightID);

    if ($deleteStmt->execute()) {
        header("Location: adminflights.php?message=deleted");
        exit();
    } else {
        echo "Error deleting flight: " . $deleteStmt->error;
    }

    $deleteStmt->close();
} else {
    echo "No flight ID provided.";
}
?>
