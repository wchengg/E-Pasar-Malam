<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['merchant_id'])) {
    header("location: merchantlogin.php");
    exit();
}

// Fetch merchant ID from session
$merchant_id = $_SESSION['merchant_id'];

// Fetch merchant profile data from the database
$sql = "SELECT * FROM merchant WHERE merchant_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $merchant_id);
$stmt->execute();
$result = $stmt->get_result();
$merchant = $result->fetch_assoc();

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $businesslicenseno = $_POST['businesslicenseno'];
    $shopname = $_POST['shopname'];
    $shopcategory1 = $_POST['shopcategory1'];
    $shopcategory2 = $_POST['shopcategory2'];

    // SQL to update profile data
    $update_sql = "UPDATE merchant SET fullname = ?, username = ?, password = ?, contact = ?, email = ?, businesslicenseno = ?, shopname = ?, shopcategory1 = ?, shopcategory2 = ? WHERE merchant_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssssi", $fullname, $username, $password, $contact, $email, $businesslicenseno, $shopname, $shopcategory1, $shopcategory2, $merchant_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully');
        window.location.href = 'myprofile.php';
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
    <link href="../Css/merchantupdateprofile.css" rel="stylesheet" type="text/css">
    <title>Update Profile</title>
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

    <!-- Profile Page Content -->
    <div class="container">
         <div class="back-button">
            <a href="myprofile.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon"></a>
        </div>
        <div class="profile_container">
            <div class="Header">
                <h1>Update Profile</h1>
            </div>
            <div class="form_container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label>Full Name: </label>
                    <input type="text" name="fullname" value="<?php echo htmlspecialchars($merchant['fullname']); ?>" required>

                    <label>Username: </label> 
                    <input type="text" name="username" value="<?php echo htmlspecialchars($merchant['username']); ?>" required>

                    <label for="password">Password:</label>
                    <div class="password-container">
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($merchant['password']); ?>" required>
                    <span id="show-icon" class="toggle-password" onclick="togglePassword()">🔒</span>
                    <span id="hide-icon" class="toggle-password" onclick="togglePassword()">🔓</span>
                    </div>

                    <label>Contact Number: </label> 
                    <input type="text" name="contact" value="<?php echo htmlspecialchars($merchant['contact']); ?>" required>

                    <label>Email: </label> 
                    <input type="email" name="email" value="<?php echo htmlspecialchars($merchant['email']); ?>" required>

                    <label>Business License No: </label> 
                    <input type="text" name="businesslicenseno" value="<?php echo htmlspecialchars($merchant['businesslicenseno']); ?>" required>

                    <label>Shop Name: </label> 
                    <input type="text" name="shopname" value="<?php echo htmlspecialchars($merchant['shopname']); ?>" required>

                    <label>Shop Category 1: </label> 
                    <input type="text" name="shopcategory1" value="<?php echo htmlspecialchars($merchant['shopcategory1']); ?>" required>

                    <label>Shop Category 2: </label> 
                    <input type="text" name="shopcategory2" value="<?php echo htmlspecialchars($merchant['shopcategory2']); ?>" >

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
