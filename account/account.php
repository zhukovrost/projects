<?php

include "../func.php";
include '../settings.php';

check_the_login();

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$error_array = array(
  "empty_form" => false,
  "select_error" => false,
  "update_error" => false,
  "update_success" => false,
  "too_long_string" => false
);

if (isset($_POST['exit'])){
  setcookie("login", "");
  header("Location: regenlog.php");
}

$login = $_COOKIE['login'];

# ---------------------------- update user data ------------------------------

if  (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['thirdname'])){
  if ($_POST['name'] != "" && $_POST['surname'] != "" && $_POST['thirdname'] != ""){
    if (strlen($_POST['name']) <= 32 && strlen($_POST['surname']) <= 32 && strlen($_POST['thirdname']) <= 32){
      $update_sql = "UPDATE users SET name='".$_POST['name']."', surname='".$_POST['surname']."', thirdname='".$_POST['thirdname']."' WHERE login='".$login."'";
      if ($conn -> query($update_sql)){
        $error_array["update_success"] = true;
      }else{
        $error_array["update_error"] = true;
      }
    }else{
      $error_array["too_long_string"] = true;
    }
  }else{
    $error_array["empty_form"] = true;
  }
}

# ------------------------- select user data -----------------------------

$select_sql = "SELECT name, surname, thirdname, email, LENGTH(password) FROM users WHERE login='".$_COOKIE['login']."'";
if ($data_array = $conn -> query($select_sql)){
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
}else{
  $error_array["select_error"] = true;
}


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
      <div class="together">
        <img class="avatar" src="../img/images/user.png" style="margin-right: 30px;">
        <label style="font-size: 50px; margin-top: auto; margin-bottom: auto;">Профиль <span style="color: #FF0000"><?php echo $login; ?></span></label>
      </div>
    <form method="post" id="user">
        <div class="together">
          <p style="margin-right: 10px;">Имя</p>
          <input class="card_input" name="name" value="<?php echo $name; ?>">
        </div>
        <div class="together">
          <p style="margin-right: 10px;">Фамилия</p>
          <input class="card_input" name="surname" value="<?php echo $surname; ?>">
        </div>
        <div class="together">
          <p style="margin-right: 10px;">Отчество</p>
          <input class="card_input" name="thirdname" value="<?php echo $thirdname; ?>">
      </div>
    </form>

    <?php
    # --------------------- errors -------------------------

    if ($error_array["update_success"]){ echo "<p style='color: lime'>Данные пользователя успешно обновлены</p>"; }
    if ($error_array["update_error"]){ echo "<p style='color: #FF0000'>Ошибка обновления данных</p>"; }
    if ($error_array["empty_form"]){ echo "<p style='color: #FF0000'>Ошибка: заполните форму</p>"; }
    if ($error_array["select_error"]){ echo "<p style='color: #FF0000'>Ошибка: не удалось найти пользователя</p>"; }
    if ($error_array["too_long_string"]){ echo "<p style='color: #FF0000'>Ошибка: слишком длинное имя/фамилия/отчество</p>"; }

    ?>

    <p style="margin-right: 10px;">Электронная почта: <?php echo $email ?></p>
    <p style="margin-right: 10px;">Пароль: <?php echo $password ?></p>

    <form method="post" id="exit">
      <input type="hidden" value="true" name="exit">
    </form>
    <button form="exit" type="submit" class="exit_button"><img style="height: 30px; margin-right: 5px;" src="../img/images/logout.png"> <label>Выйти из аккаунта</label></button>
  </main>

  <script src="../main.js"></script>
</body>
</html>

<?php $conn->close(); ?>
