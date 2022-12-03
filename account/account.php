

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sport - account</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
	<main>
			<div class="container container_account">
			<a class="home_account" href="../index.html"><img src="../img/Home.svg" alt=""></a>
					<p class="account_title">Данные пользователя <span class="account_title_user"><?php echo $userlogin; ?></span>:</p>
					<div class="data_block">
							<p>Фамилия: <?php echo $surname; ?></p>
							<p>Имя: <?php echo $name; ?></p>
							<p>Отчество: <?php if ($thirdname == ""){ echo "Отсутствует"; }else{ echo $thirdname; } ?></p>
							<p>Email: <?php echo $email; ?></p>
							<p>Пароль: <?php echo $password; ?></p>
					</div>
			</div>
		</main>
</body>
</html>