<?php
// Include database connection
include_once("database.php");

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Retrieve POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate data
if (!isset($data['pickup_time']) || !isset($data['payment_method']) || !isset($data['items']) || !isset($data['name']) || !isset($data['contact_number'])) {
    http_response_code(400);
    echo json_encode(["message" => "Missing data"]);
    exit();
}

// Extract data
$pickup_time = $data['pickup_time'];
$payment_method = $data['payment_method'];
$items = $data['items'];
$name = $data['name'];
$contact_number = $data['contact_number'];

// Calculate total
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Set the initial status
$status = 'In Progress'; // Default status

// Set the current date and time for order_date
$order_date = date('Y-m-d H:i:s');

// Insert order into the database
$order_sql = "INSERT INTO orders (user_id, pickup_time, payment_method, name, contact_number, order_date, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("isssssss", $user_id, $pickup_time, $payment_method, $name, $contact_number, $order_date, $total, $status);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert items into the database
$item_sql = "INSERT INTO order_items (order_id, title, price, quantity, image, item_id, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)";
$item_stmt = $conn->prepare($item_sql);

foreach ($items as $item) {
    // Validate item_id
    if (!isset($item['item_id']) || is_null($item['item_id'])) {
        http_response_code(400);
        echo json_encode(["message" => "Item ID is missing or null"]);
        exit();
    }

    // Bind parameters
    $item_stmt->bind_param("isdisis", $order_id, $item['title'], $item['price'], $item['quantity'], $item['image'], $item['item_id'], $item['remarks']);
    
    // Execute statement and check for errors
    if (!$item_stmt->execute()) {
        http_response_code(500);
        echo json_encode(["message" => "Error inserting item: " . $item_stmt->error]);
        exit();
    }
}

// Close the connection
$stmt->close();
$item_stmt->close();
$conn->close();

// Respond with success
echo json_encode(["message" => "Order placed successfully"]);
?>
