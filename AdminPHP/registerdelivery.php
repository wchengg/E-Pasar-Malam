<?php
// database connection
include_once("database.php");
// Start the session
session_start();
if(!isset($_SESSION['admin_id'])){
    header("location:adminlogin.php");
}

// Handle delivery registration
if (isset($_POST['delivery_submit'])) {
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $agent = $_SESSION['admin_id'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        // Check if delivery name already exists
        $sql = "SELECT * FROM deliveryman WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<script>alert('Delivery name already exists. Please choose a different name.');</script>";
        } else {
            // Insert new delivery
            $hashed_password = password_hash($delivery_password, PASSWORD_DEFAULT); // Hash the password

            $sql = "INSERT INTO deliveryman (fullname, contact, email, username, password, agent) 
                    VALUES ('$fullname', '$contact', '$email', '$username', '$password', '$agent')";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['registration_success'] = true;
                echo "<script>
                        alert('Registered successfully! Please ask deliveryman to login.');
                        window.location.href = 'registerdelivery.php';
                      </script>";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deliveryman Signup</title>
    <link rel="stylesheet" type="text/css" href="../Css/adminsignup.css">
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

    <div class="content">
        <h1>New Deliveryman Signup</h1>
        <form action="registerdelivery.php" method="POST">
            <div class="container">
                <label for="delivery_name"><b>Fullname:</b></label>
                <input type="text" placeholder="Enter Name" name="fullname" required>

                <label for="delivery_contact"><b>Contact No.:</b></label>
                <input type="tel" placeholder="Enter Contact Number" name="contact" required>

                <label for="delivery_email"><b>Email:</b></label>
                <input type="email" placeholder="Enter Email" name="email" required>

                <label for="delivery_name"><b>Username:</b></label>
                <input type="text" placeholder="Enter Name" name="username" required>

                <label for="delivery_password"><b>Password:</b></label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <label for="confirm_delivery_password"><b>Confirm Password:</b></label>
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>

                <button class="submit" name="delivery_submit" type="submit">Signup</button>
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
