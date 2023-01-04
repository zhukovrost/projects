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

session_start();

if (isset($_POST['edit_construct_array'])){

  # select exercises on construct page if we edit program

  $_SESSION['edit_day'] = json_decode($_POST['edit_day'])[0];
  $_SESSION['construct_array'] = json_decode($_POST['edit_construct_array']);
}else{

# pushing the array with all selected exercises, if it does not exist - creating it

  if (isset($_SESSION['construct_array'])){
    if (isset($_POST['add_to_construct_array'])){
      $push_exercise = $_POST['exercise'].$_POST['repeats'];
      array_push($_SESSION['construct_array'], $push_exercise);
      $error_array['add_to_construct_success'] = true;
    }
  }else{
    $_SESSION['construct_array'] = array();
  }
}

# pushing selected exercises($_SESSION['construct_array']) to program($_SESSION['program_array']), creating $_SESSION['program_array'] if it does not exist

if (isset($_SESSION['program_array'])){
  if (isset($_POST['add_to_program_array'])){
    if (isset($_POST['day'])){
      foreach ($_POST['day'] as $item){
        foreach ($_SESSION['construct_array'] as $exercise_id) {
          array_push($_SESSION['program_array'][$item], $exercise_id);
          $_SESSION['edit_day'] = '';
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
  elseif (isset($_POST['delete_exercise'])){
    # deleting an exercise from $_SESSION['construct_array']
    $new_construct_array = array();
    for ($i = 0; $i < $_POST['delete_exercise_id']; $i++){ array_push($new_construct_array, $_SESSION['construct_array'][$i]); }
    for ($i = $_POST['delete_exercise_id'] + 1; $i < count($_SESSION['construct_array']); $i++){ array_push($new_construct_array, $_SESSION['construct_array'][$i]); }
    $_SESSION['construct_array'] = $new_construct_array;
  }
  elseif (not_empty($_POST['change_repeats'])){
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

<?php include "../templates/header.html"; ?>

<body>
<nav>
  <a href="exercises.php?page=my_program">Моя программа</a>
  <a href="exercises.php?page=constructor">Конструктор(<?php if (isset($_SESSION['construct_array'])) { echo count($_SESSION['construct_array']); } else { echo "0"; } # amount of selected exercises ?>)</a>
  <a href="exercises.php?page=press">Пресс</a>
  <a href="exercises.php?page=legs">Ноги</a>
  <a href="exercises.php?page=arms">Руки</a>
  <a href="exercises.php?page=back">Спина</a>
  <a href="exercises.php?page=chest_and_shoulders">Грудь и плечи</a>
</nav>
<?php
if (empty($_GET['page'])){

  # welcome to constructor page

  include "../templates/welcome_to_constructor.html";

}elseif ($_GET['page'] == "my_program") {

  # my program page

  echo "
    <table>
      <tr class='program_table'>
        <td></td>";
  foreach ($week as $weekday){
    echo "<th>".$weekday."</th>"; # columns headers
  }
  echo "
      </tr>
      <tr class='program_table'>
        <th>Тренировка</th>"; # row header

  for ($i = 0; $i < 7; $i++){
    $program = $_SESSION['program_array'][$i]; # selecting workout from $_SESSION['program_array'] of the $i's day
    echo "<td>";
    if (empty($program)){
      echo "<label>Выходной</label>"; # print Выходной if program is empty
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
        <button type='submit'>Изменить</button>
      </form>
      <form method='post'>
        <input type='hidden' value='".$i."' name='delete_workout'>
        <input type='submit' value='Удалить тренировку'>
      </form>";
    echo "</td>";
  }

  echo "</tr></table>";

} elseif ($_GET['page'] == "constructor") {

  # constructor page

  if (!isset($_SESSION['construct_array']) || count($_SESSION['construct_array']) == 0){

    # printing some text if there are no selected exercises

    echo "<label>Вы не выбрали упражнения</label><br>";
    echo "<label>Помните: выходные для слабаков!</label>";

  }else{
    for ($i = 0; $i < count($_SESSION['construct_array']); $i++){

      # printing selected exercises ($_SESSION['construct_array'])

      $exercise_id_explode = explode("/", $_SESSION['construct_array'][$i]); # split array by '/'
      $repeats = $exercise_id_explode[3];
      $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]]; # select the exercise from $exercises_array by id

      echo "
    <form method='post' class='together'>
      <img src='".$exercise[3]."'>
      <h4>".$exercise[1]."</h4>";

      # repeats input

      if ($exercise[0]){
        echo "<label>Длительность (в секундах)<input name='change_repeats' type='number' value='".$repeats."'></label>";
      }else{
        echo "<label>Повторения<input type='number' name='change_repeats' value='".$repeats."'></label>";
      }

      # edit and delete exercise buttons

      echo "
      <input type='hidden' name='change_repeats_id' value='".$i."'>
      <button type='submit'><img style='width: 100px; height: 100px' src='../img/icons/edit.png'></button>
      <input type='hidden' name='delete_exercise_id' value='".$i."'>
      <button type='submit' name='delete_exercise'><img style='width: 100px; height: 100px' src='../img/icons/bin.png'></button>
    </form>";
    }

    # add to program menu

    echo "
    <form method='post'>
      <div>";
    # printing days menu
    for ($i = 0; $i < 7; $i++){
      if (isset($_SESSION['edit_day'])){
        if ($_SESSION['edit_day'] == $i){
          echo "<label><input id='".$i."' type='checkbox' name='day[]' value='".$i."' checked>".$week[$i]."</label>";
        }else {
          echo "<label><input id='".$i."' type='checkbox' name='day[]' value='".$i."'>".$week[$i]."</label>";
        }
      }else{
        echo "<label><input id='".$i."' type='checkbox' name='day[]' value='".$i."'>".$week[$i]."</label>";
      }
    }

    echo "
         </div>
      <input type='submit' name='add_to_program_array' value='Добавить в программу'>
      <input type='submit' name='clear' value='Очистить конструктор полностью'>
    </form>";
  }

}else{

  # exercises pages

  $name = $_GET['page'];
  echo "<h1>".$exercises_array[$name][0]."</h1>"; # header
  for ($i = 1; $i < count($exercises_array[$name]); $i++){
    echo "<div style='border: black 1px dashed'>";
    echo
    "<div class='together'>
      <img src='".$exercises_array[$name][$i][1]."'> <!-- group of muscules image -->
      <h3>".$exercises_array[$name][$i][0]."</h3> <!-- group of muscules -->
    </div>";
    for ($j = 2; $j < count($exercises_array[$name][$i]); $j++){
      $exercise = $exercises_array[$name][$i][$j];
      $exercise_id = $name."/".$i."/".$j."/";
      echo
        "<form method='post' class='together'>
          <button type='submit' name='add_to_construct_array'>
            <img src='".$exercise[3]."'>  <!-- exercise image + submit button -->
          </button>
          <h4>".$exercise[1]."</h4> <!-- exercise name -->";
      # repeats input
      if ($exercise[0]){
        echo "<label>Длительность (в секундах)<input name='repeats' type='number' value='".$exercise[2]."'></label>";
      }else{
        echo "<label>Повторения<input type='number' name='repeats' value='".$exercise[2]."'></label>";
      }
      # posting an id of the exercise
      echo "<input type='hidden' name='exercise' value='".$exercise_id."'></form>";
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
</body>
<?php include "../templates/footer.html"; ?>
</html>
