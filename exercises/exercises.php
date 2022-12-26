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
  <a href="exercises.php?page=my_program">Моя программа(<?php echo count($_SESSION['construct_array']); # amount of selected exercises ?>)</a>
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
  print_r($_SESSION['construct_array']);
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
      echo
        "<form method='post' class='together'>
          <button type='submit' name='add'>
            <img src='".$exercises_array[$name][$i][$j][3]."'>  <!-- exercise image + submit button -->
          </button>
          <h4>".$exercises_array[$name][$i][$j][1]."</h4> <!-- exercise -->";
      # repeats input
      if ($exercises_array[$name][$i][$j][0]){
        $exercise_id = $name."/".$i."/".$j."/s/";
        echo "<label>Длительность (в секундах)<input name='repeats' type='number' value='".$exercises_array[$name][$i][$j][2]."'></label>";
      }else{
        $exercise_id = $name."/".$i."/".$j."/r/";
        echo "<label>Повторения<input type='number' name='repeats' value='".$exercises_array[$name][$i][$j][2]."'></label>";
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
