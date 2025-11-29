<?php

@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if(!$user_id){
   header('location:login.php');
   exit;
}

$message = [];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])){

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $number = mysqli_real_escape_string($conn, trim($_POST['number']));
    $msg = mysqli_real_escape_string($conn, trim($_POST['message']));

    $check_query = "SELECT * FROM `message` WHERE user_id = '$user_id' AND name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'";
    $select_message = mysqli_query($conn, $check_query) or die('Query failed');

    if(mysqli_num_rows($select_message) > 0){
        $message[] = 'You already sent this message!';
    } else {
        $insert_query = "INSERT INTO `message`(user_id, name, email, number, message) 
                         VALUES('$user_id', '$name', '$email', '$number', '$msg')";
        mysqli_query($conn, $insert_query) or die('Insert failed');
        $message[] = 'Message sent successfully!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>


   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Contact Us</h3>
    <p> <a href="home.php">Home</a> / Contact </p>
</section>

<section class="contact">

    <?php
    if (!empty($message)) {
        foreach($message as $msg){
            echo '<div class="message-box">'.$msg.'</div>';
        }
    }
    ?>

    <form action="" method="POST">
        <h3>Send us a message!</h3>
        <input type="text" name="name" placeholder="Enter your name" class="box" required>
        <input type="email" name="email" placeholder="Enter your email" class="box" required>
        <input type="number" name="number" placeholder="Enter your number" class="box" required>
        <textarea name="message" class="box" placeholder="Enter your message" required cols="30" rows="10"></textarea>
        <input type="submit" value="Send Message" name="send" class="btn">
    </form>

</section>

<?php @include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>

