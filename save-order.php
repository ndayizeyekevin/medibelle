<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/cosmeticsstore/assets/css/sweetalert2.min.css">
  
    <!-- SweetAlert2 JS -->
    <script src="/cosmeticsstore/assets/js/sweetalert2.all.min.js"></script>
</head>
<body>

<?php
// Database configuration
require './includes/connection.php';
// Create connection
$conn = $dsn;

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve data from the POST request
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$cart_data = json_decode($_POST['cart'], true); // Decode the JSON cart data

try {
    // Check if the customer exists
    $check_customer = "SELECT * FROM customers WHERE phone = ?";
    $stmt_customer = $conn->prepare($check_customer);
    $stmt_customer->bind_param("s", $phone);
    $stmt_customer->execute();
    $result = $stmt_customer->get_result();

    if ($result->num_rows === 0) {
        // Customer does not exist, insert the customer
        $sql_customer = "INSERT INTO customers (full_name, email, address, phone) VALUES (?, ?, ?, ?)";
        $stmt_insert_customer = $conn->prepare($sql_customer);
        $stmt_insert_customer->bind_param("ssss", $full_name, $email, $address, $phone);

        if (!$stmt_insert_customer->execute()) {
            throw new Exception("Error inserting customer: " . $stmt_insert_customer->error);
        }
        $customer_id = $stmt_insert_customer->insert_id; // Get the inserted customer's ID
    } else {
        // Customer exists, fetch their ID
        $customer = $result->fetch_assoc();
        $customer_id = $customer['id'];
    }

    // Insert order information
    $sql_order = "INSERT INTO orders (customer_id, total_items, total_price) VALUES (?, ?, ?)";
    $total_items = 0;
    $total_price = 0;

    foreach ($cart_data as $item) {
        $total_items += $item['quantity'];
        $total_price += $item['price'] * $item['quantity'];
    }

    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("iid", $customer_id, $total_items, $total_price);

    if (!$stmt_order->execute()) {
        throw new Exception("Error inserting order: " . $stmt_order->error);
    }
    $order_id = $stmt_order->insert_id; // Get the inserted order's ID

    // Insert each cart item into the order_items table
    $sql_order_item = "INSERT INTO order_items (order_id, product_id, pricee, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt_order_item = $conn->prepare($sql_order_item);

    foreach ($cart_data as $item) {
        // Fetch the product ID based on the product name
        $sql_product = "SELECT id FROM products WHERE name = ?";
        $stmt_product = $conn->prepare($sql_product);
        $stmt_product->bind_param("s", $item['name']);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();

        if ($result_product->num_rows > 0) {
            $product = $result_product->fetch_assoc();
            $product_id = $product['id'];

            // Calculate total price for the item
            $price = $item['price'];
            $quantity = $item['quantity'];
            $item_total_price = $price * $quantity;

            // Insert the order item
            $stmt_order_item->bind_param("iidii", $order_id, $product_id, $price, $quantity, $item_total_price);

            if (!$stmt_order_item->execute()) {
                throw new Exception("Error inserting order item: " . $stmt_order_item->error);
            }
        } else {
            throw new Exception("Product not found: " . $item['name']);
        }
    }

    // Display success message and clear the cart
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Order Saved',
            text: `The cart has been successfully saved!
                   Our Agent will contact you soon!`,
            confirmButtonText: 'OK'
        }).then(() => {
            localStorage.removeItem('cart');
            window.location.href = './index.php'; // Redirect to the cart page
        });
    </script>";

} catch (Exception $e) {
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `" . $e->getMessage() . "`,
            confirmButtonText: 'OK'
        });
    </script>";
} finally {
    // Close all statements and connection
    if (isset($stmt_customer)) $stmt_customer->close();
    if (isset($stmt_insert_customer)) $stmt_insert_customer->close();
    if (isset($stmt_order)) $stmt_order->close();
    if (isset($stmt_order_item)) $stmt_order_item->close();
    if (isset($stmt_product)) $stmt_product->close();
    $conn->close();
}
?>

</body>
</html>
