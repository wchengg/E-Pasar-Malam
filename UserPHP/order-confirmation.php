<?php
// database connection
include_once("database.php");
 // Start the session
 session_start();
 // Check login (change id to userid/adminid/merchantid)
 if(!isset($_SESSION['user_id'])){
   header("location:userlogin.php");
   exit(); // Ensure no further code is executed after redirect
 }
?>

<?php
  if(isset($_SESSION['user_id'])){
    $user_id=($_SESSION['user_id']);
    $sql = "SELECT order_id FROM orders WHERE user_id = $user_id ORDER BY order_date DESC LIMIT 1";
    $result = mysqli_query($conn,$sql);
    //fetch data
    $row = mysqli_fetch_array($result);
  } else{
    echo "<h2>No data</h2>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../Css/confirmation.css">
  <title>Order Confirmation</title>
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
    <h1>Order Confirmed</h1>
    <p>Thank you for your order! Your order number is <strong>#<?php echo $row['order_id'];?></strong>.</p>
    <p>You can pick up your order at the selected time.</p>
    <button onclick="goToOrderHistory()">View Order History</button>
  </div>

  <script>
    function goToOrderHistory() {
      window.location.href = 'history.php';
    }


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
