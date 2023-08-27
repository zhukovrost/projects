<?php

include 'templates/func.php';
include 'templates/settings.php';

$title = "Registration";
if ($user_data->get_auth()){
    header("Location: index.php");
}

# ------------------ registration ------------------------

if (isset($_POST['reg_done'])){
    $user = new User($conn);
    $error_array = $user->reg($conn, $_POST['code'], $_POST['reg_login'], $_POST['reg_password'], $_POST['reg_password2'], $_POST['reg_name'], $_POST['reg_surname'], $_POST['reg_thirdname']);
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
<body class="reg_body">
<!-- Header only for reg and log -->
<header>
  <a class="logo log_reg_logo" href="index.php"><p>24</p><p>Roman</p></a>
</header>

<main>
  <!-- Registration form -->
  <form class="reg_form" action="" method="post">
    <h1>Registration</h1>
    <div class="content">
        <div class="left">
            <label for="login">Login</label>
            <input type="text" id="login" name="reg_login">
            <label for="password">Password</label>
            <input id="password" type="password" name="reg_password">
            <label for="confirm_password">Confirm the password</label>
            <input id="confirm_password" type="password" name="reg_password2">
            <input type="password" name="code" placeholder="enter secret code here">
        </div>
      <div class="right">
        <label for="name">Name</label>
        <input type="text" id="name" name="reg_name">
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="reg_surname">
        <label for="thirdname">Thirdname(optional)</label>
        <input type="text" id="thirdname" name="reg_thirdname">
      </div>
    </div>
    <?php
      reg_warning($error_array['reg_login_is_used'], "This login is not available");
      reg_warning($error_array['reg_passwords_are_not_the_same'], "Passwords are not equal, try again");
      reg_warning($error_array['reg_fill_all_input_fields'], "Fill all the fields");
      reg_warning($error_array["too_long_string"], "Too long string");
      reg_warning($error_array["incorrect_code"], "Incorrect secret code");
      if ($error_array['reg_conn_error']){ reg_warning($error_array['reg_conn_error'], "Error: " . $conn->error); }
    ?>
    <button class="reg_button" type="submit" name="reg_done">Submit</button>
    <a href="log.php">Login</a>
  </form>
</main>

<!-- Footer only for reg and log -->
<?php include "templates/footer.html"; ?>
<script src="templates/format.js"></script>
</body>
</html>