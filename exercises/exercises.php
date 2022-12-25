<?php
include "../templates/settings.php";
include "../templates/func.php";
$data = file_get_contents("exercises.json");
$exercises_array = json_decode($data, true);
session_start();
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
  <a href="exercises.php?page=press">Пресс</a>
  <a href="exercises.php?page=legs">Ноги</a>
  <a href="exercises.php?page=arms">Руки</a>
  <a href="exercises.php?page=back">Спина</a>
  <a href="exercises.php?page=chest_and_shoulders">Грудь и плечи</a>
</nav>
<?php
if (empty($_GET['page'])){
  include "../templates/welcome_to_constructor.html";
}elseif ($_GET['page'] == "my_program") {
  echo "<h1>Coming soon...</h1>";
  # тут возня с сессиями жоская потом сделаю
}else{
  $name = $_GET['page'];
  echo "<h1>".$exercises_array[$name][0]."</h1>";
  for ($i = 1; $i < count($exercises_array[$name]); $i++){
    echo "<div style='border: black 1px dashed'>";
    echo
    "<div class='together'>
      <img src='".$exercises_array[$name][$i][1]."'>
      <h3>".$exercises_array[$name][$i][0]."</h3>
    </div>";
    for ($j = 2; $j < count($exercises_array[$name][$i]); $j++){
      echo
        "<div class='together'>
          <img src='".$exercises_array[$name][$i][$j][3]."'>
          <h4>".$exercises_array[$name][$i][$j][1]."</h4>
        </div>";
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
