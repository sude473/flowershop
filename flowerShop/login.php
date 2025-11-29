<?php
@include 'config.php';
session_start();

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
   $token = mysqli_real_escape_string($conn, $_COOKIE['remember_token']);
   $check = mysqli_prepare($conn, "SELECT * FROM users WHERE remember_token = ? AND token_expiry > NOW()");
   mysqli_stmt_bind_param($check, 's', $token);
   mysqli_stmt_execute($check);
   $result = mysqli_stmt_get_result($check);

   if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      $_SESSION['user_email'] = $row['email'];

      if ($row['user_type'] === 'admin') {
         header('Location: admin_page.php');
      } else {
         header('Location: home.php');
      }
      exit();
   }
}

if (isset($_POST['submit'])) {
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $password_input = $_POST['pass'];

   $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
   mysqli_stmt_bind_param($stmt, 's', $email);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);

   if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      if (password_verify($password_input, $row['password'])) {
         $_SESSION['user_id'] = $row['id'];
         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];

         if ($row['user_type'] === 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
         }

         if (isset($_POST['remember'])) {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', time() + (86400 * 30));

            setcookie('remember_token', $token, time() + (86400 * 30), "/", "", true, true);

            $update = mysqli_prepare($conn, "UPDATE users SET remember_token = ?, token_expiry = ? WHERE id = ?");
            mysqli_stmt_bind_param($update, 'ssi', $token, $expiry, $row['id']);
            mysqli_stmt_execute($update);
         } else {
            setcookie('remember_token', '', time() - 3600, "/", "", true, true);
         }

         if ($row['user_type'] === 'admin') {
            header('Location: admin_page.php');
         } else {
            header('Location: home.php');
         }
         exit();
      } else {
         $message[] = 'Incorrect email or password!';
      }
   } else {
      $message[] = 'Incorrect email or password!';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Login</title>
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
      <h3>Login Now</h3>
      <input type="email" name="email" class="box" placeholder="Enter your email" required>
      <input type="password" name="pass" class="box" placeholder="Enter your password" required>
      <div class="form-group d-flex align-items-center">
         <label class="mb-0 ml-1">
            <input type="checkbox" name="remember" style="margin-right: 5px;">Remember me
         </label>
      </div>
      <input type="submit" class="btn" name="submit" value="Login Now">
      <p>Don't have an account? <a href="register.php">Register now</a></p>
   </form>
</section>

</body>
</html>

