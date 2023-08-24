<?php

include 'templates/func.php';
include 'templates/settings.php';

$title = "Registration";

$error_array = array(
  "reg_fill_all_input_fields" => false,
  "reg_login_is_used" => false,
  "reg_passwords_are_not_the_same" => false,
  "reg_conn_error" => false,
  "reg_success" => false,
  "too_long_string" => false,
  "adding_stats" => false
);

# ------------------ registration ------------------------

if (isset($_POST['reg_done'])){
	if ($_POST['reg_login'] == "" || $_POST['reg_password'] == "" || $_POST['reg_password2'] == "" || $_POST['reg_name'] == "" || $_POST['reg_surname'] == ""){
		$error_array['reg_fill_all_input_fields'] = true;
	}else{
			if ($_POST['reg_password'] == $_POST['reg_password2']){
				$reg_login = trim($_POST['reg_login']);
				$reg_password = trim($_POST['reg_password']);
				$reg_name = trim($_POST['reg_name']);
				$reg_surname = trim($_POST['reg_surname']);
        if (strlen($reg_login) < 33 && strlen($reg_password) < 33 && strlen($reg_surname) < 33) {
          $reg_sql = "SELECT * FROM users WHERE login='" . $reg_login . "'";
          if ($reg_result = $conn->query($reg_sql)) {
            $rowsCount = $reg_result->num_rows;
            if ($rowsCount == 0) {
              if (isset($_POST['reg_thirdname'])) {
                $reg_thirdname = trim($_POST['reg_thirdname']);
                if (strlen($reg_thirdname) > 32){ $error_array["too_long_string"] = true; }
                $reg_sql2 = "INSERT INTO users(login, password, name, surname, thirdname) VALUES('".$reg_login."', '".md5($reg_password)."', '".$reg_name."', '".$reg_surname."', '".$reg_thirdname."')";
              } else {
                $reg_sql2 = "INSERT INTO users(login, password, name, surname) VALUES('".$reg_login."', '".md5($reg_password)."', '".$reg_name."', '".$reg_surname."')";
              }

              if (!$error_array["too_long_string"]){
                if ($conn->query($reg_sql2)) {
                  $reg_sql3 = "INSERT INTO stats(user) VALUES (LAST_INSERT_ID())";
                  if ($conn->query($reg_sql3)){
                    $_SESSION["reg_login"] = $reg_login;
                    $_SESSION["reg_password"] = $reg_password;
                    header("Location: log.php?reg=1");
                  }else{
                    $error_array['adding_stats'] = true;
                  }
                } else {
                  $error_array['reg_conn_error'] = true;
                }
              }

            } else {
              $error_array['reg_login_is_used'] = true;
            }
          } else {
            $error_array['reg_conn_error'] = true;
          }
        }else{
          $error_array["too_long_string"] = true;
        }
				$conn->close();
		}else{
				$error_array['reg_passwords_are_not_the_same'] = true;
		}
	}
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
        <label for="name">Name</label>
        <input type="text" id="name" name="reg_name">
        <label for="surname">Surname</label>
        <input type="text" id="surname" name="reg_surname">
        <label for="thirdname">Thirdname(optional)</label>
        <input type="text" id="thirdname" name="reg_thirdname">
      </div>
      <div class="right">
        <label for="login">Login</label>
        <input type="text" id="login" name="reg_login">
        <label for="password">Password</label>
        <input id="password" type="password" name="reg_password">
        <label for="confirm_password">Confirm the password</label>
        <input id="confirm_password" type="password" name="reg_password2">
      </div>
    </div>
    <?php
      reg_warning($error_array['reg_login_is_used'], "Данный логин занят");
      reg_warning($error_array['reg_passwords_are_not_the_same'], "Пароли не совпадают, попробуйте ещё раз");
      reg_warning($error_array['reg_fill_all_input_fields'], "Заполните все поля");
      reg_warning($error_array["too_long_string"], "Слишком много символов");
      if ($error_array['reg_conn_error']){ reg_warning($error_array['reg_conn_error'], "Ошибка: " . $conn->error); }
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