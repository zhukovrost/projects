<?php
include "../templates/settings.php";
include "../templates/func.php";

# getting array of all exercises ($exercises_array)

$data = file_get_contents("exercises.json");
$exercises_array = json_decode($data, true);

$error_array = array(
  "add_to_construct_success" => false,
  "add_to_program_success" => false,
  "set_date_error" => true
);

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);
check_the_login("../");
$login = $_COOKIE['login'];
check_the_program($conn, $login);

session_start();

$back_way = "../account.php";
if (isset($_GET['back'])){
  $back_way = $_GET['back'];
}

if (isset($_POST['edit_construct_array'])){

  # select exercises on construct page if we edit program

  $_SESSION['edit_day'] = json_decode($_POST['edit_day'])[0];
  $_SESSION['construct_array'] = json_decode($_POST['edit_construct_array']);
}else{

# pushing the array with all selected exercises, if it does not exist - creating it

  if (isset($_SESSION['construct_array'])){
    if (isset($_POST['exercise'])){
      $push_exercise = $_POST['exercise'].$_POST['repeats'];
      array_push($_SESSION['construct_array'], $push_exercise);
      $error_array['add_to_construct_success'] = true;
    }
  }else{
    $_SESSION['construct_array'] = array();
  }
}

# pushing selected exercises($_SESSION['construct_array']) to program($_SESSION['program_array']), creating $_SESSION['program_array'] if it does not exist


if (isset($_GET['id'])){
  $select_sql = "SELECT program from userprograms WHERE id='".$_GET['id']."'";
  if ($select_result = $conn->query($select_sql)){
    foreach($select_result as $item){
      $_SESSION['program_array'] = json_decode($item['program']);
    }
  }
  $select_result->free();
}else if (isset($_SESSION['program_array'])){
  if (isset($_POST['add_to_program_array'])){
    if (isset($_POST['day'])){
      if ($_POST['day'] != ''){
        foreach ($_POST['day'] as $item){
          foreach ($_SESSION['construct_array'] as $exercise_id) {
            array_push($_SESSION['program_array'][$item], $exercise_id);
            $_SESSION['edit_day'] = '';
          }
        }
      }
    }else{
      $error_array['set_date_error'] = true;
    }
  }
}else{
  $_SESSION['program_array'] = [[], [], [], [], [], [], []];
}

# delete and change exercises

if ($_GET['page'] == "constructor"){

  if (isset($_POST['clear'])){
    # clear selected exercises ($_SESSION['construct_array'])
    $_SESSION['construct_array'] = array();
  }
  elseif (isset($_POST['delete_exercise_id'])){
    # deleting an exercise from $_SESSION['construct_array']
    $new_construct_array = array();
    for ($i = 0; $i < $_POST['delete_exercise_id']; $i++){ array_push($new_construct_array, $_SESSION['construct_array'][$i]); }
    for ($i = $_POST['delete_exercise_id'] + 1; $i < count($_SESSION['construct_array']); $i++){ array_push($new_construct_array, $_SESSION['construct_array'][$i]); }
    $_SESSION['construct_array'] = $new_construct_array;
  }
  elseif (isset($_POST['change_repeats'])){
    # changing exercise from $_SESSION['construct_array']
    $change = explode("/", $_SESSION['construct_array'][$_POST['change_repeats_id']]);
    $change[3] = $_POST['change_repeats'];
    $_SESSION['construct_array'][$_POST['change_repeats_id']] = $change[0]."/".$change[1]."/".$change[2]."/".$change[3];
  }

}

# delete workouts

if (isset($_POST['delete_workout'])){
  $_SESSION['program_array'][$_POST['delete_workout']] = array();
}

# adding program to bd and to account

