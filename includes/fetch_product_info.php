<?php
ob_start();
require './conn.php';

if (isset($_GET['product_id'])) {
    $product_id = (int) $_GET['product_id'];
    $stmt = $pdo->prepare("SELECT id, name, price, description FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} else {
    echo json_encode(['error' => 'No product id specified']);
}
ob_end_flush();
?>
