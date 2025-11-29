<?php
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

// AJAX ile miktar güncelleme
if (isset($_POST['ajax']) && $_POST['ajax'] === 'update_quantity') {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    echo 'Quantity updated!';
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
    exit;
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php @include 'header.php'; ?>

<section class="container py-4">
    <h3 class="mb-4">Shopping Cart</h3>
    <p><a href="home.php">Home</a> / Cart</p>

    <div class="row">
        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>"
                                   onclick="return confirm('Delete this item?');"
                                   class="text-danger"><i class="bi bi-x-lg"></i></a>
                                <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="text-info"><i class="bi bi-eye"></i></a>
                            </div>
                            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt=""
                                 class="img-fluid my-3">
                            <h5 class="card-title"><?php echo $fetch_cart['name']; ?></h5>
                            <p class="card-text">$<?php echo $fetch_cart['price']; ?>/-</p>

                            <form onsubmit="return false;">
                                <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" class="cart-id">
                                <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>"
                                       class="form-control qty mb-2" onchange="updateQuantity(this)">
                                <span class="update-msg text-success small" style="display:none;"></span>
                            </form>

                            <p class="fw-bold">Sub-total:
                                $<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</p>
                        </div>
                    </div>
                </div>
                <?php
                $grand_total += $sub_total;
            }
        } else {
            echo '<p class="text-muted">Your cart is empty.</p>';
        }
        ?>
    </div>

    <div class="text-end my-3">
        <a href="cart.php?delete_all"
           class="btn btn-danger <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>"
           onclick="return confirm('Delete all items?');">Delete All</a>
    </div>

    <div class="card p-3">
        <p class="mb-2">Grand Total: <strong>$<?php echo $grand_total; ?>/-</strong></p>
        <div>
            <a href="shop.php" class="btn btn-secondary me-2">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-primary <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
        </div>
    </div>
</section>

<?php @include 'footer.php'; ?>

<script>
    function updateQuantity(inputEl) {
        const cartId = inputEl.closest('form').querySelector('.cart-id').value;
        const qty = inputEl.value;
        const msgSpan = inputEl.closest('form').querySelector('.update-msg');

        fetch("cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `ajax=update_quantity&cart_id=${cartId}&cart_quantity=${qty}`
        })
            .then(res => res.text())
            .then(data => {
                msgSpan.style.display = 'inline';
                msgSpan.textContent = data;
                setTimeout(() => msgSpan.style.display = 'none', 3000);
            });
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>

