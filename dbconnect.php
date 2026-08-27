<?php
$conn = mysqli_connect("localhost", "root", "", "capstone_project_tourism_system");
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Connection to DB failed.');
        </script>";
}
?>