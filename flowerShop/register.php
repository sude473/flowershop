<?php
@include 'config.php';

if (isset($_POST['submit'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   $user_type = 'user';

   $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
   mysqli_stmt_bind_param($stmt, 's', $email);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   if (mysqli_num_rows($result) > 0) {
      $message[] = 'User already exists!';
   } else {
      if ($pass !== $cpass) {
         $message[] = 'Confirm password does not match!';
      } else {
         $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
         $insert = mysqli_prepare($conn, "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
         mysqli_stmt_bind_param($insert, 'ssss', $name, $email, $hashed_pass, $user_type);
         mysqli_stmt_execute($insert);

         $message[] = 'Registered successfully!';
         header('Location: login.php');
         exit();
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Register</title>
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '<div class="message"><span>' . $msg . '</span><i class="bi bi-x" onclick="this.parentElement.remove();"></i></div>';
   }
}
?>

<section class="form-container">
   <form action="" method="post">
      <h3>Register Now</h3>
      <input type="text" name="name" class="box" placeholder="Enter your username" required>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
      <input type="submit" class="btn" name="submit" value="Register Now">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</section>

</body>
</html>
