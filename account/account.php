<?php

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
} else {

  $select_sql = "SELECT name, surname, thirdname, email, LENGTH(password) FROM users WHERE login='".$_COOKIE['login']."'";
  if ($data_array = $conn -> query($select_sql)){
    $login = $_COOKIE['userlogin'];
    foreach ($data_array as $data){
      $name = $data['name'];
      $surname = $data['surname'];
      $email = $data['email'];
      if ($data['thirdname'] == '' || $data['thirdname'] == null){
        $thirdname = "Отсутствует";
      }else{
        $thirdname = $data['thirdname'];
      }
      $password = "";
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
  <title>Sport - account</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <main>
    <form method="post">
      <input type="submit" value="Выйти из аккаунта" name="exit">
    </form>
  </main>
</body>
</html>