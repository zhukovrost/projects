<?php

include 'templates/func.php';
include 'templates/settings.php';

# ------------------ login -----------------------

if (isset($_POST['log_done'])){
	$error_array = $user_data->authenticate($conn, $_POST['log_login'], $_POST['log_password']);
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/format.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <title>24 / Roman</title>
</head>
<body class="log_body">
<!-- Header only for log and reg -->
<header>
  <a class="logo log_reg_logo" href="index.php"><p>24</p><p>Roman</p></a>
</header>

<main>
  <!-- Login form -->
  <form class="log_form" action="" method="post">
    <h1>Login</h1>
    <div class="content">
      <label for="login">Login</label>
      <input type="text" id="login" name="log_login" value="<?php if (isset($_SESSION['reg_login'])){ echo $_SESSION['reg_login']; } ?>">
      <label for="password">Password</label>
      <input type="password" id="password" name="log_password">
      <a href="">Forgot password</a>
    </div>
    <?php
      log_warning($error_array['log_incorrect_login_or_password'], "Неправильный логин или пароль");
      log_warning($error_array['log_fill_all_input_fields'], "Заполните все поля");
      if ($error_array['log_conn_error']){ log_warning($error_array['log_conn_error'], "Ошибка: " . $conn->error); };
      if (isset($_GET['please_log'])){ echo "<p class=''> Пожалуйста авторизуйтесь</p>"; }
      if (isset($_GET['reg'])){ echo "<p class=''>Регистрация прошла успешно, пожалуйста авторизуйтесь</p>"; }
    ?>
    <button class="log_button" type="submit" name="log_done">Submit</button>
    <a href="reg.php">Registration</a>
  </form>
</main>

<!-- Footer only for log and reg -->
<footer class="reg_log_footer">
  <div class="contacts">
    <p>telephone: +7 999-999-99-99</p>
    <p>email: aaaaaaaa@aaa.aaaaaaaa</p>
  </div>
  <div class="company">
    <p>Created by: El Primo</p>
    <p>All rights are reserved</p>
  </div>
</footer>

<script src="../templates/format.js"></script>
</body>
</html>