<?php
$data = file_get_contents("exercises.json");
$exercises_array = json_decode($data, true);
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
<body>
<?php
/*
$exercises_array[group_of_muscles][muscle][exercise]
$exercises_array[$i][0] = group of muscles name
$exercises_array[$i][$j][0] = muscle
$exercises_array[$i][$j][1] = muscle image
$exercises_array[$i][$j][$k][0] = if static - true, if not - false
$exercises_array[$i][$j][$k][1] = exercise name
$exercises_array[$i][$j][$k][2] = if static - seconds, if not - repeats
$exercises_array[$i][$j][$k][3] = exercise image
*/

  for ($i = 0; $i < count($exercises_array); $i++){
    echo "<div class='main_exercise_block'>";
    echo "<h2>".$exercises_array[$i][0]."</h2>";
    echo "<div>";
    for ($j = 1; $j < count($exercises_array[$i]); $j++){
      echo "<label>".$exercises_array[$i][$j][0]."</label><br>";
      echo "<img src='".$exercises_array[$i][$j][1]."'><br>";
      for ($k = 2; $k < count($exercises_array[$i][$j]); $k++){
        echo "<label>".$exercises_array[$i][$j][$k][1]."</label><br>";
        echo "<img src='".$exercises_array[$i][$j][$k][3]."'><br>";
      }
    }
    echo "</div>";
    echo "</div>";
  }
?>
</body>
</html>
