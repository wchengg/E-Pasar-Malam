<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location:adminlogin.php");
    exit(); // Ensure no further code is executed after redirect
}

// Check if 'id' is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $admin_id = intval($_GET['id']); // Sanitize input

    // Fetch admin details from the database
    $sql = "SELECT * FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $admin_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullname = $row['fullname'];
        $contact = $row['contact'];
        $email = $row['email'];
        $username = $row['username'];
        $password = $row['password'];
        $agent = $row['agent'];
    } else {
        echo "<script>
                alert('Admin not found.');
                window.location.href = 'admin.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Invalid admin ID.');
            window.location.href = 'admin.php';
          </script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $agent = $_POST['agent'];

    // Update admin data in the database
    $sql = "UPDATE admin SET fullname = ?, contact = ?, email = ?, username = ?, password = ?, agent = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $fullname, $contact, $email, $username, $password, $agent, $admin_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Admin updated successfully.');
                window.location.href = 'admin.php';
              </script>";
        exit();
    } else {
        echo "Error updating admin: " . $conn->error;
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
    <title>Edit Admin Information</title>
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

    <!-- Admin Edit Form -->
    <div class="container">
        <div class="back-button">
            <a href="admin.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon"></a>
        </div>
        <h1>Edit Admin Information</h1>
        <form action="" method="post">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password);?>" required>
                <span id="show-icon" class="toggle-password" onclick="togglePassword()">🔒</span>
                <span id="hide-icon" class="toggle-password" onclick="togglePassword()">🔓</span>
            </div>

            <label for="agent">Agent:</label>
            <input type="text" id="agent" name="agent" value="<?php echo htmlspecialchars($agent); ?>">

            <div class="button-container">
                <input type='submit' name='update' value='Update' class='input-submit'>
                <a href='deleteadmin.php?id=<?php echo $item_id; ?>' class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
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
