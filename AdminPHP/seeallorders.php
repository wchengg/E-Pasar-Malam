<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("location:adminlogin.php");
    exit();
}

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pasarmalam"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders for the admin view
$sql = "SELECT o.order_id, oi.item_id, oi.title, oi.quantity, oi.price, oi.remarks,
               o.user_id, o.pickup_time, o.payment_method, o.name, o.contact_number, 
               o.order_date, o.total, o.status, i.shop_id, m.shopname
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN item i ON oi.item_id = i.item_id
        JOIN merchant m ON i.shop_id = m.merchant_id
        ORDER BY o.order_id DESC";


$result = $conn->query($sql);

// Group results by order_id
$orders = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $row['order_id'],
            'user_id' => $row['user_id'],
            'pickup_time' => $row['pickup_time'],
            'payment_method' => $row['payment_method'],
            'name' => $row['name'],
            'contact_number' => $row['contact_number'],
            'order_date' => $row['order_date'],
            'total' => $row['total'],
            'status' => $row['status'],
            'items' => []
        ];
    }
    $orders[$order_id]['items'][] = [
        'item_id' => $row['item_id'],
        'title' => $row['title'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'remarks' => $row['remarks']
    ];
}

// Handle status updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    
    // Refresh the page to show updated status
    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../Css/seeallorders.css" rel="stylesheet" type="text/css"> 
    <title>Admin Orders</title>
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
            <a href="../AdminPHP/registerdelivery.php">- Register Deliveryman</a>
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

    <div class="container">
        <h1>All Orders</h1>
        <div class="table_wrapper">
        <table style="width:100%">
            <tr>
                <th>Order ID</th>
                <th>Item ID</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Remarks</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Pickup Time</th>
                <th>Payment Method</th>
                <th>Contact Number</th>
                <th>Order Date</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            <?php
            // Display grouped orders
            foreach ($orders as $order) {
                $item_count = count($order['items']);
                foreach ($order['items'] as $index => $item) {
                    if ($index == 0) {
                        echo "<tr>";
                        echo "<td rowspan='$item_count'>" . $order['order_id'] . "</td>";
                        echo "<td>" . $item['item_id'] . "</td>";
                        echo "<td>" . $item['title'] . "</td>";
                        echo "<td>" . $item['quantity'] . "</td>";
                        echo "<td>" . $item['price'] . "</td>"; // Ensure 'price' is fetched if needed
                        echo "<td>" . $item['remarks'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['user_id'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['name'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['pickup_time'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['payment_method'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['contact_number'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['order_date'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['total'] . "</td>";
                        echo "<td rowspan='$item_count'>" . $order['status'] . "</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr>";
                        echo "<td>" . $item['item_id'] . "</td>";
                        echo "<td>" . $item['title'] . "</td>";
                        echo "<td>" . $item['quantity'] . "</td>";
                        echo "<td>" . $item['price'] . "</td>"; // Ensure 'price' is fetched if needed
                        echo "<td>" . $item['remarks'] . "</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </table>
        </div>
    </div>
</body>

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
</html>
