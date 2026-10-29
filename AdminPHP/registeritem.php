<?php
// database connection
  include_once("database.php");
  // Start the session
  session_start();
    if(!isset($_SESSION['admin_id'])){
        header("location:adminlogin.php");
    }
?>


<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/registershop.css" rel="stylesheet" type="text/css">
    <title>New Item Registration</title>
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

  <!-- Item Registration Form -->
  <div class="container">
        <div class="reserve_container">
            <div class="Header">
                <div class="head">
                    <h1>New Item Registration</h1>
                </div>
            </div>
            <div class="form_container">
                <div class="form_body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <label>Shop Id: </label>
                        <input type="number" id="input" name="shopid" required>

                        <label>Item Picture URL: </label>
                        <textarea rows="1" cols="80" name="itempicture" placeholder="Image URL here" ></textarea>

                        <label>Item Name: </label>
                        <input type="text" id="input" name="itemname"  placeholder="Item name"required>

                        <label>Item Price: </label>
                        <input type="text" id="input" name="itemprice"  placeholder="6.00" required>

                        <label for="itemcategory">Item Category:</label>
                        <select name="itemcategory" required>
                            <option value="" disabled selected>Select One</option>
                            <option value="Food & Beverages">Food & Beverages</option>
                            <option value="Bread & Pastries">Bread & Pastries</option>
                            <option value="Fresh Items">Fresh Items (e.g., Fruits, Vegetables, Meat, Seafood)</option>
                            <option value="Clothing & Accessories">Clothing & Accessories</option>
                            <option value="Household Items">Household Items</option>
                            <option value="Electronics & Gadgets">Electronics & Gadgets</option>
                            <option value="Health & Beauty">Health & Beauty</option>
                            <option value="Toys & Hobbies">Toys & Hobbies</option>
                            <option value="Books & Stationery">Books & Stationery</option>
                            <option value="Flowers & Plants">Flowers & Plants</option>
                        </select>

                        <label>Item Description: </label>
                        <input type="text" id="input" name="itemdesc"  placeholder="Item description"required>



        <!-- Add the momment we hard code key in 'sold' first, later see how to do -->

                        <label>Item sold: </label>
                        <input type="text" id="input" name="sold"  placeholder="Sold" required>
                        
                        <div class="last">
                            <input type="submit" name="submit" value="Submit" class="submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
  </div>

  <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $shopid = $_POST["shopid"];
    $itempic = $_POST["itempicture"];
    $itemname = $_POST["itemname"];
    $itemprice = $_POST["itemprice"];
    $itemcategory = $_POST["itemcategory"];
    $itemdesc = $_POST["itemdescription"];
    $sold = $_POST["sold"];
    $agent = $_SESSION['admin_id'];

    // SQL to insert data
    $sql = "INSERT INTO item (shop_id, itempic, itemname, itemprice, itemcategory, itemdescription, sold, agent)
            VALUES ('$shopid', '$itempic', '$itemname', '$itemprice', '$itemcategory', '$itemdesc', '$sold', '$agent')";

    if ($conn->query($sql) === TRUE) {
        // Display success message
        echo "<script>
                alert('Record inserted successfully');
                window.location.href = 'allitem.php'; // Redirect to allitem.php
              </script>";
        exit(); // Ensure that no further output is sent
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>


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
