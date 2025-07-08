<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch cart items for this user
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT p.name, p.price, c.quantity 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Optional: Clear cart after checkout
$conn->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f7f7f7;
        }
        h2 {
            color: #28a745;
        }
        table {
            width: 70%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
        .container {
            background: #fff;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Order Confirmed!</h2>
    <p>Thank you for your purchase. Here is your order summary:</p>

    <table>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price (₹)</th>
            <th>Subtotal (₹)</th>
        </tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>₹<?= number_format($item['price'], 2) ?></td>
            <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3" class="total">Total to Pay</td>
            <td class="total">₹<?= number_format($total, 2) ?></td>
        </tr>
    </table>

    <a href="../index.php">Return to Shop</a>
</div>

</body>
</html>
