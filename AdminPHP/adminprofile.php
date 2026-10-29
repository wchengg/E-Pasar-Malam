<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: adminlogin.php");
    exit();
}

// Fetch admin ID from session
$admin_id = $_SESSION['admin_id'];

// Fetch admin profile data from the database
$sql = "SELECT * FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Close the connection
$conn->close();
?>


<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/adminseeprofile.css" rel="stylesheet" type="text/css">
    <title>Admin Profile</title>
</head>
<body>
    <!-- Top bar -->
    <div class="top-bar box">
        <div class="top-bar left">
            <img src="../Picture/menuBar.svg" class="menuBar svg" alt="Menu" />
        </div>
        <div class="top-bar mid">
          <a href="./seeallorders.php" class="logo-link">
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
            <a href="../AdminPHP/registershop.php">- Register Shop</a>
            <a href="../AdminPHP/registeritem.php">- Register Item</a>
            <a href="../AdminPHP/registeradmin.php">- Register Admin</a>
            <a href="../AdminPHP/allshop.php">- All Shops</a>
            <a href="../AdminPHP/allitem.php">- All Items</a>
            <a href="../AdminPHP/admin.php">- All Admin</a>
            <a href="../AdminPHP/alluser.php">- All User</a>
            <a href="../AdminPHP/seeallorders.php">- All Orders</a>
            <a href="adminprofile.php">- Profile</a>
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
                <p><strong>Full Name: </strong><?php echo htmlspecialchars($admin['fullname']); ?></p>
                <p><strong>Username: </strong><?php echo htmlspecialchars($admin['username']); ?></p>
                <p><strong>Password: </strong>********</p> 
                <p><strong>Contact Number: </strong><?php echo htmlspecialchars($admin['contact']); ?></p>
                <p><strong>Email: </strong><?php echo htmlspecialchars($admin['email']); ?></p>
            </div>
            <div class="button_container">
                <a href="adminupdateprofile.php" class="update_button">Update Profile</a>
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

    function closeMenuOnOutsideClick(event) {
      if (!event.target.closest('#sideMenu') && !event.target.closest('.menuBar') && !event.target.closest('.overlay')) {
        closeMenu();
      }
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

    document.addEventListener('click', closeMenuOnOutsideClick);
  });
</script>
</body>
</html>
