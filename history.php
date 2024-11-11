<?php
include 'config.php'; // Include your database connection file

// Fetch transactions for Left Join
function fetchLeftJoinTransactions() {
    global $pdo;
    $stmt = $pdo->query("SELECT t.id, u.username, p.name AS product_name, t.quantity, t.total_amount, t.payment, t.created_at 
                          FROM transactions t 
                          LEFT JOIN users u ON t.user_id = u.id 
                          LEFT JOIN products p ON t.product_id = p.id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch transactions for Right Join
function fetchRightJoinTransactions() {
    global $pdo;
    $stmt = $pdo->query("SELECT t.id, u.username, p.name AS product_name, t.quantity, t.total_amount, t.payment, t.created_at 
                          FROM transactions t 
                          RIGHT JOIN users u ON t.user_id = u.id 
                          RIGHT JOIN products p ON t.product_id = p.id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch transactions for Cross Join
function fetchCrossJoinTransactions() {
    global $pdo;
    $stmt = $pdo->query("SELECT t.id, u.username, p.name AS product_name, t.quantity, t.total_amount, t.payment, t.created_at 
                          FROM transactions t 
                          CROSS JOIN users u 
                          CROSS JOIN products p");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$leftJoinTransactions = fetchLeftJoinTransactions();
$rightJoinTransactions = fetchRightJoinTransactions();
$crossJoinTransactions = fetchCrossJoinTransactions();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #2e2e3e;
            color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

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
        }

        .navbar a {
            color: #f0c674;
            margin-left: 1.5em;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #fff;
        }

        .container {
            padding: 2em;
            margin: 2em auto;
            max-width: 1200px;
            background-color: #3c3c51;
            border-radius: 15px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.3);
        }

        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            background-color: #f0c674;
            color: #2e2e3e;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-right: 15px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background-color: #e0b154;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #1f1f1f;
            border: 1px solid #444;
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            color: #f1f1f1;
        }

        th {
            background-color: #333;
            color: #f0c674;
        }

        tr:nth-child(even) {
            background-color: #2b2b2b;
        }

        .hidden {
            display: none;
        }

    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">Sari Sari Store</div>
    <div>
        <a href="history.php">Transaction History</a>
        <a href="dashboard.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h1>Transaction History</h1>

    <!-- Button Container -->
    <div class="button-container">
        <button onclick="showTable('leftJoin')">Left Join</button>
        <button onclick="showTable('rightJoin')">Right Join</button>
        <button onclick="showTable('crossJoin')">Cross Join</button>
    </div>

    <!-- Left Join Table -->
    <div id="leftJoin" class="table-container hidden">
        <h2>Left Join Transactions</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
            <?php foreach ($leftJoinTransactions as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['username'] ?? 'N/A'; ?></td>
                    <td><?php echo $transaction['product_name'] ?? 'N/A'; ?></td>
                    <td><?php echo $transaction['quantity']; ?></td>
                    <td><?php echo $transaction['total_amount']; ?></td>
                    <td><?php echo $transaction['payment']; ?></td>
                    <td><?php echo $transaction['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Right Join Table -->
    <div id="rightJoin" class="table-container hidden">
        <h2>Right Join Transactions</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
            <?php foreach ($rightJoinTransactions as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['username'] ?? 'N/A'; ?></td>
                    <td><?php echo $transaction['product_name'] ?? 'N/A'; ?></td>
                    <td><?php echo $transaction['quantity']; ?></td>
                    <td><?php echo $transaction['total_amount']; ?></td>
                    <td><?php echo $transaction['payment']; ?></td>
                    <td><?php echo $transaction['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Cross Join Table -->
    <div id="crossJoin" class="table-container hidden">
        <h2>Cross Join Transactions</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
            <?php foreach ($crossJoinTransactions as $transaction): ?>
                <tr>
                    <td><?php echo $transaction['id']; ?></td>
                    <td><?php echo $transaction['username'] ?? 'N/A'; ?></td>
                    <td><?php echo $transaction['product_name'] ?? 'N/A'; ?></td>
                    <td><?php echo $transaction['quantity']; ?></td>
                    <td><?php echo $transaction['total_amount']; ?></td>
                    <td><?php echo $transaction['payment']; ?></td>
                    <td><?php echo $transaction['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
    // Function to display the selected table based on button clicked
    function showTable(tableName) {
        // Hide all tables
        const tables = document.querySelectorAll('.table-container');
        tables.forEach(table => table.classList.add('hidden'));

        // Show the selected table
        const tableToShow = document.getElementById(tableName);
        if (tableToShow) {
            tableToShow.classList.remove('hidden');
        }
    }
</script>

</body>
</html>
