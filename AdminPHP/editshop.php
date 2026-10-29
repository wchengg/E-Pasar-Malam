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

    // Fetch shop details from the database
    $sql = "SELECT * FROM shop WHERE shop_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shopname = $row['shopname'];
        $shopcategory = $row['shopcategory'];
        $shopdescription = $row['shopdescription'];
        $shoprating = $row['shoprating'];
        $shoppic = $row['shoppic'];
        $merchant_id = $row['merchant_id'];
        $shoptime = $row['shoptime'];
        $agent = $row['agent'];
    } else {
        echo "<script>
                alert('Shop not found.');
                window.location.href = 'allshop.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Invalid shop ID.');
            window.location.href = 'allshop.php';
          </script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shopname = $_POST['shopname'];
    $shopcategory = $_POST['shopcategory'];
    $shopdescription = $_POST['shopdescription'];
    $shoprating = $_POST['shoprating'];
    $shoppic = $_POST['shoppic'];
    $merchant_id = $_POST['merchant_id'];
    $shoptime = $_POST['shoptime'];
    $agent = $_POST['agent'];

    // Update shop data in the database
    $sql = "UPDATE shop SET shopname = ?, shopcategory = ?, shopdescription = ?, shoprating = ?, shoppic = ?, merchant_id = ?, shoptime = ?, agent = ? WHERE shop_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $shopname, $shopcategory, $shopdescription, $shoprating, $shoppic, $merchant_id, $shoptime, $agent, $shop_id);

    if ($stmt->execute()) {
        // Close database connection
        $conn->close();
        // Display success message and redirect to allshop.php
        echo "<script>
                alert('Shop updated successfully.');
                window.location.href = 'allshop.php';
              </script>";
        exit();
    } else {
        echo "Error updating shop: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/edit.css" rel="stylesheet" type="text/css">
    <title>Edit Shop Information</title>
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

    <div class="container">
        <!-- Back Button -->
        <div class="back-button">
            <a href="../AdminPHP/allshop.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon"></a>
        </div>
        <h1>Edit Shop Information</h1>
        <form action="" method="post">
            <label for="shopname">Shop Name:</label>
            <input type="text" id="shopname" name="shopname" value="<?php echo htmlspecialchars($shopname); ?>" required>
            
            <label for="shopcategory">Shop Category:</label>
            <input type="text" id="shopcategory" name="shopcategory" value="<?php echo htmlspecialchars($shopcategory); ?>" required>
            
            <label for="shopdescription">Description:</label>
            <textarea rows="2" cols="40" name="shopdescription" required><?php echo htmlspecialchars($shopdescription); ?></textarea>
            
            <label for="shoprating">Rating:</label>
            <input type="text" id="shoprating" name="shoprating" value="<?php echo htmlspecialchars($shoprating); ?>" required>
            
            <label for="shoppic">Picture URL:</label>
            <input type="text" id="shoppic" name="shoppic" value="<?php echo htmlspecialchars($shoppic); ?>" required>
            
            <label for="merchant_id">Merchant ID:</label>
            <input type="text" id="merchant_id" name="merchant_id" value="<?php echo htmlspecialchars($merchant_id); ?>" required>
            
            <label for="shoptime">Shop Time:</label>
            <input type="text" id="shoptime" name="shoptime" value="<?php echo htmlspecialchars($shoptime); ?>" required>
            
            <input type="hidden" id="agent" name="agent" value="<?php echo htmlspecialchars($agent); ?>">
            
            <div class="button-container">
                <input type='submit' name='update' value='Update' class='input-submit'>
                <a href='delete.php?id=<?php echo $shop_id; ?>' class="delete-btn" onclick="return confirm('Are you sure you want to delete this shop?')">Delete</a>
            </div>
        </form>
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
