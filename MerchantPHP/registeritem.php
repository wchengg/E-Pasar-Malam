<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['merchant_id'])) {
    header("location: merchantlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

// Fetch merchant ID from session
$merchant_id = $_SESSION['merchant_id']; // Using merchant_id from session

// Fetch shop_id associated with the merchant_id
$shop_id_query = "SELECT shop_id FROM shop WHERE merchant_id = ?";
$stmt = $conn->prepare($shop_id_query);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $merchant_id);
$stmt->execute();
$stmt->bind_result($shop_id);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $itempic = $_POST["itempicture"];
    $itemname = $_POST["itemname"];
    $itemprice = $_POST["itemprice"];
    $itemcategory = $_POST["itemcategory"];
    $itemdesc = $_POST["itemdesc"];
    $sold = $_POST["sold"];
    $agent = "M" . $merchant_id; // Concatenate "M" with merchant_id

    // Prepare and bind SQL statement
    $sql = "INSERT INTO item (shop_id, itempic, itemname, itemprice, itemcategory, itemdescription, sold, agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("isssssis", $shop_id, $itempic, $itemname, $itemprice, $itemcategory, $itemdesc, $sold, $agent);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Display success message and redirect
        echo "<script>
                alert('Record inserted successfully');
                window.location.href = 'allitem.php'; // Redirect to allitem.php
              </script>";
        exit(); // Ensure that no further output is sent
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>


<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/merchantregisteritem.css" rel="stylesheet" type="text/css">
    <title>New Item Registration</title>
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
                        <label>Item Picture URL: </label> 
                        <textarea rows="1" cols="80" name="itempicture" placeholder="Image URL here" required></textarea>  

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
                        <input type="text" id="input" name="itemdesc"  placeholder="Item description" required>  

                        <label>Item Sold: </label> 
                        <input type="text" id="input" name="sold"  placeholder="Sold" required>  
                        
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
