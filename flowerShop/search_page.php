<?php
@include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $check_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');
    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

    if (mysqli_num_rows($check_wishlist) > 0) {
        $message[] = 'Already added to wishlist';
    } elseif (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Already added to cart';
    } else {
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('Query failed');
        $message[] = 'Product added to wishlist';
    }
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');

    if (mysqli_num_rows($check_cart) > 0) {
        $message[] = 'Already added to cart';
    } else {
        $check_wishlist = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');
        if (mysqli_num_rows($check_wishlist) > 0) {
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('Query failed');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Query failed');
        $message[] = 'Product added to cart';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Search Page</h3>
    <p><a href="home.php">Home</a> / Search</p>
</section>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="Search products..." name="search_box">
        <input type="submit" class="btn" value="Search" name="search_btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">
    <div class="box-container">
        <?php
        if (isset($_POST['search_btn'])) {
            $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
            $products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search_box%'") or die('Query failed');

            if (mysqli_num_rows($products) > 0) {
                while ($product = mysqli_fetch_assoc($products)) {
        ?>
        <form action="" method="POST" class="box">
            <a href="view_page.php?pid=<?= $product['id']; ?>" class="view-link">View</a>
            <div class="price">$<?= $product['price']; ?>/-</div>
            <img src="uploaded_img/<?= $product['image']; ?>" alt="" class="image">
            <div class="name"><?= $product['name']; ?></div>
            <input type="number" name="product_quantity" value="1" min="1" class="qty">
            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
            <input type="hidden" name="product_name" value="<?= $product['name']; ?>">
            <input type="hidden" name="product_price" value="<?= $product['price']; ?>">
            <input type="hidden" name="product_image" value="<?= $product['image']; ?>">
            <input type="submit" value="Add to Wishlist" name="add_to_wishlist" class="option-btn">
            <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
        </form>
        <?php
                }
            } else {
                echo '<p class="empty">No result found!</p>';
            }
        } else {
            echo '<p class="empty">Search something!</p>';
        }
        ?>
    </div>
</section>

<?php @include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
