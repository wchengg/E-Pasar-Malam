<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['merchant_id'])) {
    header("location: merchantlogin.php");
    exit();
}

// Fetch merchant ID from session
$merchant_id = $_SESSION['merchant_id'];

// Fetch merchant profile data from the database
$sql = "SELECT * FROM merchant WHERE merchant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $merchant_id);
$stmt->execute();
$result = $stmt->get_result();
$merchant = $result->fetch_assoc();

// Close the connection
$conn->close();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/merchantseeprofile.css" rel="stylesheet" type="text/css">
    <title>View Profile</title>
</head>
<body>
    <!-- Top bar -->
    <div class="top-bar box">
        <div class="top-bar left">
            <img src="../Picture/menuBar.svg" class="menuBar svg" alt="Menu" />
        </div>
        <div class="top-bar mid">
            <a href="../MerchantPHP/orders.php" class="logo-link">
                <img src="../Picture/logo2.png" class="logo image" alt="Logo">
            </a>
        </div>
        <div class="top-bar right">
            <a href="logout.php" class="logout">
            <img src="../Picture/logout.svg" class="profile svg" alt="Logout">
            </a>
        </div>
    </div>

    <!-- Side Menu -->
    <div id="sideMenu" class="side-menu">
        <div class="menu-header">
            <span class="close-btn">&times;</span>
            <h2>Menu</h2>
        </div>
        <div class="menu-content">
            <a href="../MerchantPHP/registeritem.php">- Register Item</a>
            <a href="merchant_items.php">- My Shop Items</a>
            <a href="../MerchantPHP/myprofile.php">- Profile</a>
            <a href="../MerchantPHP/orders.php">- All Orders</a>
        </div>
    </div>


    <!-- Overlay -->
    <div class="overlay"></div>
    
    <!-- Profile Page Content -->
    <div class="container">
        <div class="profile_container">
            <div class="Header">
                <h1>My Profile</h1>
            </div>
            <div class="info_container">
                <p><strong>Full Name: </strong><?php echo htmlspecialchars($merchant['fullname']); ?></p>
                <p><strong>Username: </strong><?php echo htmlspecialchars($merchant['username']); ?></p>
                <p><strong>Password: </strong>********</p> 
                <p><strong>Contact Number: </strong><?php echo htmlspecialchars($merchant['contact']); ?></p>
                <p><strong>Email: </strong><?php echo htmlspecialchars($merchant['email']); ?></p>
                <p><strong>Business License No: </strong><?php echo htmlspecialchars($merchant['businesslicenseno']); ?></p>
                <p><strong>Shop Name: </strong><?php echo htmlspecialchars($merchant['shopname']); ?></p>
                <p><strong>Shop Category 1: </strong><?php echo htmlspecialchars($merchant['shopcategory1']); ?></p>
                <p><strong>Shop Category 2: </strong><?php echo htmlspecialchars($merchant['shopcategory2']); ?></p>
            </div>
            <div class="button_container">
                <a href="merchantprofile.php" class="update_button">Update Profile</a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        const sideMenu = document.getElementById("sideMenu");
        const overlay = document.querySelector('.overlay');
        const container = document.querySelector('.container');
        const menuBar = document.querySelector('.menuBar');
        const closeBtn = document.querySelector('.close-btn');

        function openMenu() {
            sideMenu.style.width = "250px";
            overlay.style.display = "block";
            container.classList.add('transparent');
            document.body.style.overflow = 'hidden'; 
        }

        function closeMenu() {
            sideMenu.style.width = "0";
            overlay.style.display = "none";
            container.classList.remove('transparent');
            document.body.style.overflow = ''; 
        }

        if (menuBar) {
            menuBar.addEventListener('click', openMenu);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeMenu);
        }

        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }
    });
    </script>
</body>
</html>
