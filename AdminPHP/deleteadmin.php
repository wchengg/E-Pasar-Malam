<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location:adminlogin.php");
    exit();
}

// Database connection
include_once("database.php");

if (isset($_GET['id'])) {
    $admin_id = intval($_GET['id']); // Sanitize admin_id

    // SQL query to delete the admin record
    $sql = "DELETE FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $admin_id);

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        echo "<script>
                alert('Admin record deleted successfully');
                window.location.href = 'admin.php'; // Redirect to admin.php
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close database connection
    $stmt->close();
    $conn->close();
} else {
    die("Admin ID not provided.");
}
