<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="bi bi-x" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

    <div class="flex">

        <a href="home.php" class="logo">bloom</a>

        <nav class="navbar">
            <ul>
                <li><a href="home.php">home</a></li>
                <li><a href="#">pages </a>
                    <ul>
                        <li><a href="about.php">about</a></li>
                        <li><a href="contact.php">contact</a></li>
                    </ul>
                </li>
                <li><a href="shop.php">shop</a></li>
                <li><a href="orders.php">orders</a></li>
                <li><a href="#">account </a>
                    <ul>
                        <li><a href="login.php">login</a></li>
                        <li><a href="register.php">register</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="icons">
    <div id="menu-btn" class="bi bi-list" style="cursor:pointer;"></div>
    <a href="search_page.php"><i class="bi bi-search"></i></a>
    <div id="user-btn" class="bi bi-person-circle" style="cursor:pointer;"></div>

    <?php
        $select_wishlist_count = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE user_id = '$user_id'") or die('query failed');
        $wishlist_num_rows = mysqli_num_rows($select_wishlist_count);
    ?>
    <a href="wishlist.php"><i class="bi bi-heart-fill"></i><span>(<?php echo $wishlist_num_rows; ?>)</span></a>

    <?php
        $select_cart_count = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $cart_num_rows = mysqli_num_rows($select_cart_count);
    ?>
    <a href="cart.php"><i class="bi bi-cart-fill"></i><span>(<?php echo $cart_num_rows; ?>)</span></a>
</div>


        <div class="account-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
        </div>




</header>
<head>

<link rel="stylesheet" href="css/style.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<style>
    .header .flex .icons {
   display: flex;
   align-items: center;
   justify-content: flex-end;
   gap: 1.5rem;
}

.header .flex .icons a,
.header .flex .icons div {
   display: flex;
   align-items: center;
   font-size: 2.5rem;
   color: var(--black);
   cursor: pointer;
   text-decoration: none;
}

.header .flex .icons span {
   font-size: 1.6rem;
   margin-left: 0.3rem;
   color: var(--black);
}
</style>
