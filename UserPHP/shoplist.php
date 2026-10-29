<?php
// database connection
include_once("database.php");

// Start the session
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("location:userlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

// Fetch search query
$search_order_id = isset($_POST['search_order_id']) ? $_POST['search_order_id'] : '';

// Prepare SQL query
if ($search_order_id) {
    $sql = "SELECT * FROM shop WHERE shopname LIKE ? ORDER BY shop_id";
    $stmt = $conn->prepare($sql);
    $search_term = "%{$search_order_id}%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM shop ORDER BY shop_id";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../Css/shops.css">
  <title>Stalls</title>
</head>
<body>
  <div class="top-bar box">
    <div class="top-bar left">
      <img src="../Picture/menuBar.svg" class="menuBar svg" alt="Menu" />
    </div>
    <div class="top-bar mid">
      <a href="../UserPHP/shoplist.php" class="logo-link">
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
      <a href="../UserPHP/shoplist.php">- Home</a>
      <a href="userprofile.php">- Profile</a>
      <a href="history.php">- My Orders</a>
    </div>
  </div>

  <!-- Overlay -->
  <div class="overlay"></div>

  <div class="container">
    <!-- Search Form -->
    <div class="search-form">
      <a href="cart.php" class="cart-button">
        <img src="../Picture/cart.png" class="cart" alt="Cart">
      </a>
      <form method="post" action="shoplist.php">
        <input type="text" name="search_order_id" placeholder="Search by Shop Name" value="<?php echo isset($_POST['search_order_id']) ? htmlspecialchars($_POST['search_order_id']) : ''; ?>">
        <button type="submit" class="search-btn">&#128269;</button>
      </form>
    </div>


    <div class="stall-list">
      <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
      ?>
            <div class="stall-card" onclick="window.location.href='stalldetail.php?shopid=<?php echo $row['shop_id']; ?>'">
              <div class="shopimage-container">
                <img src="<?php echo htmlspecialchars($row['shoppic']); ?>" class="shop-image" alt="Shop Image">
              </div>
              <div class="line" style="background-color:#ddd; height:1px; width:100%;"></div>
              <div class="stall-info">
                <h2 class="stall-title"><?php echo htmlspecialchars($row['shopname']); ?></h2>
                <p class="stall-category"><?php echo htmlspecialchars($row['shopcategory']); ?></p>
                <p class="stall-rating">⭐<?php echo htmlspecialchars($row['shoprating']); ?> | Pick up in <?php echo htmlspecialchars($row['shoptime']); ?> mins</p>
              </div>
            </div>
      <?php
          }
        } else {
      ?>
        <h2>Sorry, no stalls found matching your search!</h2>
      <?php
        }
      ?>
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
