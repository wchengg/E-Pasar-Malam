<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location:adminlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

// Fetch item data from the database
$sql = "SELECT * FROM item"; // Modify if needed
$result = $conn->query($sql);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/allshop.css" rel="stylesheet" type="text/css">
    <title>Item Information</title>
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

    <!-- Item Information Table -->
    <div class="container">
        <h1>All Items</h1>
        <div class="table_wrapper"> <!-- New wrapper for the table -->
            <table style="width:100%">
                <tr>
                    <th>Item ID</th>
                    <th>Shop ID</th>
                    <th>Item Picture</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Item Category</th>
                    <th>Item Description</th>
                    <th>Sold</th>
                    <th>Created Time</th>
                    <th>Agent</th>
                    <th>Actions</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["item_id"] . "</td>";
                        echo "<td>" . $row["shop_id"] . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row["itempic"]) . "' alt='Item Picture' width='80' height='60'></td>";
                        echo "<td>" . $row["itemname"] . "</td>";
                        echo "<td>" . $row["itemprice"] . "</td>";
                        echo "<td>" . $row["itemcategory"] . "</td>";
                        echo "<td>" . $row["itemdescription"] . "</td>";
                        echo "<td>" . $row["sold"] . "</td>";
                        echo "<td>" . $row["created time"] . "</td>"; // Fixed column name
                        echo "<td>" . $row["agent"] . "</td>";
                        echo "<td><a href='edititem.php?id=" . htmlspecialchars($row["item_id"]) . "'>Edit</a> </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No items found</td></tr>";
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
// Close the connection
$conn->close();
?>
