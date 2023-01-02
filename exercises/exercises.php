<?php
include "../templates/settings.php";
include "../templates/func.php";
$data = file_get_contents("exercises.json");
$exercises_array = json_decode($data, true);
$error_array = array(
  "add_success" => false
);
session_start();
# pushing the array with all selected exercises, if it does not exist - creating it
if (isset($_SESSION['construct_array'])){
  if (isset($_POST['add'])){
    $push_exercise = $_POST['exercise'].$_POST['repeats'];
    array_push($_SESSION['construct_array'], $push_exercise);
    $error_array['add_success'] = true;
  }
}else{
  $_SESSION['construct_array'] = array();
}

# delete and change exercises

if ($_GET['page'] == "my_program"){
  if (isset($_POST['clear'])){
    clear_all();
  } elseif (isset($_POST['delete_exercise'])){
    $new_construct_array = array();
    for ($i = 0; $i < $_POST['delete_exercise_id']; $i++){ array_push($new_construct_array, $_SESSION['construct_array'][$i]); }
    for ($i = $_POST['delete_exercise_id'] + 1; $i < count($_SESSION['construct_array']); $i++){ array_push($new_construct_array, $_SESSION['construct_array'][$i]); }
    $_SESSION['construct_array'] = $new_construct_array;
  } elseif (not_empty($_POST['change_repeats'])){
    $change = explode("/", $_SESSION['construct_array'][$_POST['change_repeats_id']]);
    $change[3] = $_POST['change_repeats'];
    $_SESSION['construct_array'][$_POST['change_repeats_id']] = $change[0]."/".$change[1]."/".$change[2]."/".$change[3];
  }
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
  <a href="exercises.php?page=my_program">Моя программа(<?php if (isset($_SESSION['construct_array'])) { echo count($_SESSION['construct_array']); } else { echo "0"; } # amount of selected exercises ?>)</a>
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

  if (!isset($_SESSION['construct_array']) || count($_SESSION['construct_array']) == 0){
    echo "<label>Вы не выбрали упражнений</label>";
    echo "<label>Помните: выходные для слабаков!</label>";
  }else{
    for ($i = 0; $i < count($_SESSION['construct_array']); $i++){
      $exercise_id_explode = explode("/", $_SESSION['construct_array'][$i]); # making an id as array by splitting a string
      $repeats = $exercise_id_explode[3];
      $exercise = $exercises_array[$exercise_id_explode[0]][$exercise_id_explode[1]][$exercise_id_explode[2]];
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
      # posting an id of the exercise
      echo "
      <input type='hidden' name='change_repeats_id' value='".$i."'>
      <button type='submit'><img style='width: 100px; height: 100px' src='../img/images/edit.png'></button>
      <input type='hidden' name='delete_exercise_id' value='".$i."'>
      <button type='submit' name='delete_exercise'><img style='width: 100px; height: 100px' src='../img/images/bin.png'></button>
    </form>";
    }
    echo "
    <form method='post'>
      <input type='submit' name='clear' value='Очистить конструктор полностью'>
      <div>
        <label><input type='checkbox' name='day' value='0'>Понедельник</label>
        <label><input type='checkbox' name='day' value='1'>Фторник</label>
        <label><input type='checkbox' name='day' value='2'>Среда</label>
        <label><input type='checkbox' name='day' value='3'>Четверг</label>
        <label><input type='checkbox' name='day' value='4'>Пятница</label>
        <label><input type='checkbox' name='day' value='5'>Суббота</label>
        <label><input type='checkbox' name='day' value='6'>Воскресениье</label>
      </div>
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
          <button type='submit' name='add'>
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
