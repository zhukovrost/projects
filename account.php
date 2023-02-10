<?php

include "templates/func.php";
include 'templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$error_array = array(
  "empty_form" => false,
  "select_error" => false,
  "update_error" => false,
  "update_success" => false,
  "too_long_string" => false,
  "success_sub" => false
);

session_start();

if (isset($_GET['user']) && $_GET['user'] != $_COOKIE['login']){
  $login = $_GET['user'];
  $all_info = false;
}else{
  check_the_login();
  $login = $_COOKIE['login'];
  $all_info = true;
}

if (isset($_POST['sub']) && !check_if_sub($conn, $_COOKIE['login'], $login)){
  check_the_login();

  $select_sub_sql = "SELECT subscriptions FROM users WHERE login='".$_COOKIE['login']."'";
  if ($select_sub_result = $conn->query($select_sub_sql)){
    foreach ($select_sub_result as $item){
      $subs = json_decode($item['subscriptions']);
    }

    array_push($subs, $login);
    $update_subs_sql = "UPDATE users SET subscriptions='".json_encode($subs)."' WHERE login='".$_COOKIE['login']."'";
    if ($conn->query($update_subs_sql)){
      $insert_news_sql = "INSERT INTO news (new_id, user, date, personal, additional_info) VALUES (0, '".$login."', '".time()."', 1, '".$_COOKIE['login']."')";
      if ($conn->query($insert_news_sql)){
        $error_array['success_sub'] = true;
      }
    }

  }
  $select_sub_result->free();

}

