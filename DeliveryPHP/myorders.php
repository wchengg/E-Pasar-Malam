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

// Initialize the search query
$search_order_id = isset($_POST['search_order_id']) ? $_POST['search_order_id'] : '';

// Fetch all orders accepted by this deliveryman
$sql_orders = "SELECT o.order_id, o.pickup_time, o.order_date, o.total, o.status AS order_status,
                      i.item_id, i.itemname AS title, oi.quantity, oi.remarks, oi.order_item_id, oi.image, oi.price, oi.status AS item_status
               FROM orders o
               JOIN order_items oi ON o.order_id = oi.order_id
               JOIN item i ON oi.item_id = i.item_id
               WHERE o.deliveryman_id = ? AND o.status != 'Waiting for deliveryman'"; // Exclude orders with status "Waiting for deliveryman"

// Append search filter if specified
if (!empty($search_order_id)) {
    $sql_orders .= " AND o.order_id = ?";
}

// Add sorting
$sql_orders .= " ORDER BY 
                   CASE 
                       WHEN o.status = 'Completed' THEN 1 
                       ELSE 0 
                   END, 
                   o.order_id DESC"; // Orders not completed come first, then by order ID

$stmt = $conn->prepare($sql_orders);

// Bind parameters
if (!empty($search_order_id)) {
    $stmt->bind_param("ii", $deliveryman_id, $search_order_id);
} else {
    $stmt->bind_param("i", $deliveryman_id);
}

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

// Handle status update for ready to pickup and completed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    if (!empty($order_id) && !empty($new_status)) {
        $update_sql = "UPDATE orders 
                       SET status = ? 
                       WHERE order_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_status, $order_id);
        $update_stmt->execute();
        
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
    <title>My Orders</title>
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

<!-- Search Form -->
<div class="search-form">
    <form method="post" action="myorders.php">
        <input type="text" name="search_order_id" placeholder="Search by Order ID">
        <button type="submit" class="search-btn">&#128269;</button>
    </form>
</div>

<div class="container">
    <div class="back-button">
        <div class="back"><a href="deliveryorder.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a></div>
        <div class="button"><h1>My Orders</h1></div>
    </div>

    <!-- Order History List -->
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

        <!-- Status Update Buttons -->
        <div class="order-actions">
        <?php
        $canUpdateStatus = true;
        foreach ($order['items'] as $item) {
            if ($item['status'] == 'In Progress') {
                $canUpdateStatus = false;
                break;
            }
        }
        ?>
        <form method="post" action="myorders.php" style="display:inline;" id="form-ready-<?php echo htmlspecialchars($order['order_id']); ?>">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
            <input type="hidden" name="status" value="Ready to Pickup">
            <button type="button" class="action-btn ready" <?php echo $canUpdateStatus ? 'onclick="submitForm(\'form-ready-' . htmlspecialchars($order['order_id']) . '\')"' : 'onclick="showAlert(\'' . htmlspecialchars($order['order_id']) . '\', \'ready\')"'; ?>>Ready to Pickup</button>
        </form>
        <form method="post" action="myorders.php" style="display:inline;" id="form-completed-<?php echo htmlspecialchars($order['order_id']); ?>">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
            <input type="hidden" name="status" value="Completed">
            <button type="button" class="action-btn completed" <?php echo $canUpdateStatus ? 'onclick="submitForm(\'form-completed-' . htmlspecialchars($order['order_id']) . '\')"' : 'onclick="showAlert(\'' . htmlspecialchars($order['order_id']) . '\', \'completed\')"'; ?>>Completed</button>
        </form>
        </div>

    </div>
<?php } ?>
    </div>
</div>

<script type="text/javascript">
    function showAlert(orderId, statusType) {
        alert("Item in progress, cannot mark order #" + orderId + " as " + (statusType === 'ready' ? "Ready to Pickup" : "Completed"));
    }

    function submitForm(formId) {
        document.getElementById(formId).submit();
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
