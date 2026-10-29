<?php
session_start();
if (!isset($_SESSION['merchant_id'])) {
    header("location:merchantlogin.php"); // Redirect to the merchant login page
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
    $item_id = intval($_GET['id']); // Sanitize ID input

    // Fetch item details from the database
    $sql = "SELECT * FROM item WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $item_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $shop_id = $row['shop_id'];
        $itempic = $row['itempic'];
        $itemname = $row['itemname'];
        $itemprice = $row['itemprice'];
        $itemcategory = $row['itemcategory'];
        $itemdescription = $row['itemdescription'];
        $sold = $row['sold'];
        $agent = $row['agent'];
        $created_time = $row['created_time']; // Fixed column name
    } else {
        echo "<script>
                alert('Item not found.');
                window.location.href = 'allitem.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Invalid item ID.');
            window.location.href = 'allitem.php';
          </script>";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_id = $_POST['shop_id'];
    $itempic = $_POST['itempic'];
    $itemname = $_POST['itemname'];
    $itemprice = $_POST['itemprice'];
    $itemcategory = $_POST['itemcategory'];
    $itemdescription = $_POST['itemdescription'];
    $sold = $_POST['sold'];
    $agent = $_POST['agent'];

    // Update item data in the database
    $sql = "UPDATE item SET shop_id = ?, itempic = ?, itemname = ?, itemprice = ?, itemcategory = ?, itemdescription = ?, sold = ?, agent = ? WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $shop_id, $itempic, $itemname, $itemprice, $itemcategory, $itemdescription, $sold, $agent, $item_id);

    if ($stmt->execute()) {
        // Close database connection
        $conn->close();
        // Display success message and redirect to allitems.php
        echo "<script>
                alert('Item updated successfully.');
                window.location.href = 'allitem.php';
              </script>";
        exit();
    } else {
        echo "Error updating item: " . $conn->error;
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
    <link href="../Css/merchantedit.css" rel="stylesheet" type="text/css">
    <title>Edit Item Information</title>
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

    <div class="container">
    <div class="back-button">
        <div class="back"><a href="cart.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a></div>
        <div class="button"><h1>Edit Item Information</h1></div>
      </div>
        <form action="" method="post">
            <label for="shop_id">Shop ID:</label>
            <input type="text" id="shop_id" name="shop_id" value="<?php echo htmlspecialchars($shop_id); ?>" required readonly>

            <label for="itempic">Item Picture URL:</label>
            <input type="text" id="itempic" name="itempic" value="<?php echo htmlspecialchars($itempic); ?>" required>

            <label for="itemname">Item Name:</label>
            <input type="text" id="itemname" name="itemname" value="<?php echo htmlspecialchars($itemname); ?>" required>

            <label for="itemprice">Item Price:</label>
            <input type="text" id="itemprice" name="itemprice" value="<?php echo htmlspecialchars($itemprice); ?>" required>

            <label for="itemcategory">Item Category:</label>
            <input type="text" id="itemcategory" name="itemcategory" value="<?php echo htmlspecialchars($itemcategory); ?>" required>

            <label for="itemdescription">Item Description:</label>
            <textarea id="itemdescription" name="itemdescription" required><?php echo htmlspecialchars($itemdescription); ?></textarea>

            <label for="sold">Sold:</label>
            <input type="number" id="sold" name="sold" value="<?php echo htmlspecialchars($sold); ?>" required>

            <input type="hidden" id="agent" name="agent" value="<?php echo htmlspecialchars($agent); ?>">

            <div class="button-container">
                <input type='submit' name='update' value='Update' class='input-submit'>
                <a href='deleteitem.php?id=<?php echo $item_id; ?>' class="delete-btn" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
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
