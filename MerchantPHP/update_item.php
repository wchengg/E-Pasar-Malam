<?php
include_once("database.php");

// Fetch POST data
$item_id = isset($_POST['item_id']) ? $_POST['item_id'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';
$sold = isset($_POST['sold']) ? $_POST['sold'] : '';

// Validate data
if (empty($item_id) || empty($title) || empty($price) || empty($sold)) {
    echo "Error: Missing required data.";
    exit();
}

// Prepare and execute update query
$sql = "UPDATE item SET itemname = ?, itemprice = ?, sold = ? WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdii", $title, $price, $sold, $item_id);

if ($stmt->execute()) {
    echo "Item updated successfully";
} else {
    echo "Error updating item: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
