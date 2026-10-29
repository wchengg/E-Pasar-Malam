<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location:adminlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

// Fetch shop data from the database
$sql = "SELECT * FROM shop"; // Modify if needed
$result = $conn->query($sql);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/allshop.css" rel="stylesheet" type="text/css">
    <title>Shop Information</title>
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
    
        <!-- Updated Container with Table -->
        <div class="container">
            <h1>Shop Information</h1>
            <div class="table_wrapper"> <!-- New wrapper for the table -->
                    <table style="width:100%">
                        <tr>
                            <th>Shop ID</th>
                            <th>Shop Name</th>
                            <th>Shop Category</th>
                            <th>Item Description</th>
                            <th>Rating</th>
                            <th>Picture</th>
                            <th>Merchant ID</th>
                            <th>Shop Time</th>
                            <th>Created Time</th>
                            <th>Agent</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["shop_id"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["shopname"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["shopcategory"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["shopdescription"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["shoprating"]) . "</td>";
                                echo "<td><img src='" . htmlspecialchars($row["shoppic"]) . "' alt='Shop Picture' width='80' height='60'></td>";
                                echo "<td>" . htmlspecialchars($row["merchant_id"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["shoptime"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["created time"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["agent"]) . "</td>";
                                echo "<td><a href='editshop.php?id=" . htmlspecialchars($row["shop_id"]) . "'>Edit</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11'>No shops found.</td></tr>";
                        }
                        ?>
                    </table>
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

<?php
// Close the database connection
$conn->close();
?>
