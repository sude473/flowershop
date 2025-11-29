<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Your Orders</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="heading">
    <h3>Your Orders</h3>
    <p><a href="home.php">Home</a> / Orders</p>
</section>

<section class="placed-orders">
    <h1 class="title">Placed Orders</h1>
    <div class="box-container">
    <?php
    $stmt = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($order = $result->fetch_assoc()) {
    ?>
        <div class="box">
            <p>Placed on: <span><?= htmlspecialchars($order['placed_on']) ?></span></p>
            <p>Name: <span><?= htmlspecialchars($order['name']) ?></span></p>
            <p>Number: <span><?= htmlspecialchars($order['number']) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($order['email']) ?></span></p>
            <p>Address: <span><?= htmlspecialchars($order['address']) ?></span></p>
            <p>Payment Method: <span><?= htmlspecialchars($order['method']) ?></span></p>
            <p>Your Orders: <span><?= htmlspecialchars($order['total_products']) ?></span></p>
            <p>Total Price: <span>$<?= number_format($order['total_price'], 2) ?>/-</span></p>
            <p>Payment Status: 
                <span style="color:<?= $order['payment_status'] === 'pending' ? 'tomato' : 'green' ?>">
                    <?= htmlspecialchars($order['payment_status']) ?>
                </span>
            </p>
        </div>
    <?php
        }
    } else {
        echo '<p class="empty">No orders placed yet!</p>';
    }

    $stmt->close();
    ?>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>

