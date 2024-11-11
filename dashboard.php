<?php
// Database connection
$host = 'localhost';
$dbname = 'hope';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}

// Fetch products from the database
$stmt = $pdo->query("SELECT id, name, price FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari Sari Store Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Global styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2e2e3e;
            color: #f1f1f1;
            box-sizing: border-box;
        }

        /* Navbar styles */
        .navbar {
            background-color: #4b3f72;
            color: #f1f1f1;
            padding: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f1f1f1;
        }

        .navbar .logo {
            font-weight: bold;
            color: #f0c674;
            font-size: 24px;
            margin-right: auto;
        }

        .navbar .links {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .navbar .links a {
            color: #f0c674;
            margin-left: 1.5em;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar .links a:hover {
            color: #fff;
        }

        /* Dashboard content styles */
        .dashboard {
            padding: 3em 2em;
            max-width: 700px;
            background-color: #3c3c51;
            border-radius: 15px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
            margin-left: 20px;
            margin-top: 20px;
        }

        .dashboard h1 {
            color: #f0c674;
            font-size: 28px;
            text-align: left;
            margin-bottom: 1.5em;
        }

        /* Product Selection */
        .product-selection, .checkout {
            background-color: #2c2c3e;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }

        .product-selection label, .checkout label {
            color: #f1f1f1;
            font-weight: 600;
        }

        .product-selection select, .product-selection input, .checkout input {
            padding: 12px;
            margin-top: 10px;
            width: 100%;
            background-color: #4a4a5d;
            color: #f1f1f1;
            border: 1px solid #f0c674;
            border-radius: 8px;
            font-size: 16px;
        }

        .product-selection select:focus, .checkout input:focus {
            border-color: #f0c674;
            outline: none;
        }

        /* Checkout button styles */
        .checkout button {
            background-color: #f0c674;
            color: #2e2e3e;
            padding: 15px;
            width: 100%;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            margin-top: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .checkout button:hover {
            background-color: #e0b154;
            transform: scale(1.05);
        }

        /* Message styles */
        .warning {
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }

        .warning.success {
            color: #4caf50;
        }

        .warning.error {
            color: #ff5252;
        }

    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">Sari Sari Store</div>
    <div class="links">
        <a href="history.php">Transaction History</a>
        <a href="dashboard.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Dashboard Section -->
<div class="dashboard">
    <h1>Point of Sale - Sari Sari Store Products</h1>

    <!-- Product Selection -->
    <div class="product-selection">
        <label for="product">Select Product:</label>
        <select id="product">
            <?php
            foreach ($products as $product) {
                echo "<option value='{$product['id']}' data-price='{$product['price']}'>
                        {$product['name']} - \${$product['price']}
                      </option>";
            }
            ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" min="1" placeholder="Enter quantity">
    </div>

    <!-- Checkout Section -->
    <div class="checkout">
        <label for="payment">Payment Amount:</label>
        <input type="number" id="payment" placeholder="Enter payment amount">

        <button onclick="processTransaction()">Checkout</button>

        <div id="message" class="warning"></div>
    </div>
</div>

<script>
    // JavaScript to handle POS logic
    function processTransaction() {
        const product = document.getElementById("product");
        const productId = product.value;
        const quantity = parseInt(document.getElementById("quantity").value);
        const payment = parseFloat(document.getElementById("payment").value);
        const message = document.getElementById("message");

        // Validate inputs
        if (isNaN(quantity) || isNaN(payment) || quantity <= 0 || payment <= 0) {
            message.style.color = 'red';
            message.innerText = 'Please enter valid quantity and payment amount.';
            return;
        }

        // Send transaction data to server
        fetch('process_transaction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${productId}&quantity=${quantity}&payment=${payment}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                message.style.color = 'green';
                message.innerText = `Transaction successful! Change: $${data.change.toFixed(2)}`;
            } else {
                message.style.color = 'red';
                message.innerText = data.message;
            }
        })
        .catch(error => {
            message.style.color = 'red';
            message.innerText = 'Error processing transaction. Please try again.';
        });
    }
</script>

</body>
</html>
