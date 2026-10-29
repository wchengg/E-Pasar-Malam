<?php
// database connection
include_once("database.php");

// Start the session
session_start();
if(!isset($_SESSION['admin_id'])){
    header("location:adminlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $shopname = $_POST["shopname"];
    $shopcategory = $_POST["shopcategory"];
    $shopdescription = $_POST["shopdescription"];
    $shoprating = $_POST["shoprating"];
    $shoppicture = $_POST["shoppicture"];
    $merchantid = $_POST["merchantid"];
    $shoptime = $_POST["shoptime"];
    $agent = $_SESSION['adminid'];

    // Use prepared statements to avoid SQL injection
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM shop WHERE shopname = ?");
    $checkStmt->bind_param("s", $shopname);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo "<script>alert('Shop with this name already exists.');</script>";
    } else {
        // Insert new shop
        $stmt = $conn->prepare("INSERT INTO shop (shopname, shopcategory, shopdescription, shoprating, shoppic, merchant_id, shoptime, agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssis", $shopname, $shopcategory, $shopdescription, $shoprating, $shoppicture, $merchantid, $shoptime, $agent);

        if ($stmt->execute()) {
            header("Location: allshop.php"); // Redirect after successful insertion
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/registershop.css" rel="stylesheet" type="text/css">
    <title>New Shop Registration</title>
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
    
  <!-- Shop Registration Form -->
  <div class="container">
    <div class="reserve_container">
        <div class="Header">
        <h1>New Shop Registration</h1>
        </div>
        <div class="form_container">
        <div class="form_body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label>Shop Name:</label> 
            <input type="text" name="shopname" placeholder="Full name" required>  

            <label for="shopcategory">Shop Category:</label> 
            <select name="shopcategory" required>
                <option value="" disabled selected>Select One</option>
                <option value="Food & Beverages">Food & Beverages</option>
                <option value="Bread & Pastries">Bread & Pastries</option>
                <option value="Fresh Items">Fresh Items</option>
                <option value="Clothing & Accessories">Clothing & Accessories</option>
                <option value="Household Items">Household Items</option>
                <option value="Electronics & Gadgets">Electronics & Gadgets</option>
                <option value="Health & Beauty">Health & Beauty</option>
                <option value="Toys & Hobbies">Toys & Hobbies</option>
                <option value="Books & Stationery">Books & Stationery</option>
                <option value="Flowers & Plants">Flowers & Plants</option>
            </select>  

            <label>Shop Description:</label> 
            <textarea rows="2" cols="40" name="shopdescription" placeholder="Shop Description"></textarea>  

            <label>Shop Rating:</label> 
            <input type="text" name="shoprating" placeholder="Rating from Google" required>  

            <label>Shop Picture URL:</label> 
            <textarea rows="5" cols="60" name="shoppicture" placeholder="Image URL here"></textarea>  

            <label>Merchant Id:</label> 
            <input type="text" name="merchantid" required>  

            <label>Shop Time:</label> 
            <input type="number" name="shoptime" placeholder="Pick up time" required min="0">  

            <div class="last">
                <input type="submit" name="submit" value="Submit" class="submit">
            </div>
            </form>
        </div>
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
