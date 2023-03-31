<?php
include "../templates/func.php";
include "../templates/settings.php";

check_the_login("../");
$login = $_COOKIE['login'];

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$data = file_get_contents("exercises.json");
$exercises_array = json_decode($data, true);

date_default_timezone_set('Europe/Moscow');

$error_array = array(
  "holiday" => false,
  "no_program" => false
);

session_start();

if (isset($_POST['start'])){
  $_SESSION['start'] = $_POST['start'];
}else{
  if (empty($_SESSION['start'])){
    $_SESSION['start'] = 0;
  }
}

$arr = get_program_day($conn, $login);

$day_now = $arr['day_now'];
$week_now = $arr['week_now'];
$completed = false;

$select_id_sql = "SELECT program, calendar FROM users WHERE login='".$login."'";
if ($select_id_result = $conn->query($select_id_sql)){
  foreach ($select_id_result as $user){
    $program_id = $user['program'];
    $calendar = json_decode($user['calendar']);
  }
  if ($program_id != 0){
    $arr = get_program_day($conn, $login);
    $select_program_sql = "SELECT program FROM userprograms WHERE id='".$program_id."'";
    if ($select_program_result = $conn->query($select_program_sql)){
      foreach ($select_program_result as $item){
        $program = json_decode($item['program']);
      };
      $today_program = $program[$day_now];
      $amount_of_exercises = count($today_program);
      if (count($today_program) == 0){
        $error_array['holiday'] = true;
      }
    }
    $select_program_result->free();
  }else{
    $error_array['no_program'] = true;
  }
}
$select_id_result->free();

if (isset($_POST['finish'])){
  $_SESSION['current_exercise'] = 0;
  $_SESSION['start'] = 0;
  $_SESSION['passed'] = 0;

  $calendar[$week_now][$day_now] = 3;

  $update_sql = "UPDATE users SET calendar='" . json_encode($calendar) . "' WHERE login='" . $login . "'";
  if ($conn->query($update_sql)){
    header("Location: ../account.php");
  }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/format.css">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,700&display=swap" rel="stylesheet">

</head>
<header>
  <div class="header_info">
    <h1 class="company_name">sport_is_life &#174;</h1>
    <div class="title">
      <h1>
        Спорт
      </h1>
      <h2>
        это жизнь
      </h2>
    </div>
    <div class="reglog_buttons">
      <?php
      if (check_the_login("../", false)){
        echo '<a href="../account.php">'.$_COOKIE["login"].'</a>';
      }else{
        echo '
        <a href="../log.php">Вход</a>
        <a href="../reg.php">Регистрация</a>
        ';
      }
      ?>
    </div>
  </div>
  <nav>
    <a href="../index.php">Главная</a>
    <a href="../exercises/workout.php">Мои тренировки</a>
    <a href="../users/search.php">Пользователи</a>
    <a href="../news/news.php">Новости</a>
  </nav>
</header>
<body class="exercises_body">
  <main class="container_exercises">
    <div class="container">
        <?php
        if ($error_array['no_program']){
          include "../templates/no_program.html";
        }else{
          if ($error_array['holiday']){
            include "../templates/holiday.html";
          }else{

            # ------------------------------------- CURRENT EXERCISE ----------------------------------

            if ((bool)$_SESSION['start']){
              if ($calendar[$week_now][$day_now] == 3){
                header('Location: workout.php');
              }
              if (isset($_SESSION['current_exercise'])){
                if (isset($_POST['next'])){
                  $_SESSION['current_exercise']++;
                }
                if (isset($_POST['pass'])){
                  $_SESSION['current_exercise']++;
                  $_SESSION['passed']++;
                }
              }else{
                $_SESSION['current_exercise'] = 0;
                $_SESSION['passed'] = 0;
              }

              echo "<form method='post' class='current_exercise_form'>";
              if ($_SESSION['current_exercise'] < $amount_of_exercises){
                $exercise_id_explode = explode("/", $today_program[$_SESSION['current_exercise']]); # split array by '/'
                $repeats = $exercise_id_explode[3];
                $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id

                echo "
                <div class='current_exercise'><div class='together'>
                <img src='".$exercise[3]."'>
                <section>
                  <h4>".$exercise[1]."</h4>";
                # repeats
                if ($exercise[0]){
                  echo "<label>Длительность (в секундах): ".$repeats."</label>";
                }else{
                  echo "<label>Повторения: ".$repeats."</label>";
                }

                echo "
                </div>
                </section>
                <section>
                  <button class='skip_exercise_btn' type='submit' name='pass'>Пропустить упражнение</button>
                  <button class='done_exercise_btn' type='submit' name='next'>Я выполнил упражнение</button>
                </section>
                </div>
            ";
              }else{
                echo "
              <h1>Поздравляем, вы завершили тренировку!</h1>
              <label>Выполнено упражнений ".($amount_of_exercises-$_SESSION['passed'])."/".$amount_of_exercises."</label>
              <button class='finish_workout_btn' type='submit' name='finish'>Завершить сеанс</button>
              ";
              }

              echo "</form>";
            } else {

              # ------------------------ TODAY'S WORKOUT  --------------------------
              echo "<section class='today_workout'>";
              echo "<h1>А вот и сегодняшняя тренировка!</h1>";
              for ($i = 0; $i < count($today_program); $i++){
                $exercise_id_explode = explode("/", $today_program[$i]); # split array by '/'
                $repeats = $exercise_id_explode[3];
                $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id

                echo "
              <div class='today_workout_exercise'>
                <img src='".$exercise[3]."' style='margin-right: 5px'>
                <div class='together'>
                  <p>".$exercise[1]."</p>";
                # repeats
                if ($exercise[0]){
                  echo "<p>Длительность (в секундах): ".$repeats."</p>";
                }else{
                  echo "<p>Повторения: ".$repeats."</p>";
                }
                echo "</div></div>";
              }
              if ($calendar[$week_now][$day_now] != 3){
                echo "
                <form method='post'>
                  <input type='hidden' name='start' value='1'>
                  <button class='start_workout_btn' type='submit'>Начать тренировку</button>
                </form>
                ";
              }else{
                echo "<p class='already_workout_text'>Вы уже потренировались. Возвращайтесь завтра.</p>";
              }

              echo "</section>";
            }
          }
        }
        ?>
    </div>
  </main>
</body>

</html>