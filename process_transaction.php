<?php
// Database connection
$host = 'localhost';
$dbname = 'hope';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Get POST data
$productId = $_POST['product_id'];
$quantity = $_POST['quantity'];
$payment = $_POST['payment'];

// Fetch product price
$stmt = $pdo->prepare("SELECT price, stock FROM products WHERE id = :product_id");
$stmt->execute(['product_id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product) {
    $price = $product['price'];
    $stock = $product['stock'];

    // Check if enough stock is available
    if ($quantity > $stock) {
        echo json_encode(['status' => 'error', 'message' => 'Not enough stock available']);
        exit;
    }

    // Calculate total amount
    $totalAmount = $price * $quantity;
    $change = $payment - $totalAmount;

    // Check if payment is sufficient
    if ($change < 0) {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient payment']);
        exit;
    }

    // Insert transaction into the database
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, product_id, quantity, total_amount, payment) 
                           VALUES (1, :product_id, :quantity, :total_amount, :payment)");
    $stmt->execute([
        'product_id' => $productId,
        'quantity' => $quantity,
        'total_amount' => $totalAmount,
        'payment' => $payment
    ]);

    // Update product stock
    $stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
    $stmt->execute([
        'quantity' => $quantity,
        'product_id' => $productId
    ]);

    // Return success
    echo json_encode(['status' => 'success', 'change' => $change]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
}
