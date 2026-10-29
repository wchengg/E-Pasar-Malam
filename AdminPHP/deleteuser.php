<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location:adminlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

// Check if 'id' is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $user_id = intval($_GET['id']); // Sanitize input

    // Prepare and execute the delete query
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo "<script>
                alert('User deleted successfully.');
                window.location.href = 'allusers.php';
              </script>";
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "<script>
            alert('Invalid user ID.');
            window.location.href = 'allusers.php';
          </script>";
    exit();
}

// Close database connection
$conn->close();
?>
