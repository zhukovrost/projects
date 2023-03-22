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
  "success_sub" => false,
  "incorrect_format" => false,
  "not_image" => false,
  "too_big_image" => false,
  'success_upload' => false,
  'error_upload' => false,
);

session_start();

$back_way = "index.php";
if (isset($_GET['back'])){
  $back_way = $_GET['back'];
}

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

# ---------------------- avatar --------------------


if (isset($_FILES['load_avatar'])){
  $load_avatar = $_FILES['load_avatar'];
  if (@getimagesize($load_avatar['tmp_name']) == true){
    $input = file_get_contents($load_avatar['tmp_name']);
    if (preg_match('/(<\?php\s)/', $input)){
      $error_array['not_image'] = true;
    }else{
      $input = str_replace(chr(0), '', $input);
    }
  }else{
    $error_array['not_image'] = true;
  }

  $size = $load_avatar['size'];

  if ($size > 2949120){
    $error_array['too_big_image'] = true;
  }


   if ($load_avatar['type'] != "image/jpg" && $load_avatar['type'] != "image/jpeg" && $load_avatar['type'] != "image/png"){
      $error_array['incorrect_format'] = true;
    }

   if (!$error_array['not_image'] && !$error_array['too_big_image'] && !$error_array['incorrect_format']){
     $image = addslashes(file_get_contents($load_avatar["tmp_name"]));
     $insert_image_sql = "INSERT INTO avatars (image) VALUES ('".$image."')";
     if ($conn->query($insert_image_sql)){
       $error_array['success_upload'] = true;
       $update_avatar_sql = "UPDATE users SET avatar_id='".$conn->insert_id."' WHERE login='".$login."'";
       $conn->query($update_avatar_sql);
     }else{
       $error_array['error_upload'] = true;
     }
   }else{
     $error_array['update_success'] = false;
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
    $avatar = base64_encode(get_avatar($conn, $data['avatar_id']));
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
  <a href="<?php echo $back_way; ?>">Назад</a>
</header>
<body class="exercises_body">
  <main class="profile_card">
      <form enctype="multipart/form-data" method="post">
      <!-- first string -->
      <div class="together" method="post">
        <img class="avatar" src="data:image/jpeg;base64, <?php echo $avatar; ?>" style="margin-right: 30px;">
        <label style="font-size: 50px; margin-top: auto; margin-bottom: auto;">Профиль спортсмена <span style="color: #FF0000"><?php echo $login; ?></span></label>
      </div>
        <div method="post" id="user" style="display: flex;">
          <?php
            if ($all_info){
              echo '
              <div class="personal_data">
                <h2>Даннные аккаунта спортсмена</h2>
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
                <label>Дата рождения: <span>'.date("d-m-Y" ,$date_of_birth).'</span></label>
                <br>
                <label>Электронная почта: <span>'.$email.'</span></label>
                <br>
                <label>Пароль: <span>'.$password.'</span></label>
                <br>
                <label class="load_avatar_label" for="load_avatar_btn">Загрузить новую аватарку<img src="img/icons/free-icon-download-arrow-62055.png" alt=""> </label>
                <input id="load_avatar_btn" type="file" name="load_avatar">
                <br>
              </div>
              <div class="physical_data">
                <h2>Физические данные спортсмена</h2>
                <div class="together">
                  <label style="margin-right: 10px;">Вес (кг): </label>
                  <input type="text" class="card_input" name="weight" value="'.$weight.'">
                </div>
                <div class="together">
                  <label style="margin-right: 10px;">Рост (см): </label>
                  <input type="text" class="card_input" name="height" value="'.$height.'">
                </div>
                <label>Пол: <span>'.$sex .'</span></label>
                <br>
                <a href="clear.php" class="exit_button">Выйти из аккаунта</a> <!-- logout png -->
              </div>
              ';
            }else{
              echo '
              <div class="personal_data">
                <h2>Даннные аккаунта спортсмена</h2>
                <div class="together">
                  <label style="margin-right: 10px;">Имя: <span>'.$name.'</span></label>
                </div>
                <div class="together">
                  <label style="margin-right: 10px;">Фамилия: <span>'.$surname.'</span></label>
                </div>
                <div class="together">
                  <label style="margin-right: 10px;">Отчество: <span>'.$thirdname.'</span></label>
                </div>
                <label>Дата рождения: <span>'.date("d-m-Y" ,$date_of_birth).'</span></label>
                <br>
              </div>
              <div class="physical_data">
                <h2>Физические данные спортсмена</h2>
                <div class="together">
                  <label style="margin-right: 10px;">Вес (кг): <span>'.$weight.'</span></label>
                </div>
                <div class="together">
                  <label style="margin-right: 10px;">Рост (см): <span>'.$height.'</span></label>
                </div>
                <label>Пол: <span>'.$sex .'</span></label>
                <br>
                ';
              if (check_if_sub($conn, $_COOKIE['login'], $login)){
                echo "<label' class='already_subscribed_btn'>Вы подписаны</label>";
              }else {
                echo '
                <input class="subscribe_btn" type="submit" value="Подписаться" name="sub">
                ';
              }
              echo '</div>';
            }
          ?>
        </div>
        <?php
        # --------------------- errors -------------------------

        if ($error_array["update_success"]){ echo "<p style='color: lime'>Данные пользователя успешно обновлены</p>"; }
        if ($error_array["update_error"]){ echo "<p style='color: #FF0000'>Ошибка обновления данных</p>"; }
        if ($error_array["empty_form"]){ echo "<p style='color: #FF0000'>Ошибка: заполните форму</p>"; }
        if ($error_array["select_error"]){ echo "<p style='color: #FF0000'>Ошибка: не удалось найти пользователя</p>"; }
        if ($error_array["too_long_string"]){ echo "<p style='color: #FF0000'>Ошибка: слишком длинное имя/фамилия/отчество</p>"; }
        if ($error_array["incorrect_format"]){ echo "<p style='color: #FF0000'>Ошибка: неправильное расширение изображения: попробуйте jpg/jpeg/png</p>"; }
        if ($error_array["not_image"]){ echo "<p style='color: #FF0000'>Ошибка: вы загружаете не изображение</p>"; }
        if ($error_array["error_upload"]){ echo "<p style='color: #FF0000'>Ошибка: не удалось загрузить изображение</p>"; }
        if ($error_array["too_big_image"]){ echo "<p style='color: #FF0000'>Ошибка: изображение слишком большое</p>"; }

        ?>

      <section class="user_program_block">
        <?php
        if ($program_id == 0){
          echo '<h2 class = "acc_title_prog">Програм пока нет</h2>';
          if ($all_info){
            echo '<a href="exercises/exercises.php" class="create_prog_button">Создать программу</a> <!-- сделай здесть эту кнопку -->';
          }
        }else{
          echo "<h2>Программа спортсмена:</h2>";
          $select_program_sql = "SELECT program FROM userprograms WHERE id=".$program_id;
          if ($select_program_result = $conn->query($select_program_sql)){
            foreach ($select_program_result as $data){
              $program = json_decode($data['program']);
            }
            $select_program_result->free();
            $exercises_array = json_decode(file_get_contents("./exercises/exercises.json"), true);
            echo "<table class='program_table'><tr>";
            foreach ($week as $weekday){
              echo "<th class='weekday_cell'>".$weekday."</th>"; # columns headers
            }
            echo "</tr>";

            for ($i = 0; $i < 7; $i++){
              $workout = $program[$i];
              if (get_week_day() == $i){
                echo "<td class='highlighted_cell'>";
              }else{
                echo "<td>";
              }
              if (empty($workout)){
                echo "<label>Выходной</label>"; # print Выходной if program is empty
              }else{
                foreach ($workout as $exercise_id){
                  $exercise_id_explode = explode("/", $exercise_id); # split id by '/'
                  $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id
                  echo "<li>";
                  echo $exercise[1]." - ".$exercise_id_explode[3]; # exercise name - repeats
                  if ($exercise[0]){
                    echo "с.";
                  }else{
                    echo "п.";
                  }
                  echo "</li>";
                }
              }
              echo "</td>";
            }
            echo "</tr></table>";
          }

          check_if_passed($conn, $login);

          echo "<h2>Календарь тренировок</h2>";

          echo "
          <table class='calendar'><tr><th class='weekday_cell'>Неделя</th>";
          foreach ($week as $weekday){
            echo "<th class='weekday_cell'>".$weekday."</th>";
          }
          echo "</tr>";

          $arr = get_program_day($conn, $login);
          $day_now = $arr['day_now'];
          $week_now = $arr['week_now'];

          for ($i = 0; $i < count($calendar); $i++){
            echo "<tr><th>".($i + 1)."</th>";
            for ($j = 0; $j < 7; $j++){
              if ($j == $day_now && $i == $week_now){
                echo "<td class='highlighted_cell'>";
              }else{
                echo "<td>";
              }
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

        if ($all_info) {
          if ($program_id != 0) {
            echo "<a class='finish_btn' href='exercises/end.php'>Досрочно завершить</a>";
          }
        }else{
          if ($program_id != 0){
            echo "<a class='same_programm_btn' href='exercises/exercises.php?back=../account.php?user=".$login."&page=my_program&id=" . $program_id . "'>Начать такую же программу</a>";
          }
        }
        ?>
      </section>
        </form>
  </main>

  <script src="main.js"></script>
  <?php
  if (isset($_GET['new_program_error'])){
    echo "
    <script>
      alert('У вас уже есть программа. Завершите текущую, чтобы начать новую')
    </script>
    ";
  }
  ?>
</body>
<?php $conn->close(); ?>
</html>
