<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location:adminlogin.php");
    exit();
}

// Database connection
include_once("database.php");

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']); // Sanitize item_id

    $sql = "DELETE FROM item WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Record deleted successfully');
                window.location.href = 'allitem.php'; // Redirect to allitem.php
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close database connection
    $stmt->close();
    $conn->close();
} else {
    die("Item ID not provided.");
}
?>
