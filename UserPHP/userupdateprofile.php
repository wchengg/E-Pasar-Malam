<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: userlogin.php");
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user profile data from the database
$sql = "SELECT * FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    // SQL to update profile data
    $update_sql = "UPDATE user SET fullname = ?, username = ?, password = ?, contact = ?, email = ?, dob = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $fullname, $username, $password, $contact, $email, $dob, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully');
        window.location.href = 'userprofile.php';
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
    <link href="../Css/userupdateprofile.css" rel="stylesheet" type="text/css">
    <title>Update Profile</title>
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

    <!-- Profile Page Content -->
    <div class="container">
        <div class="back-button">
            <a href="userprofile.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a>
        </div>
        <div class="profile_container">
            <div class="Header">
                <h1>Update Profile</h1>
            </div>
            <div class="form_container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label>Full Name: </label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

                    <label>Username: </label> 
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    
                    <label for="password">Password:</label>
                    <div class="password-container">
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']);?>" required>
                    <span id="show-icon" class="toggle-password" onclick="togglePassword()">🔒</span>
                    <span id="hide-icon" class="toggle-password" onclick="togglePassword()">🔓</span>
                    </div>

                    <label>Contact Number: </label> 
                    <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" required>

                    <label>Email: </label> 
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                    <label>Date of Birth: </label> 
                    <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>

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