if (isset($_POST['weeks'])){
  if ($_POST['weeks'] > 0){
    $new_program = json_encode($_SESSION['program_array']);
    $check_existing_program_sql = "SELECT id FROM userprograms WHERE program='" . $new_program . "'";
    if ($check_existing_program_result = $conn->query($check_existing_program_sql)) {
      $rowsCount = $check_existing_program_result->num_rows;
      if ($rowsCount == 0) {
        $insert_sql = "INSERT INTO userprograms(program) VALUES('".$new_program."')";
        if ($conn->query($insert_sql)) {
          if ($check_existing_program_result2 = $conn->query($check_existing_program_sql)) {
            foreach ($check_existing_program_result2 as $select_id){
              $id = $select_id['id'];
            }
          }
          $insert_sql2 = "INSERT INTO news(new_id, user, date) VALUES(2, '".$login."', '".time()."')";
          $conn->query($insert_sql2);
          $check_existing_program_result2->free();
        }
      }else {
        foreach ($check_existing_program_result as $select_id) {
          $id = $select_id['id'];
        }
      }

      # calendar
      for ($j = 0; $j < get_week_day(); $j++){
        $calendar[0][$j] = 2;
      }
      for ($j = get_week_day(); $j < 7; $j++){
        if ((int)count($_SESSION['program_array'][$j]) == 0){
          $calendar[0][$j] = 0;
        }else{
          $calendar[0][$j] = 1;
        }
      }

      for ($i = 1; $i < $_POST['weeks']; $i++){
        for ($j = 0; $j < 7; $j++){
          if ((int)count($_SESSION['program_array'][$j]) == 0){
            $calendar[$i][$j] = 0;
          }else{
            $calendar[$i][$j] = 1;
          }
        }
      }

      for ($j = 0; $j < get_week_day(); $j++) {
        if ((int)count($_SESSION['program_array'][$j]) == 0) {
          $calendar[$_POST['weeks']][$j] = 0;
        } else {
          $calendar[$_POST['weeks']][$j] = 1;
        }
      }
      if (get_week_day() != 0){
        for ($j = get_week_day(); $j < 7; $j++){
          $calendar[$_POST['weeks']][$j] = 2;
        }
      }

      date_default_timezone_set('Europe/Moscow');
      $date = time();

      $update_account_sql = "UPDATE users SET program='".$id."', program_duration='".$_POST['weeks']."', calendar='".json_encode($calendar)."', start_program='".$date."', completed_program=false WHERE login='".$login."'";
      if ($conn->query($update_account_sql)){
        $_SESSION['program_array'] = [[], [], [], [], [], [], []];
        $_SESSION['construct_array'] = array();
        header("Location: ../account.php");
      }
    }
    $check_existing_program_result->free();
  }
  $conn->close();
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../css/style.css">
  <title>Document</title>
</head>



<body class="exercises_body">
<header class="default_header">
  <a href="<?php echo $back_way; ?>">Назад</a>
</header>
<nav>
  <a href="exercises.php?page=my_program">Моя программа</a>
  <a href="exercises.php?page=constructor">Конструктор(<?php if (isset($_SESSION['construct_array'])) { echo count($_SESSION['construct_array']); } else { echo "0"; } # amount of selected exercises ?>)</a>
  <a href="exercises.php?page=press">Пресс</a>
  <a href="exercises.php?page=legs">Ноги</a>
  <a href="exercises.php?page=arms">Руки</a>
  <a href="exercises.php?page=back">Спина</a>
  <a href="exercises.php?page=chest_and_shoulders">Грудь и плечи</a>
</nav>
<main class="container">
  <?php
  if (empty($_GET['page'])){

    # ---------------------------- WELCOME PAGE ---------------------------------

    include "../templates/welcome_to_constructor.html";

  }elseif ($_GET['page'] == "my_program") {

    # ----------------------------- MY PROGRAM PAGE -----------------------------------

    echo "
    <table class='program_table'>
      <tr class='program_table'>";
    foreach ($week as $weekday){
      echo "<th class='weekday_cell'>".$weekday."</th>"; # columns headers
    }
    echo "
      </tr>
      <tr class='program_table'>"; # row header

    for ($i = 0; $i < 7; $i++){
      $program = $_SESSION['program_array'][$i]; # selecting workout from $_SESSION['program_array'] of the $i's day
      echo "<td style='background-color: #F2F2F2;'>";
      if (empty($program)){
        echo "<h1 style='font-size: 20px; color: red;'>Выходной</h1>"; # print Выходной if program is empty
      }else{

        # printing workout of the day

        echo "<ul>";
        foreach ($program as $exercise_id){

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

      # edit and delete workout buttons

      echo "
      <form action='exercises.php?page=constructor' method='post'>
        <input type='hidden' name='edit_construct_array' value='".json_encode($program)."'>
        <input type='hidden' name='edit_day' value='".json_encode(array($i))."'>
        <button class='change_btn' type='submit'>Изменить</button>
      </form>
      <form method='post'>
        <input type='hidden' value='".$i."' name='delete_workout'>
        <input class='remove_btn' type='submit' value='Удалить тренировку'>
      </form>";
      echo "</td>";
    }

    echo "</tr></table>";

    echo "
    <form class='start_program_form' method='post'>
      <h1>Укажите продолжительность выполнения данной программы в неделях</h1>
      <input class='start_program_form_input' name='weeks' type='number' value='3'>
      <br>
      <button class='start_btn' type='submit'>Начать данную программу</button>
    </form>";

  } elseif ($_GET['page'] == "constructor") {

    # ------------------ CONSTRUCTOR PAGE -------------------------

    if (!isset($_SESSION['construct_array']) || count($_SESSION['construct_array']) == 0){

      # printing some text if there are no selected exercises
      echo "<div style='width: 100%; text-align: center'>";
      echo "<h1 style='margin: 30px auto; font-size: 40px;'>Вы не выбрали упражнения</h1>";
      echo "<h2 style='margin: auto; font-size: 32px;'><span style='color: red'>Помните:</span> выходные для слабаков!</h2>";
      echo "</div>";
    }else{

      echo "
      <form class='construct_form' method='post'>
        <table>
        <tr>
      ";
      foreach ($week as $weekday){
        echo "<th class='weekday_cell'>".$weekday."</th>";
      }
      echo "</tr><tr>";
        # printing days menu
      for ($i = 0; $i < 7; $i++){
        if (isset($_SESSION['edit_day'])){
          if ($_SESSION['edit_day'] == $i){
            echo "<td><input id='".$i."' type='checkbox' name='day[]' value='".$i."' checked></td>";
          }else {
            echo "<td><input id='".$i."' type='checkbox' name='day[]' value='".$i."'></td>";
          }
        }else{
          echo "<td><input id='".$i."' type='checkbox' name='day[]' value='".$i."'></td>";
        }
      }

      echo "
        </tr></table>
      <input type='submit' name='add_to_program_array' value='Добавить в программу'>
      <input type='submit' name='clear' value='Очистить конструктор полностью'>
    </form>";

      echo "<section class='exercise_list_block'>";
      for ($i = 0; $i < count($_SESSION['construct_array']); $i++){

        # printing selected exercises ($_SESSION['construct_array'])

        $exercise_id_explode = explode("/", $_SESSION['construct_array'][$i]); # split array by '/'
        $repeats = $exercise_id_explode[3];
        $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id

        echo "
    <form class='exercise' method='post' class='together'>
      <img style='width: 15%;' src='".$exercise[3]."'>
      <section>
        <h4>".$exercise[1]."</h4> <!-- exercise name -->";
        # repeats input
        if ($exercise[0]){
          echo "<p>Длительность (в секундах)</p>";
        }else{
          echo "<p>Повторения</p>";
        }
        # posting an id of the exercise
        echo "
         <input type='number' name='change_repeats' value='".$repeats."'>
      </section>
      <section style='justify-content: right; width: 35%'>
        <input type='hidden' name='change_repeats_id' value='".$i."'>
        <button type='submit'><img style='width: 40%' src='../img/icons/edit.png'></button>
        <input type='hidden' name='delete_exercise_id' value='".$i."'>
        <button type='submit'><img style='width: 40%' src='../img/icons/bin.png'></button>
      </section>
    </form>";
      }
      echo '</seciton>';
    }

  }else{

    # ---------------------------- EXERCISES PAGES -------------------------------------

    $name = $_GET['page'];
    for ($i = 1; $i < count($exercises_array[$name]); $i++){
      echo "<div class='exercise_list_block'>";

      echo "
      <article>
        <img src='".$exercises_array[$name][$i][1]."'> <!-- group of muscles image -->
        <h3>".$exercises_array[$name][$i][0]."</h3> <!-- group of muscles -->
      </article>";

      for ($j = 2; $j < count($exercises_array[$name][$i]); $j++){
        $exercise = $exercises_array[$name][$i][$j];
        $exercise_id = $name."/".$i."/".$j."/";
        echo "
        <form method='post' class='exercise'>
          <img src='".$exercise[3]."'>  <!-- exercise image + submit button -->
          <section>
          <h4>".$exercise[1]."</h4> <!-- exercise name -->";
        # repeats input
        if ($exercise[0]){
          echo "<p>Длительность (в секундах)</p>";
        }else{
          echo "<p>Повторения</p>";
        }
        # posting an id of the exercise
        echo "
           <input type='number' name='repeats' value='".$exercise[2]."'>
          </section>
          <input type='hidden' name='exercise' value='".$exercise_id."'>
          <button style='border: none' type='submit'>
            <img src='../img/icons/add_to_the_list.png'>
          </button>
        </form>";
      }
      echo "</div>";
    }


  }
  /*
  $exercises_array[group_of_muscles][muscle][exercise]
  $exercises_array[$name][0] = group of muscles name
  $exercises_array[$name][$i][0] = muscle
  $exercises_array[$name][$i][1] = muscle image
  $exercises_array[$name][$i][$j][0] = if static - true, if not - false
  $exercises_array[$name][$i][$j][1] = exercise name
  $exercises_array[$name][$i][$j][2] = if static - seconds, if not - repeats
  $exercises_array[$name][$i][$j][3] = exercise image
  */
  ?>
</main>
</body>
</html>