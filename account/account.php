<?php
/*
include "../func.php";
include '../settings.php';

check_the_login();

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$error_array = array(

);

if (isset($_POST['exit'])){
  setcookie("login", "");
  header("Location: regenlog.php");
}

$select_sql = "SELECT name, surname, thirdname, email, LENGTH(password) FROM users WHERE login='".$_COOKIE['login']."'";
if ($data_array = $conn -> query($select_sql)){
  $login = $_COOKIE['login'];
  foreach ($data_array as $data) {
    $name = $data['name'];
    $surname = $data['surname'];
    $email = $data['email'];
    if ($data['thirdname'] == '' || $data['thirdname'] == null) {
      $thirdname = "Отсутствует";
    } else {
      $thirdname = $data['thirdname'];
    }
    $password = "";
    for ($i = 0; $i < $data['LENGTH(password)']; $i++) {
      $password .= '*';
    }

  }
  $data_array -> free();
}
*/

$login = "rostik_krutoy_chel";
$password = "*******";
$email = "rostik@gmail.com";
$surname = "Жуков";
$thirdname = "Сергеевич";
$name = "Ростислав";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sport - account</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body style="height: 100vh">
  <main class="profile_card">
    <form method="post" id="user">
      <div class="together">
        <img class="avatar" src="../img/images/user.png" style="margin-right: 30px;">
        <label style="font-size: 50px; margin-top: auto; margin-bottom: auto;">Профиль <span style="color: #FF0000"><?php echo $login; ?></span></label>
      </div>
      <div class="together">
        <p style="margin-right: 10px;">Имя</p>
        <input class="card_input" value="<?php echo $name; ?>">
      </div>
      <div class="together">
        <p style="margin-right: 10px;">Фамилия</p>
        <input class="card_input" value="<?php echo $surname; ?>">
      </div>
      <div class="together">
        <p style="margin-right: 10px;">Отчество</p>
        <input class="card_input" value="<?php echo $thirdname; ?>">
      </div>
      <p style="margin-right: 10px;">Электронная почта: <?php echo $email ?></p>
        <p style="margin-right: 10px;">Пароль: <?php echo $password ?></p>
    </form>
    <script src="../main.js"></script>
    <form method="post" id="exit">
      <input type="hidden" value="true" name="exit">
    </form>
    <button form="exit" type="submit" class="exit_button"><img style="height: 30px; margin-right: 5px;" src="../img/images/logout.png"> <label>Выйти из аккаунта</label></button>
  </main>
</body>
</html>