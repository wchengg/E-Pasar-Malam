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

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Ideally hashed in a real-world scenario
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    // SQL to update profile data
    $update_sql = "UPDATE admin SET fullname = ?, username = ?, password = ?, contact = ?, email = ? WHERE admin_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $fullname, $username, $password, $contact, $email, $admin_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully');
        window.location.href = 'adminprofile.php';
        </script>";
    } else {
        echo "Error: " . $update_stmt->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/adminupdateprofile.css" rel="stylesheet" type="text/css">
    <title>Update Profile</title>
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
        <div class="back-button">
            <a href="adminprofile.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a>
        </div>
        <div class="profile_container">
            <div class="Header">
                <h1>Update Profile</h1>
            </div>
            <div class="form_container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label>Full Name: </label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($admin['fullname']); ?>" required>

                    <label>Username: </label> 
                    <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>

                    <label for="password">Password:</label>
                    <div class="password-container">
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($admin['password']);?>" required>
                    <span id="show-icon" class="toggle-password" onclick="togglePassword()">🔒</span>
                    <span id="hide-icon" class="toggle-password" onclick="togglePassword()">🔓</span>
                    </div>

                    <label>Contact Number: </label> 
                    <input type="text" name="contact" value="<?php echo htmlspecialchars($admin['contact']); ?>" required>

                    <label>Email: </label> 
                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

                    <input type="submit" name="submit" value="Update Profile" class="submit">
                </form>
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

  function togglePassword() {
        const passwordInput = document.getElementById('password');
        const showIcon = document.getElementById('show-icon');
        const hideIcon = document.getElementById('hide-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showIcon.style.display = 'none';
            hideIcon.style.display = 'inline';
        } else {
            passwordInput.type = 'password';
            showIcon.style.display = 'inline';
            hideIcon.style.display = 'none';
        }
    }
</script>
</body>
</html>
