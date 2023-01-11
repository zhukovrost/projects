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
          header('Location: account.php');
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,700&display=swap" rel="stylesheet">
	<title>Document</title>
</head>
<body class="regenlog_body">
	<header class="default_header">
		<a href="index.php">Назад</a>
		<a class="log" href="reg.php">Зарегистрироваться</a>
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
                <div class="button_login">
                    <input type="submit" class="button" value="Войти" name="log_done">
                </div>
			
                <svg class="first_svg" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 64 64" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m35.659 34.623-8.659-1.623v-6h-10v6l-8.659 1.623a9 9 0 0 0 -7.341 8.846v19.531h42v-19.531a9 9 0 0 0 -7.341-8.846z" fill="#fecdaa" data-original="#fecdaa" class=""></path><path d="m35.659 34.623-8.659-1.623v-6h-8v8l-9.474 1.776a8 8 0 0 0 -6.526 7.863v18.361h40v-19.531a9 9 0 0 0 -7.341-8.846z" fill="#fdbe94" data-original="#fdbe94"></path><path d="m34 44.838v-10.526l-5-.937v2.625a7 7 0 0 1 -14 0v-2.625l-5 .937v10.526a4.387 4.387 0 0 1 -3 4.162v14h30v-14a4.387 4.387 0 0 1 -3-4.162z" fill="#899af5" data-original="#899af5"></path><path d="m34 44.838v-10.526l-5-.937v2.625a7 7 0 0 1 -14 0v-.25l-5 .938v8.15a4.387 4.387 0 0 1 -3 4.162v14h30v-14a4.387 4.387 0 0 1 -3-4.162z" fill="#7b8bf2" data-original="#7b8bf2"></path><path d="m21 26-9-12a7.237 7.237 0 0 1 -4-6.472v-4.528l.977.244a9.247 9.247 0 0 0 5.887-.471 21.916 21.916 0 0 1 8.636-1.773 12.5 12.5 0 0 1 12.5 12.5 12.5 12.5 0 0 1 -12.5 12.5z" fill="#be6552" data-original="#be6552" class=""></path><path d="m23.5 26a12.5 12.5 0 0 0 12.5-12.5 12.434 12.434 0 0 0 -2.724-7.776 12.434 12.434 0 0 0 -7.776-2.724 21.916 21.916 0 0 0 -8.636 1.773 9.254 9.254 0 0 1 -5.887.471l-.977-.244v2.172c0 1.81 2.719 5.547 4 6.828l7.625 12z" fill="#ac573d" data-original="#ac573d" class=""></path><path d="m31 18h1.5a2.5 2.5 0 0 1 2.5 2.5 2.5 2.5 0 0 1 -2.5 2.5h-1.5a0 0 0 0 1 0 0v-5a0 0 0 0 1 0 0z" fill="#fdbe94" data-original="#fdbe94"></path><path d="m9 18h1.5a2.5 2.5 0 0 1 2.5 2.5 2.5 2.5 0 0 1 -2.5 2.5h-1.5a0 0 0 0 1 0 0v-5a0 0 0 0 1 0 0z" fill="#fdbe94" transform="matrix(-1 0 0 -1 22 41)" data-original="#fdbe94"></path><path d="m22 31a10 10 0 0 1 -10-10v-7h20v7a10 10 0 0 1 -10 10z" fill="#ffdbc1" data-original="#ffdbc1"></path><path d="m23 30.95a10 10 0 0 0 9-9.95v-7h-1.528a7.237 7.237 0 0 1 -6.472-4 13.658 13.658 0 0 1 -9.657 4h-.343v7a10 10 0 0 0 9 9.95z" fill="#fecdaa" data-original="#fecdaa" class=""></path><path d="m22 28a2 2 0 0 1 -2-2v-1h4v1a2 2 0 0 1 -2 2z" fill="#f95f58" data-original="#f95f58"></path><path d="m11 51h22v12h-22z" fill="#fafaff" data-original="#fafaff"></path><path d="m40 29v-3h-2.729a5.271 5.271 0 0 0 -5.271 5.271 5.27 5.27 0 0 0 3.517 4.97l4.983 1.759v-3l-3.952-1.437a2.352 2.352 0 0 1 -1.548-2.211 2.352 2.352 0 0 1 2.352-2.352z" fill="#ffc239" data-original="#ffc239"></path><path d="m55 29v-3h2.729a5.271 5.271 0 0 1 5.271 5.271 5.27 5.27 0 0 1 -3.517 4.97l-4.983 1.759v-3l3.952-1.437a2.352 2.352 0 0 0 1.548-2.211 2.352 2.352 0 0 0 -2.352-2.352z" fill="#ffc239" data-original="#ffc239"></path><path d="m49 44h-3a8.775 8.775 0 0 1 -6-8.325v-11.675h15v11.675a8.775 8.775 0 0 1 -6 8.325z" fill="#ffd86e" data-original="#ffd86e"></path><path d="m49 44h-3a7.237 7.237 0 0 1 -4-6.472v-11.528h13v9.675a8.775 8.775 0 0 1 -6 8.325z" fill="#ffce4a" data-original="#ffce4a"></path><path d="m46 44h3v16h-3z" fill="#ffc239" data-original="#ffc239"></path><path d="m41 59h13v4h-13z" fill="#ffd86e" data-original="#ffd86e"></path><path d="m43 61h11v2h-11z" fill="#ffce4a" data-original="#ffce4a"></path><rect fill="#fecdaa" height="7" rx="2" transform="matrix(0 1 -1 0 98.5 7.5)" width="12" x="39.5" y="49.5" data-original="#fecdaa" class=""></rect><path d="m47 47h-2.865a3.48 3.48 0 0 0 -.635 2v8a3.48 3.48 0 0 0 .635 2h2.865a2 2 0 0 0 2-2v-8a2 2 0 0 0 -2-2z" fill="#fdbe94" data-original="#fdbe94"></path><rect fill="#fecdaa" height="12" rx="2" width="7" x="45" y="47" data-original="#fecdaa" class=""></rect><path d="m20 54.75h3.708l-3.359 5.878a.749.749 0 0 0 .279 1.023.749.749 0 0 0 1.023-.279l4-7a.75.75 0 0 0 -.651-1.122h-5a.75.75 0 0 0 0 1.5z" fill="#000000" data-original="#000000" class=""></path><circle cx="27" cy="18" r="1" fill="#000000" data-original="#000000" class=""></circle><circle cx="17" cy="18" r="1" fill="#000000" data-original="#000000" class=""></circle><path d="m22 23a.75.75 0 0 0 .75-.75v-2.5a.75.75 0 0 0 -1.5 0v2.5a.75.75 0 0 0 .75.75z" fill="#000000" data-original="#000000" class=""></path><path d="m24.75 26v-1a.75.75 0 0 0 -.75-.75h-4a.75.75 0 0 0 -.75.75v1a2.75 2.75 0 0 0 5.5 0zm-4 0v-.25h2.5v.25a1.25 1.25 0 0 1 -2.5 0z" fill="#000000" data-original="#000000" class=""></path><path d="m57.729 25.25h-1.979v-1.25a.75.75 0 0 0 -.75-.75h-15a.75.75 0 0 0 -.75.75v1.25h-1.979a6.014 6.014 0 0 0 -5.738 7.837l-3.783-.709v-2.31a10.774 10.774 0 0 0 4.63-6.318h.12a3.254 3.254 0 0 0 3.25-3.25 3.221 3.221 0 0 0 -.3-1.341 13.068 13.068 0 0 0 1.3-5.659 13.265 13.265 0 0 0 -13.25-13.25 22.559 22.559 0 0 0 -8.931 1.833 8.55 8.55 0 0 1 -5.41.434l-.977-.245a.763.763 0 0 0 -.644.137.751.751 0 0 0 -.288.591v4.527a7.925 7.925 0 0 0 4 6.9v2.849a3.242 3.242 0 0 0 .25 6.475h.12a10.774 10.774 0 0 0 4.63 6.318v2.31l-8.05 1.507a9.757 9.757 0 0 0 -7.95 9.584v19.53a.75.75 0 0 0 .75.75h53a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 0 -.75-.75h-1.565a2.709 2.709 0 0 0 .315-1.25v-8a2.752 2.752 0 0 0 -2.75-2.75h-.25v-1.732a9.5 9.5 0 0 0 5.567-6.012l4.416-1.558a6.021 6.021 0 0 0 -2-11.7zm-20.458 1.5h1.979v1.5h-1.9a3.1 3.1 0 0 0 -1.06 6.019l2.957 1.075v.332a9.542 9.542 0 0 0 .072 1.113l-3.555-1.255a4.521 4.521 0 0 1 1.5-8.784zm1.979 3v4l-2.444-.889a1.6 1.6 0 0 1 .547-3.108zm-6.576-7.518a10.9 10.9 0 0 0 .076-1.232v-2.225a1.741 1.741 0 0 1 -.076 3.457zm-22.924-1.732a1.748 1.748 0 0 1 1.5-1.725v2.225a10.9 10.9 0 0 0 .076 1.232 1.746 1.746 0 0 1 -1.576-1.732zm-1-12.973v-3.567l.046.012a10.051 10.051 0 0 0 6.363-.51 21.065 21.065 0 0 1 8.341-1.712 11.763 11.763 0 0 1 11.75 11.75 11.554 11.554 0 0 1 -.863 4.365 3.22 3.22 0 0 0 -1.637-.59v-3.275a.75.75 0 0 0 -.75-.75h-1.528a6.45 6.45 0 0 1 -5.8-3.585.75.75 0 0 0 -1.2-.195 12.826 12.826 0 0 1 -9.127 3.78h-2.145a6.445 6.445 0 0 1 -3.45-5.723zm4 13.473v-6.25h1.593a14.3 14.3 0 0 0 9.481-3.559 7.936 7.936 0 0 0 6.648 3.559h.778v6.25a9.25 9.25 0 0 1 -18.5 0zm3 13 1.389-.261a.749.749 0 0 0 .611-.739v-2.13a10.7 10.7 0 0 0 8.5 0v2.13a.749.749 0 0 0 .612.737l1.388.263v2a6.25 6.25 0 0 1 -12.5 0zm-8.75 10.25a.75.75 0 0 0 -.75.75v17.25h-4.5v-18.78a8.256 8.256 0 0 1 6.729-8.11l.771-.144v9.622a3.629 3.629 0 0 1 -1.5 2.94v-2.778a.75.75 0 0 0 -.75-.75zm25.25 18h-20.5v-10.5h20.5zm4 0h-2.5v-11.25a.75.75 0 0 0 -.75-.75h-22a.75.75 0 0 0 -.75.75v11.25h-2.5v-12.74a5.133 5.133 0 0 0 3-4.672v-9.9l3.5-.656v1.718a7.75 7.75 0 0 0 15.5 0v-1.721l2.628.492a6.026 6.026 0 0 0 .872.956v9.111a5.133 5.133 0 0 0 3 4.672zm4.75-4a.75.75 0 0 0 -.75.75v3.25h-2.5v-17.25a.75.75 0 0 0 -1.5 0v2.778a3.629 3.629 0 0 1 -1.5-2.94v-8.119c.171.079.337.166.517.229l4.416 1.558a9.489 9.489 0 0 0 2.5 4.043 7.966 7.966 0 0 1 .063.921v3.43a2.733 2.733 0 0 0 -1 2.1v8a2.709 2.709 0 0 0 .315 1.25zm12.25 1.5v2.5h-11.5v-2.5zm-9.25-12h.565a2.709 2.709 0 0 0 -.315 1.25v8a2.709 2.709 0 0 0 .315 1.25h-.565a1.252 1.252 0 0 1 -1.25-1.25v-8a1.252 1.252 0 0 1 1.25-1.25zm-.25-1.475v-2.507a9.678 9.678 0 0 0 1.5.75v1.732h-1.25c-.086 0-.166.018-.25.025zm6.25 1.475a1.252 1.252 0 0 1 1.25 1.25v8a1.252 1.252 0 0 1 -1.25 1.25h-3a1.252 1.252 0 0 1 -1.25-1.25v-.25h3.25a.75.75 0 0 0 0-1.5h-3.25v-1.5h3.25a.75.75 0 0 0 0-1.5h-3.25v-1.5h3.25a.75.75 0 0 0 0-1.5h-3.25v-.25a1.252 1.252 0 0 1 1.25-1.25zm-3.25-1.5v-1.5h1.5v1.5zm7.5-10.574a8.013 8.013 0 0 1 -5.374 7.574h-2.752a8.013 8.013 0 0 1 -5.374-7.574v-10.926h13.5zm1.5-5.926h1.9a1.6 1.6 0 0 1 .548 3.108l-2.445.89zm3.483 5.784-3.555 1.255a9.542 9.542 0 0 0 .072-1.113v-.332l2.958-1.075a3.1 3.1 0 0 0 -1.061-6.019h-1.9v-1.5h1.979a4.521 4.521 0 0 1 1.5 8.784z" fill="#000000" data-original="#000000" class=""></path></g></svg>
                <img class="second_svg" src="img/icons/bench-barbel.png" alt="">
				<img class="third_svg" src="img/icons/diet.png" alt="">
				<img class="fourth_svg" src="img/icons/growth.png" alt="">
				<img class="fifth_svg" src="img/icons/muscle.png" alt="">
				<img class="sixth_svg" src="img/icons/ok.png" alt="">
			</form>
	</main>
</body>
<?php include "templates/footer.html"; ?>
</html>