if  ($all_info && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['thirdname']) && isset($_POST['weight']) && isset($_POST['height'])){
  if ($_POST['name'] != "" && $_POST['surname'] != "" && $_POST['thirdname'] != "" && $_POST['weight'] != "" && $_POST['height'] != ""){
    if (strlen($_POST['name']) <= 32 && strlen($_POST['surname']) <= 32 && strlen($_POST['thirdname']) <= 32){
      $weight_to_push = 0;
      $height_to_push = 0;
      if (is_numeric($_POST['weight'])) {
        $weight_to_push = $_POST['weight'];
      }else if (is_numeric(str_replace(',', '.', $_POST['weight']))){
        $weight_to_push = str_replace(',', '.', $_POST['weight']);
      }
      if (is_numeric($_POST['height'])){
        $height_to_push = $_POST['height'];
      }else if (is_numeric(str_replace(',', '.', $_POST['weight']))){
        $height_to_push = str_replace(',', '.', $_POST['weight']);
      }
      $update_sql = "UPDATE users SET name='".$_POST['name']."', surname='".$_POST['surname']."', thirdname='".$_POST['thirdname']."', height='".$height_to_push."', weight='".$weight_to_push."' WHERE login='".$login."'";
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

$select_sql = "SELECT * FROM users WHERE login='".$login."'";
if ($data_array = $conn -> query($select_sql)){
  foreach ($data_array as $data) {
    $name = $data['name'];
    $surname = $data['surname'];
    $email = $data['email'];
    $date_of_birth = $data['date_of_birth'];
    $program_id = $data['program'];
    $program_duration = $data['program_duration'];
    $calendar = json_decode($data['calendar']);
    $start_program = $data['start_program'];
    if ($data['sex'] == "m"){
      $sex = "Мужской";
    }elseif ($data['sex'] == "f"){
      $sex = "Женский";
    }
    if ($data['weight'] == 0){
      $weight = "Не указано";
    }else{
      $weight = $data['weight'];
    }
    if ($data['height'] == 0){
      $height = "Не указано";
    }else{
      $height = $data['height'];
    }
    if ($data['thirdname'] == '' || $data['thirdname'] == null) {
      $thirdname = "Отсутствует";
    } else {
      $thirdname = $data['thirdname'];
    }
    $password = "";
    for ($i = 0; $i < strlen($data['password']); $i++) {
      $password = $password.'*';
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
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/format.css">
</head>
<header class="default_header">
  <a href="index.php">Назад</a>
</header>
<body style="height: 100vh">
  <main class="profile_card">
    <!-- first string -->
    <form class="together" method="post">
      <img class="avatar" src="img/icons/user.png" style="margin-right: 30px;">
      <label style="font-size: 50px; margin-top: auto; margin-bottom: auto;">Профиль спортсмена <span style="color: #FF0000"><?php echo $login; ?></span></label>
      <?php
      if (!$all_info){
        if (check_if_sub($conn, $_COOKIE['login'], $login)){
          echo "<label>Вы подписаны</label>";
        }else {
          echo '
        <input type="submit" value="Подписаться" name="sub">
        ';
        }
      }
      ?>
    </form>
    <div style="width: 100%; border: black dashed 1px">
      <form method="post" id="user">
        <label>Даннные аккаунта спортсмена</label>
        <?php
          if ($all_info){
            echo '
            <div class="together">
              <label style="margin-right: 10px;">Имя</label>
              <input class="card_input" name="name" value="'.$name.'">
            </div>
            <div class="together">
              <label style="margin-right: 10px;">Фамилия</label>
              <input class="card_input" name="surname" value="'.$surname.'">
            </div>
            <div class="together">
              <label style="margin-right: 10px;">Отчество</label>
              <input class="card_input" name="thirdname" value="'.$thirdname.'">
            </div>
            <label>Дата рождения: '.$date_of_birth.'</label>
            <br>
            <label>Электронная почта: '.$email.'</label>
            <br>
            <label>Пароль: '.$password.'</label>
            <br>
            <label>Физические данные спортсмена</label>
            <div class="together">
              <label style="margin-right: 10px;">Вес (кг): </label>
              <input type="text" class="card_input" name="weight" value="'.$weight.'">
            </div>
            <div class="together">
              <label style="margin-right: 10px;">Рост (см): </label>
              <input type="text" class="card_input" name="height" value="'.$height.'">
            </div>
            <label>Пол: '.$sex .'</label>
            <br>
            ';
          }else{
            echo '
            <div class="together">
              <label style="margin-right: 10px;">Имя: '.$name.'</label>
            </div>
            <div class="together">
              <label style="margin-right: 10px;">Фамилия: '.$surname.'</label>
            </div>
            <div class="together">
              <label style="margin-right: 10px;">Отчество: '.$thirdname.'</label>
            </div>
            <label>Дата рождения: '.$date_of_birth.'</label>
            <br>
            <label>Физические данные спортсмена</label>
            <div class="together">
              <label style="margin-right: 10px;">Вес (кг): '.$weight.'</label>
            </div>
            <div class="together">
              <label style="margin-right: 10px;">Рост (см): '.$height.'</label>
            </div>
            <label>Пол: '.$sex .'</label>
            <br>
            ';
          }
        ?>
      </form>
      <?php
      # --------------------- errors -------------------------

      if ($error_array["update_success"]){ echo "<p style='color: lime'>Данные пользователя успешно обновлены</p>"; }
      if ($error_array["update_error"]){ echo "<p style='color: #FF0000'>Ошибка обновления данных</p>"; }
      if ($error_array["empty_form"]){ echo "<p style='color: #FF0000'>Ошибка: заполните форму</p>"; }
      if ($error_array["select_error"]){ echo "<p style='color: #FF0000'>Ошибка: не удалось найти пользователя</p>"; }
      if ($error_array["too_long_string"]){ echo "<p style='color: #FF0000'>Ошибка: слишком длинное имя/фамилия/отчество</p>"; }

      ?>
    </div>

    <div style="width: 100%; border: black dashed 1px">
      <label>Программa спортсмена:</label>
      <?php
      if ($program_id == 0){
        echo "<label>Програм пока нет</label><a href='exercises/exercises.php'>Создать программу</a>";

      }else{
        $select_program_sql = "SELECT program FROM userprograms WHERE id=".$program_id;
        if ($select_program_result = $conn->query($select_program_sql)){
          foreach ($select_program_result as $data){
            $program = json_decode($data['program']);
          }
          $select_program_result->free();
          $exercises_array = json_decode(file_get_contents("./exercises/exercises.json"), true);
          echo "<table><th></th>";
          foreach ($week as $weekday){
            echo "<th>".$weekday."</th>"; # columns headers
          }
          echo "</tr><tr><th>Тренировки</th>";

          for ($i = 0; $i < 7; $i++){
            $workout = $program[$i];
            echo "<td>";
            if (empty($workout)){
              echo "<label>Выходной</label>"; # print Выходной if program is empty
            }else{
              echo "<ul>";
              foreach ($workout as $exercise_id){
                $exercise_id_explode = explode("/", $exercise_id); # split id by '/'
                $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id
                echo "<li>";
                echo $exercise[1]." - ".$exercise_id_explode[3]." "; # exercise name - repeats
                if ($exercise[0]){
                  echo "секунд(а)";
                }else{
                  echo "повторений(ие)";
                }
                echo "</li>";
              }
              echo "</ul>";
            }
            echo "</td>";
          }
          echo "</tr></table>";
        }

        check_if_passed($conn, $login);

        if ($_SESSION['refresh'] == null){
          $_SESSION['refresh'] = " ";
          header("Refresh: 0");
        }else{
          $_SESSION['refresh'] = null;
        }
        
        echo "
        <table><tr><th>Неделя</th>";
        foreach ($week as $weekday){
          echo "<th>".$weekday."</th>";
        }
        echo "</tr>";

        for ($i = 0; $i < count($calendar); $i++){
          echo "<tr><th>".($i + 1)."</th>";
          for ($j = 0; $j < 7; $j++){
            echo "<td>";
            switch ($calendar[$i][$j]){
              case 0:
                echo "<img class='calendar_image' src='img/icons/holiday.png'>";
                break;
              case 1:
                echo "<img class='calendar_image' src='img/icons/workout.png'>";
                break;
              case 2:
                echo "<img class='calendar_image' src='img/icons/out_of_program.png'>";
                break;
              case 3:
                echo "<img class='calendar_image' src='img/icons/complete.png'>";
                break;
              case 4:
                echo "<img class='calendar_image' src='img/icons/passed.png'>";
                break;
            }
            echo "</td>";
          }
          echo "</tr>";
        }
        echo "</table>";
      }
      ?>
    </div>
    <?php
    if ($all_info){
      echo '
      <a href="clear.php" class="exit_button"><img style="height: 30px; margin-right: 5px;" src="img/icons/logout.png"> <label>Выйти из аккаунта</label></a>
      ';
    }
    ?>
  </main>

  <script src="main.js"></script>
</body>
<?php
include "templates/footer.html";
$conn->close();
?>
</html>
