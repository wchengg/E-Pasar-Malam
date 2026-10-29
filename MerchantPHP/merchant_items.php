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
$merchant_id = $_SESSION['merchant_id'];

// Fetch item data using merchant_id as shop_id
$sql = "SELECT * FROM item WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $merchant_id); // Bind the merchant_id parameter
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Css/merchantitem.css">
    <title>Merchant Products</title>
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

    <div class="content">

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <!-- Shop Details -->
                <?php
                // Fetch shop details for the logged-in merchant
                $sql = "SELECT * FROM shop WHERE shop_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $merchant_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                ?>
                <div class="product-header">
                    <img src="<?php echo htmlspecialchars($row['shoppic']); ?>" alt="Shop Image" class="header-image">
                    <div class="product-details">
                        <h1><?php echo htmlspecialchars($row['shopname']); ?></h1>
                        <p class="category"><?php echo htmlspecialchars($row['shopcategory']); ?></p>
                        <p class="rating">⭐<?php echo htmlspecialchars($row['shoprating']); ?></p>
                        <p class="arrival-time">Pick up in <?php echo htmlspecialchars($row['shoptime']); ?> mins</p>
                    </div>
                </div>
                <?php
                } else {
                    echo "<h2>No data</h2>";
                }
                ?>

                <!-- Products -->
                <div class="products">
                    <?php
                    // Fetch items for the logged-in merchant's shop
                    $sql = "SELECT * FROM item WHERE shop_id = ? ORDER BY item_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $merchant_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <div class="product-item">
                                <img src="<?php echo htmlspecialchars($row['itempic']); ?>" alt="Product Image" class="product-image">
                                <div class="product-info">
                                    <h2><?php echo htmlspecialchars($row['itemname']); ?></h2>
                                    <p><?php echo htmlspecialchars($row['sold']); ?> sold</p>
                                    <p class="price">RM<?php echo htmlspecialchars($row['itemprice']); ?></p>
                                </div>
                                <div class="cart-controls">
                                    <button class="edit-item" onclick="openEditPopup(this, '<?php echo $row['itempic'];?>', '<?php echo $row['itemname'];?>', '<?php echo $row['itemprice'];?>', '<?php echo $row['sold'];?>' , '<?php echo $row['item_id'];?>')">Edit</button>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                    ?>
                        <h2>Sorry, no products from this shop.</h2>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Edit Item Popup -->
            <div id="editPopup" class="popup">
                <div class="popup-content">
                    <span class="close-btn" onclick="closeEditPopup()">&times;</span>
                    <h2 class="title">Edit Item</h2>
                    <form id="editForm">
                        <img id="popup-image" src="" alt="Product Image" class="popup-image">
                        <div class="form-group">
                            <label for="popup-title">Title:</label>
                            <input type="text" id="popup-title" required>
                        </div>
                        <div class="form-group">
                            <label for="popup-price">Price (RM):</label>
                            <input type="number" id="popup-price" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="popup-sold">Sold:</label>
                            <input type="number" id="popup-sold" min="0" required>
                        </div>
                        <input type="hidden" id="popup-item-id">
                        <button type="button" onclick="updateItem()">Update Item</button>
                    </form>
                </div>
            </div>



            <script>
                // Item variables
                let currentPrice = 0;
                let currentButton = null;
                let currentImage = '';
                let currentTitle = '';
                let currentItemId = '';
                let currentSold = 0;

                function openEditPopup(button, image, title, price, sold, itemId) {
                    currentButton = button;
                    currentPrice = price;
                    currentImage = image;
                    currentTitle = title;
                    currentItemId = itemId; // Store item_id
                    currentSold = sold; // Store sold quantity
                    document.getElementById('popup-image').src = image;
                    document.getElementById('popup-title').value = title;
                    document.getElementById('popup-price').value = price;
                    document.getElementById('popup-sold').value = sold; // Fix: Use value instead of innerText
                    document.getElementById('popup-item-id').value = itemId; // Set item_id in hidden field
                    document.getElementById('editPopup').style.display = 'flex';
                    console.log(currentSold);
                    console.log(currentItemId);
                }

                function closeEditPopup() {
                    document.getElementById('editPopup').style.display = 'none';
                }

                function updateItem() {
                    closeEditPopup();
                    const title = document.getElementById('popup-title').value;
                    const price = parseFloat(document.getElementById('popup-price').value);
                    const sold = parseInt(document.getElementById('popup-sold').value);
                    const itemId = document.getElementById('popup-item-id').value;

                    const xhr = new XMLHttpRequest(); // Initialize XMLHttpRequest object
                    xhr.open("POST", "update_item.php", true); // Set method, URL, and async flag
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // Set content type

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            alert('Item updated successfully: ' + xhr.responseText);
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Error updating item: ' + xhr.responseText);
                        }
                    };

                    xhr.send(`item_id=${encodeURIComponent(itemId)}&title=${encodeURIComponent(title)}&price=${encodeURIComponent(price)}&sold=${encodeURIComponent(sold)}`);
                }



                document.querySelector('.menuBar').addEventListener('click', function () {
                    document.getElementById('sideMenu').style.width = '250px';
                    document.querySelector('.overlay').style.display = 'block';
                });

                document.querySelector('.close-btn').addEventListener('click', function () {
                    document.getElementById('sideMenu').style.width = '0';
                    document.querySelector('.overlay').style.display = 'none';
                });

                document.querySelector('.overlay').addEventListener('click', function () {
                    document.getElementById('sideMenu').style.width = '0';
                    document.querySelector('.overlay').style.display = 'none';
                });
            </script>
        </div>
    </div>
</body>
</html>
