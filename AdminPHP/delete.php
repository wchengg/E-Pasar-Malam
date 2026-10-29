<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location:adminlogin.php");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pasarmalam";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $shop_id = intval($_GET['id']); // Sanitize ID input

    // Prepare and execute SQL DELETE query
    $sql = "DELETE FROM shop WHERE shop_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shop_id);

    if ($stmt->execute()) {
        // Close database connection
        $conn->close();
        // Display success message and redirect to allshop.php
        echo "<script>
                alert('Shop deleted successfully.');
                window.location.href = 'allshop.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error deleting shop: " . $conn->error . "');
                window.location.href = 'allshop.php';
              </script>";
    }
} else {
    // Redirect user if shop ID is not provided in the URL
    echo "<script>
            alert('Invalid shop ID.');
            window.location.href = 'allshop.php';
          </script>";
    exit();
}

// Close database connection
$conn->close();
?>
