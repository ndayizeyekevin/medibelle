<?php
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../logout.php");
    exit();
}
include './connection.php';

$conn = $dsn;

if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];

    $query = "SELECT DISTINCT name, quantity, pricee, price, (quantity * pricee) AS total FROM order_items, products, customers, orders WHERE order_items.product_id = products.id AND order_items.order_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode($items);
    exit;
}
?>
