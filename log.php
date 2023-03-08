<?php

include 'templates/func.php';
include 'templates/settings.php';

if (isset($_POST['reg_done']) || isset($_POST['log_done'])){
	$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
	conn_check($conn);
}

$error_array = array(
  "log_conn_error" => false,
  "log_fill_all_input_fields" => false,
  "log_incorrect_login_or_password" => false
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
	<link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Montserrat+Alternates:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,800&display=swap" rel="stylesheet">
	<title>Document</title>
</head>
<body class="regenlog_body">
	<header class="regenlog_header">
		<a class="back_button" href="index.php">На главную</a>
		<a class="reg_button" href="reg.php">Зарегистрироваться</a>
	</header>
	<main class="regenlog">
		    <form class="login_form" method="post">
				<h1 class="login_title">Вход в систему</h1>
				<p>Логин</p>
				<input class="login_item" type="text" name="log_login" value="<?php if (isset($_COOKIE['reg_login'])){ echo $_COOKIE['reg_login']; } ?>">
				<p>Пароль</p>
				<input class="login_item" type="password" name="log_password" value="<?php if (isset($_COOKIE['reg_password'])){ echo $_COOKIE['reg_password']; } ?>">
				<?php 
					log_warning($error_array['log_incorrect_login_or_password'], "Неправильный логин или пароль");
					log_warning($error_array['log_fill_all_input_fields'], "Заполните все поля");
					if ($error_array['log_conn_error']){ log_warning($error_array['log_conn_error'], "Ошибка: " . $conn->error); };
					if (isset($_GET['please_log'])){ echo "<p class=''> Пожалуйста авторизуйтесь</p>"; }
          if (isset($_GET['reg'])){ echo "<p class=''>Регистрация прошла успешно, пожалуйста авторизуйтесь</p>"; }
				?>
                <button class="button_login" type="submit" name="log_done">Войти </button>
                <img class="first_img" src="img/A.png" alt="">
				<img class="second_img" src="img/Book_open.png" alt="">
				<img class="third_img" src="img/C.png" alt="">
				<img class="fourth_img" src="img/computer.png" alt="">
				<img class="fifth_img" src="img/person_studying.png" alt="">
				<img class="sixth_img" src="img/B.png" alt="">
			</form>
	</main>
	<?php include "templates/footer.html"; ?>
</body>
</html>