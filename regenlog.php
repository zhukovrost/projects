<?php

include 'templates/func.php';
include 'templates/settings.php';

if (isset($_POST['reg_done']) || isset($_POST['log_done'])){
	$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
	conn_check($conn);
}

$error_array = array(
  "reg_fill_all_input_fields" => false,
  "reg_login_is_used" => false,
  "reg_passwords_are_not_the_same" => false,
  "reg_conn_error" => false,
  "log_conn_error" => false,
  "log_fill_all_input_fields" => false,
  "log_incorrect_login_or_password" => false,
  "reg_success" => false,
  "too_long_string" => false
);

# ------------------ login -----------------------

if (isset($_POST['log_done'])){
	if ($_POST["log_login"] == "" || $_POST["log_password"] == ""){
		$error_array['log_fill_all_input_fields'] = true;
	}else{
		$log_login = $_POST['log_login'];
		$log_password = $_POST['log_password'];

		$log_sql = "SELECT * FROM users WHERE login='".$log_login."' AND password='".$log_password."'";

		if($log_result = $conn->query($log_sql)){
				$rowsCount = $log_result->num_rows;
				if ( $rowsCount != 0 ){
          setcookie("login", $log_login);
          header('Location: index.php');
          #header('Location: account.php');
				}else{
						$error_array['log_incorrect_login_or_password'] = true;
				}
				$log_result->free();
		}else{
			$error_array['log_conn_error'] = true;
		}
			$conn->close();
	}
}

# ------------------ registration ------------------------

if (isset($_POST['reg_done'])){
	if ($_POST['reg_sex'] == "Укажите ваш пол" || $_POST['reg_date_of_birth'] == null || $_POST['reg_login'] == "" || $_POST['reg_password'] == "" || $_POST['reg_password2'] == "" || $_POST['reg_name'] == "" || $_POST['reg_surname'] == "" || $_POST['reg_email'] == ""){
		$error_array['reg_fill_all_input_fields'] = true;
	}else{
			if ($_POST['reg_password'] == $_POST['reg_password2']){
				$reg_login = trim($_POST['reg_login']);
				$reg_password = trim($_POST['reg_password']);
				$reg_name = trim($_POST['reg_name']);
				$reg_surname = trim($_POST['reg_surname']);
				$reg_email = trim($_POST['reg_email']);
        $reg_sex = $_POST['reg_sex'];
        $reg_date_of_birth = $_POST['reg_date_of_birth'];
        if (strlen($reg_login) < 33 && strlen($reg_password) < 33 && strlen($reg_surname) < 33 && strlen($reg_email) < 33) {
          $reg_sql = "SELECT * FROM users WHERE login='" . $reg_login . "'";
          if ($reg_result = $conn->query($reg_sql)) {
            $rowsCount = $reg_result->num_rows;
            if ($rowsCount == 0) {
              if (isset($_POST['reg_thirdname'])) {
                $reg_thirdname = trim($_POST['reg_thirdname']);
                if (strlen($reg_thirdname) > 32){ $error_array["too_long_string"] = true; }
                $reg_sql2 = "INSERT INTO users(login, password, name, surname, thirdname, email, date_of_birth, sex, weight, height, program, program_duration) VALUES('" . $reg_login . "', '" . $reg_password . "', '" . $reg_name . "', '" . $reg_surname . "', '" . $reg_thirdname . "', '" . $reg_email ."', '".$reg_date_of_birth."', '".$reg_sex."', 0, 0, 0, 0)";
              } else {
                $reg_sql2 = "INSERT INTO users(login, password, name, surname, thirdname, email, date_of_birth, sex, weight, height, program, program_duration) VALUES('" . $reg_login . "', '" . $reg_password . "', '" . $reg_name . "', '" . $reg_surname . "', null, '" . $reg_email . "', '".$reg_date_of_birth."', '".$reg_sex."', 0, 0, 0, 0)";
              }

              if (!$error_array["too_long_string"]){
                if ($conn->query($reg_sql2)) {
                  $error_array['reg_success'] = true;
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
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/format.css">
	<title>Document</title>
</head>
<header>
  <a href="index.php">Назад</a>
</header>
<body>
	<main>
		
		<div class="container regenlog">
				<form class="login_form" method="post">
					<h1 class="login_title">Вход в систему</h1>
					<p>Логин</p>
					<input class="login_item" type="text" name="log_login" value="<?php if ($error_array['reg_success']){ echo $reg_login; } ?>">
					<p>Пароль</p>
					<input class="login_item" type="password" name="log_password" value="<?php if ($error_array['reg_success']){ echo $reg_password; } ?>">
					<?php 
					log_warning($error_array['log_incorrect_login_or_password'], "Неправильный логин или пароль");
					log_warning($error_array['log_fill_all_input_fields'], "Заполните все поля");
					if ($error_array['log_conn_error']){ log_warning($error_array['log_conn_error'], "Ошибка: " . $conn->error); };
					if (isset($_GET['please_log'])){ echo "<p class=''> Пожалуйста авторизуйтесь</p>"; }
					?>
					<input class="button_login" type="submit" class="button" value="Войти" name="log_done">
				</form>
      <form class="registration_form" method="post">
				<h1 class="">Регистрация</h1>
				<p>Логин</p>
				<input class="" type="text" name="reg_login">
				<p>Фамилия</p>
				<input class="" type="text" name="reg_surname">
				<p>Имя</p>
				<input class="" type="text" name="reg_name">
				<p>Отчество (опционально)</p>
				<input class="" type="text" name="reg_thirdname">
        <p>Пол</p>
        <select name="reg_sex">
          <option value="Укажите ваш пол">Укажите ваш пол</option>
          <option value="man">Мужской</option>
          <option value="woman">Женский</option>
        </select>
        <p>Дата рождения</p>
        <input type="date" name="reg_date_of_birth">
				<p>Почта</p>
				<input class="" type="email" name="reg_email">
				<p>Пароль</p>
				<input class="" type="password" name="reg_password">
				<p>Подтвердите пароль</p>
				<input class="" type="password" name="reg_password2">
				<?php
        reg_warning($error_array['reg_login_is_used'], "Данный логин занят");
        reg_warning($error_array['reg_passwords_are_not_the_same'], "Пароли не совпадают, попробуйте ещё раз");
        reg_warning($error_array['reg_fill_all_input_fields'], "Заполните все поля");
        reg_warning($error_array["too_long_string"], "Слишком много символов");
        if ($error_array['reg_conn_error']){ reg_warning($error_array['reg_conn_error'], "Ошибка: " . $conn->error); };
        if (empty($_POST['reg_done']) || $error_array['reg_passwords_are_not_the_same'] || $error_array['reg_login_is_used'] || $error_array['reg_fill_all_input_fields'] || $error_array['too_long_string']){
          echo '<input class="button_login" type="submit" name="reg_done" value="Зарегистрироваться">';
        }
        if ($error_array['reg_success']){ echo "<p class='success'>Регистрация прошла успешно</p>"; }
				?>
			</form>
		</main>
	</body>
<?php include "templates/footer.html"; ?>
</html>