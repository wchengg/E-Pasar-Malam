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

// Initialize variables
$orders = [];
$search_order_id = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_order_id = $_POST['search_order_id'];

    // Fetch orders based on search query
    if (!empty($search_order_id)) {
        $sql_orders = "SELECT o.order_id, o.pickup_time, o.order_date, o.status AS order_status,
        i.item_id, i.itemname AS title, oi.quantity, oi.remarks, oi.order_item_id, oi.image, oi.price, oi.status AS item_status
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN item i ON oi.item_id = i.item_id
        WHERE i.shop_id = ?" . (isset($search_order_id) && !empty($search_order_id) ? " AND o.order_id = ?" : "") . "
        ORDER BY o.order_id DESC";


        $stmt = $conn->prepare($sql_orders);
        $stmt->bind_param("ii", $merchant_id, $search_order_id);
        $stmt->execute();
        $result_orders = $stmt->get_result();

        // Group items by order and calculate totals dynamically
        while ($row = $result_orders->fetch_assoc()) {
            $order_id = $row['order_id'];
            if (!isset($orders[$order_id])) {
                $orders[$order_id] = [
                    'order_id' => $row['order_id'],
                    'pickup_time' => $row['pickup_time'],
                    'order_date' => $row['order_date'],
                    'order_status' => $row['order_status'],
                    'total' => 0, // Initialize total to 0
                    'items' => []
                ];
            }
        
            // Add item price to the total
            $orders[$order_id]['total'] += $row['price'] * $row['quantity'];
        
            // Add item details to the order
            $orders[$order_id]['items'][] = [
                'item_id' => $row['item_id'],
                'title' => $row['title'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'remarks' => $row['remarks'],
                'order_item_id' => $row['order_item_id'],
                'status' => $row['item_status'], // Correctly set item status
                'image' => $row['image']
            ];
        }
        
    }
} else {
    // Fetch all orders if no search query is present
    $sql_orders = "SELECT o.order_id, o.pickup_time, o.order_date, o.status AS order_status,
                          i.item_id, i.itemname AS title, oi.quantity, oi.remarks, oi.order_item_id, oi.image, oi.price, oi.status AS item_status
                   FROM orders o
                   JOIN order_items oi ON o.order_id = oi.order_id
                   JOIN item i ON oi.item_id = i.item_id
                   WHERE i.shop_id = ?
                   ORDER BY o.order_id DESC";

    $stmt = $conn->prepare($sql_orders);
    $stmt->bind_param("i", $merchant_id);
    $stmt->execute();
    $result_orders = $stmt->get_result();

    // Group items by order and calculate totals dynamically
    while ($row = $result_orders->fetch_assoc()) {
        $order_id = $row['order_id'];
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = [
                'order_id' => $row['order_id'],
                'pickup_time' => $row['pickup_time'],
                'order_date' => $row['order_date'],
                'order_status' => $row['order_status'],
                'total' => 0, // Initialize total to 0
                'items' => []
            ];
        }

        // Add item price to the total
        $orders[$order_id]['total'] += $row['price'] * $row['quantity'];

        // Add item details to the order
        $orders[$order_id]['items'][] = [
            'item_id' => $row['item_id'],
            'title' => $row['title'],
            'quantity' => $row['quantity'],
            'price' => $row['price'],
            'remarks' => $row['remarks'],
            'order_item_id' => $row['order_item_id'],
            'status' => $row['item_status'], // Correctly set item status
            'image' => $row['image']
        ];
    }
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['order_item_id']) && !empty($_POST['status'])) {
    $order_item_id = $_POST['order_item_id'];
    $new_status = $_POST['status'];

    // Update the 'Status' column in the database
    $update_sql = "UPDATE order_items SET status = ? WHERE order_item_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $order_item_id);
    $update_stmt->execute();

    header("Location: orders.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Css/merchantorders.css">
    <title>All Orders</title>
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

<!-- Search Form -->
<div class="search-form">
    <form method="post" action="orders.php">
        <input type="text" name="search_order_id" placeholder="Search by Order ID">
        <button type="submit" class="search-btn">&#128269;</button>
    </form>
</div>

<!-- Back Button -->
<div class="container">
    <div class="back-button">
      <div class="button"><h1>All Orders</h1></div>
    </div>

    <!-- Order History List -->
    <div class="order-history-list">
        <?php if (empty($orders)) { ?>
            <p class="no-orders">No Orders Found</p>
        <?php } else { ?>
            <?php foreach ($orders as $order) { ?>
                <div class="order-card">
                <div class="order-header">
                    <h2>Order ID: #<?php echo $order['order_id']; ?></h2>
                    <p><strong>Delivery Status:</strong> <?php echo $order['order_status']; ?></p> <!-- New status here -->
                    <p><strong>Pickup Time:</strong> <?php echo $order['pickup_time']; ?></p>
                    <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
                </div>



                <ul class="order-items">
                <?php foreach ($order['items'] as $item) { ?>
                      <li class="order-item <?php echo ($item['status'] == 'Declined' || $item['status'] == 'Completed') ? 'disabled' : ''; ?>">
                        <div class="item-details">
                            <div class="item-image-container">
                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" class="item-image">
                            </div>
                            <div class="item-text-details">
                            <div class="box">
                                <span class="item-title"><?php echo $item['title']; ?></span>
                                <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                            </div>
                                <div class="price"><span class="item-price">RM <?php echo $item['price']; ?></span></div>
                                <?php if (!empty($item['remarks'])): ?>
                                    <span class="item-remarks">Remarks: <?php echo $item['remarks']; ?></span><br>
                                <?php endif; ?>
                                <span class="item-status <?php echo ($item['status'] == 'Decline') ? 'item-status-decline' : (($item['status'] == 'Order Ready') ? 'item-status-ready' : ''); ?>">
                                    Status: <?php echo $item['status']; ?>
                                </span>

                            </div>
                        </div>

                        <!-- Status Update Form -->
                        <?php if ($item['status'] != 'Decline' && $item['status'] != 'Completed' && $item['status'] != 'Order Ready') { ?>
                        <div class="order-actions">
                            <form method="post" action="orders.php" style="display:inline;" onsubmit="return confirmReady();">
                                <input type="hidden" name="order_item_id" value="<?php echo $item['order_item_id']; ?>">
                                <input type="hidden" name="status" value="Order Ready">
                                <button type="submit" class="action-btn ready">Order Ready</button>
                            </form>
                            
                            <form method="post" action="orders.php" style="display:inline;" onsubmit="return confirmDecline();">
                                <input type="hidden" name="order_item_id" value="<?php echo $item['order_item_id']; ?>">
                                <input type="hidden" name="status" value="Decline">
                                <button type="submit" class="action-btn decline">Decline</button>
                            </form>
                        </div>
                        <?php } ?>


                    </li>
                    <hr>
                <?php } ?>
                </ul>
                <p class="total-amount"><strong>Total:</strong> RM <?php echo number_format($order['total'], 2); ?></p>
            </div>
        <?php } ?>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">

    function confirmDecline() {
        return confirm("Attention! Are you sure you want to decline this order?");
    }

    function confirmReady(){
        return confirm("Please ensure the orders is ready!");
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

        if (menuBar) {
            menuBar.addEventListener('click', openMenu);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeMenu);
        }

        if (overlay) {
            overlay.addEventListener('click', closeMenu);
        }
    });
</script>
</body>
</html>
