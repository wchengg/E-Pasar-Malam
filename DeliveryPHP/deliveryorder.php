<?php
// Include database connection
include_once("database.php");

// Start session
session_start();
if (!isset($_SESSION['deliveryman_id'])) {
    header("Location: deliverylogin.php");
    exit();
}

// Fetch deliveryman ID from session
$deliveryman_id = $_SESSION['deliveryman_id'];

// Fetch orders that have not been assigned or are ready
$sql_orders = "SELECT o.order_id, o.pickup_time, o.order_date, o.total, o.status AS order_status,
                      i.item_id, i.itemname AS title, oi.quantity, oi.remarks, oi.order_item_id, oi.image, oi.price, oi.status AS item_status
               FROM orders o
               JOIN order_items oi ON o.order_id = oi.order_id
               JOIN item i ON oi.item_id = i.item_id
               WHERE (o.status = 'Waiting for Deliveryman' OR o.deliveryman_id IS NULL)
               ORDER BY o.order_id DESC";

$stmt = $conn->prepare($sql_orders);
$stmt->execute();
$result_orders = $stmt->get_result();

// Group items by order
$orders = [];
while ($row = $result_orders->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $row['order_id'],
            'pickup_time' => $row['pickup_time'],
            'order_date' => $row['order_date'],
            'total' => $row['total'],
            'status' => $row['order_status'],
            'items' => []
        ];
    }

    $orders[$order_id]['items'][] = [
        'item_id' => $row['item_id'],
        'title' => $row['title'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'remarks' => $row['remarks'],
        'order_item_id' => $row['order_item_id'],
        'status' => $row['item_status'],
        'image' => $row['image']
    ];
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    if (!empty($order_id) && !empty($new_status)) {
        // Update the status and assign deliveryman_id
        $update_sql = "UPDATE orders 
                       SET status = ?, deliveryman_id = ? 
                       WHERE order_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sii", $new_status, $deliveryman_id, $order_id);
        $update_stmt->execute();
        
        // Redirect to the 'myorders.php' page
        header("Location: myorders.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Css/deliveryorders.css">
    <title>Delivery Orders</title>
</head>
<body>
<div class="top-bar box">
    <div class="top-bar left">
        <img src="../Picture/menuBar.svg" class="menuBar svg" alt="Menu">
    </div>
    <div class="top-bar mid">
        <a href="./deliveryorder.php" class="logo-link">
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
        <a href="deliveryprofile.php">- My Profile</a>
        <a href="deliveryorder.php">- All Orders</a>
        <a href="myorders.php">- My Orders</a>
    </div>
</div>

<!-- Overlay -->
<div class="overlay"></div>

<!-- Back Button -->
<div class="container">
    <div class="back-button">
        <div class="button"><h1>All Orders</h1></div>
    </div>

    <div class="order-history-list">
        <?php foreach ($orders as $order) { ?>
            <div class="order-card <?php echo $order['status'] == 'Completed' ? 'completed' : ''; ?>">
                <div class="order-header">
                    <h2>Order ID: #<?php echo htmlspecialchars($order['order_id']); ?></h2>
                    <span class="order-status">
                        Status: <?php echo htmlspecialchars($order['status']); ?>
                    </span>
                    <p><strong>Pickup Time:</strong> <?php echo htmlspecialchars($order['pickup_time']); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                </div>

                <ul class="order-items">
                <?php foreach ($order['items'] as $item) { ?>
                    <li class="order-item">
                        <div class="item-details">
                            <div class="item-image-container">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image">
                            </div>
                            <div class="item-text-details">
                                <div class="box">
                                    <span class="item-title"><?php echo htmlspecialchars($item['title']); ?></span>
                                    <span class="item-quantity">x<?php echo htmlspecialchars($item['quantity']); ?></span>
                                </div>
                                <div class="price"><span class="item-price">RM <?php echo number_format($item['price'], 2); ?></span></div>
                                <?php if (!empty($item['remarks'])): ?>
                                    <span class="item-remarks">Remarks: <?php echo htmlspecialchars($item['remarks']); ?></span><br>
                                <?php endif; ?>
                                <span class="item-status <?php echo ($item['status'] == 'Decline') ? 'item-status-decline' : (($item['status'] == 'Order Ready') ? 'item-status-ready' : ''); ?>">
                                    Status: <?php echo $item['status']; ?>
                                </span>
                            </div>
                        </div>
                    </li>
                    <hr>
                <?php } ?>
                </ul>
                <p class="total-amount"><strong>Total:</strong> RM <?php echo number_format($order['total'], 2); ?></p>


                <!-- Accept Order Button -->
                <form method="post" action="deliveryorder.php" style="display:inline;">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                    <input type="hidden" name="status" value="Order Accepted">
                    <button type="submit" class="action-btn accept">Accept Order</button>
                </form>


            </div>
        <?php } ?>
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
