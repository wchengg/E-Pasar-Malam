<?php
// Include database connection
include_once("database.php");

// Start the session
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Initialize search query
$search_order_id = isset($_POST['search_order_id']) ? $_POST['search_order_id'] : '';

// Prepare SQL query
if (!empty($search_order_id)) {
    $sql_orders = "SELECT * FROM orders WHERE user_id = ? AND order_id = ? ORDER BY order_id DESC";
    $stmt = $conn->prepare($sql_orders);
    $stmt->bind_param("ii", $user_id, $search_order_id);
} else {
    $sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC";
    $stmt = $conn->prepare($sql_orders);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result_orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Css/shophistory.css">
    <title>Order History</title>
</head>
<body>
<!-- HTML Content -->
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

<!-- Search Form -->
<div class="search-form">
    <form method="post" action="history.php">
        <input type="text" name="search_order_id" placeholder="Search by Order ID">
        <button type="submit" class="search-btn">&#128269;</button>
    </form>
</div>

<!-- Back Button -->
<div class="container">
    <div class="back-button">
        <div class="back"><a href="shoplist.php" class="back-link"><img src="../Picture/back-icon.png" class="back-icon" alt="Back"></a></div>
        <div class="button"><h1>My Orders</h1></div>
    </div>

    <div class="order-history-list">
        <?php
        if ($result_orders->num_rows > 0) {
            while ($order = $result_orders->fetch_assoc()) {
                $order_id = $order['order_id'];
                $status = $order['status'];
                $status_class = ($status == 'Completed') ? 'status-completed' : 'status-progress';
                $total_items = 0;
        ?>

                <div class="order-card <?php echo $order['status'] == 'Completed' ? 'completed' : ''; ?>">
                    <div class="order-header">
                        <h2>Order ID: #<?php echo htmlspecialchars($order['order_id']); ?></h2>
                        <span class="order-status <?php echo htmlspecialchars($status_class); ?>">Status: <?php echo htmlspecialchars($status); ?></span>
                    </div>
                    <p><span class="order-status">Pickup Time: <?php echo htmlspecialchars($order['pickup_time']); ?></p>
                    <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>

                    <ul class="order-items">
                    <?php
                    $sql_items = "SELECT * FROM order_items WHERE order_id = ?";
                    $stmt_items = $conn->prepare($sql_items);
                    $stmt_items->bind_param("i", $order_id);
                    $stmt_items->execute();
                    $result_items = $stmt_items->get_result();

                    if ($result_items->num_rows > 0) {
                        while ($item = $result_items->fetch_assoc()) {
                            $total_items += $item['quantity'];
                    ?>
                            <div class="order-item">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="item-image">
                                <div class="item-details">
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
                    <?php
                        }
                    } else {
                        echo "No items found in this order.";
                    }
                    ?>
                    </ul>
                    <p class="total-amount">Total: RM <?php echo number_format($order['total'], 2); ?></p>
                </div>
        <?php
            }
        } else {
            echo "<h2>No orders found.</h2>";
        }
        ?>
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
