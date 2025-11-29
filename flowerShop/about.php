<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>




 
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">
    <div class="flex about-bg">
        <div class="content">
            <h3>why choose us?</h3>
            <p>We offer high-quality products, fast delivery, and exceptional customer service to ensure your satisfaction every step of the way.</p>
            <a href="shop.php" class="btn">shop now</a>
        </div>
    </div>
</section>

<section class="reviews" id="reviews">
    <h1 class="title">client's reviews</h1>
    <div class="box-container">
        <div class="box">
            <img src="profil.png" alt="">
            <p>Wonderful Experience!</p>
            <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <h3>Zeynep</h3>
        </div>
        <div class="box">
            <img src="profil.png" alt="">
            <p>they're the best</p>
            <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <h3>Ceren</h3>
        </div>
        <div class="box">
            <img src="profill.jpg" alt="">
            <p>I love it</p>
            <div class="stars">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
            </div>
            <h3>Ahmet</h3>
        </div>
    </div>
</section>

<?php @include 'footer.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="js/script.js"></script>

</body>
</html>

