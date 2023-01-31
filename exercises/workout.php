<?php
include "../templates/func.php";
include "../templates/settings.php";

check_the_login("../");
$login = $_COOKIE['login'];

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$data = file_get_contents("exercises.json");
$exercises_array = json_decode($data, true);

date_default_timezone_set('UTC+3');

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

$select_calendar_sql = "SELECT calendar, start_program FROM users WHERE login='".$login."'";
if ($select_result = $conn->query($select_calendar_sql)) {
  $now = time();
  foreach ($select_result as $item) {
    $calendar = json_decode($item['calendar']);
    $start_program = $item['start_program'];
  }

  for ($i = 0; $i < 7; $i++) {
    if ($calendar[0][$i] != 2) {
      $start_weekday_num = $i;
    }
  }

  $day_now = $start_weekday_num;
  for ($i = $now - $start_program; $i >= 0; $i = $i - 86400) {
    $day_now++;
  }
  $week_now = $day_now % 7;
  $day_now = (int)($day_now / 7);
}

$select_id_sql = "SELECT program FROM users WHERE login='".$login."'";
if ($select_id_result = $conn->query($select_id_sql)){
  foreach ($select_id_result as $user){
    $program_id = $user['program'];
  }
  if ($program_id != 0){
    $select_program_sql = "SELECT program FROM userprograms WHERE id='".$program_id."'";
    if ($select_program_result = $conn->query($select_program_sql)){
      foreach ($select_program_result as $item){
        $program = json_decode($item['program']);
      }
      $today_program = $program[get_week_day()];
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
<header class="default_header">
  <a href="../index.php">Назад</a>
</header>
<body>
    <nav>
      <a href="../index.php">Главная</a>
      <a href="workout.php">Мои тренировки</a>
      <a href="">Lorem</a>
      <a href="">Lorem</a>
      <a href="">Lorem</a>
    </nav>
  <main>
    <?php
    if ($error_array['no_program']){
      include "../templates/no_program.html";
    }else{
      if ($error_array['holiday']){
        include "../templates/holiday.html";
      }else{

        # ------------------------------------- CURRENT EXERCISE ----------------------------------

        if ((bool)$_SESSION['start']){
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

          echo "<form method='post'>";
          if ($_SESSION['current_exercise'] < $amount_of_exercises){
            $exercise_id_explode = explode("/", $today_program[$_SESSION['current_exercise']]); # split array by '/'
            $repeats = $exercise_id_explode[3];
            $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id

            echo "
            <div>
              <img src='".$exercise[3]."'>
              <h4>".$exercise[1]."</h4>";
            # repeats
            if ($exercise[0]){
              echo "<label>Длительность (в секундах): ".$repeats."</label>";
            }else{
              echo "<label>Повторения: ".$repeats."</label>";
            }

            echo "
          <button type='submit' name='pass'>Пропустить упражнение</button>
          <button type='submit' name='next'>Я выполнил упражнение</button>
          ";
          }else{
            echo "
            <h1>Поздравляем, вы завершили тренировку!</h1>
            <label>Выполнено упражнений ".($amount_of_exercises-$_SESSION['passed'])."/".$amount_of_exercises."</label>
            <button type='submit' name='finish'>Завершить сеанс</button>
            ";
          }

          echo "</form>";
        } else {

          # ------------------------ TODAY'S WORKOUT  --------------------------

          echo "<h1>А вот и сегодняшняя тренировка!</h1>";
          for ($i = 0; $i < count($today_program); $i++){
            $exercise_id_explode = explode("/", $today_program[$i]); # split array by '/'
            $repeats = $exercise_id_explode[3];
            $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id

            echo "
            <div>
              <img src='".$exercise[3]."'>
              <h4>".$exercise[1]."</h4>";
            # repeats
            if ($exercise[0]){
              echo "<label>Длительность (в секундах): ".$repeats."</label>";
            }else{
              echo "<label>Повторения: ".$repeats."</label>";
            }
            echo "</div>";
          }
          echo "
          <form method='post'>
            <input type='hidden' name='start' value='1'>
            <button type='submit'>Начать тренировку</button>
          </form>
          ";
        }
      }
    }
    ?>
  </main>
</body>
<?php include "../templates/footer.html"; ?>
</html>