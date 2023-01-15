<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

check_the_login("../");

$login = $_COOKIE['login'];

if (empty($_POST['id']) || $_POST['id'] == ''){
  if (empty($_SESSION['id']) || $_SESSION['id'] == ''){
    header("Location: my_tests.php");
  }
}else{
  $_SESSION['id'] = $_POST['id'];
  $_SESSION['duration'] = $_POST['duration'];
}
# ------------------- TIME ---------------------------
if (empty($_SESSION['start'])){
  $_SESSION['start'] = time();
}
$end = (int)$_SESSION['start'] + (int)$_SESSION['duration'];
/*
$_SESSION['duration'] - время отведённое на тест в секундах
$_SESSION['start'] - время начала тестирования формата timestamp
<?php echo $_SESSION['duration'] ?> - вывод переменной в HTML/JS код
*/
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Montserrat+Alternates:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,800&display=swap" rel="stylesheet">
</head>
<body>
<main class="question_main">
  <div class="container">
    <p id="demo"></p>
    <?php
    $select_test_sql = "SELECT name, test FROM tests WHERE id='".$_SESSION['id']."'";
    if ($select_test_result = $conn->query($select_test_sql)){
      foreach ($select_test_result as $item) {
        $test = json_decode($item['test']);
        $name = $item['name'];
      }

      echo "<div class='test_output_form'><h2 style='margin-bottom: 20px; font-size: 30px;'>Тест №".$_GET['test_id'].": ".$name."</h2>";

      for ($i = 0; $i < count($test); $i++){
        $question = $test[$i];
        $preview_type = $question[4];
        echo '<div class="test_output_item">';

        # ------------------ IMAGE ------------------------
        if ($question[5] != null){
          $image_id = $question[5];
          $select_image_sql = "SELECT image FROM test_images where id='".$image_id."'";
          if ($select_image_result = $conn->query($select_image_sql)){
            foreach ($select_image_result as $item){
              $image = $item['image'];
            }
            echo '<img src="data:image;base64,'.base64_encode($image).'"/>'; # image
          }
          $select_image_result->free();
        }
        # ----------------- TEST ---------------------
        echo '<p>'.($i + 1).'. '.$question[0].'</p>';
        if ($preview_type == "radio"){
          foreach ($question[3] as $answer){
            echo '<input class="test_output_input" type="radio"><label>'.$answer.'</label>';
          }
        } else if ($preview_type == "checkbox"){
          foreach ($question[3] as $answer){
            echo '<input class="test_output_input2" type="checkbox"><label>'.$answer.'</label>';
          }
        }else if ($preview_type == "definite"){
          echo '<input class="test_output_input test_output_input_text" type="text" >';
        }else if ($preview_type == "definite_mc"){
          echo '<textarea class="test_output_input test_output_input_textarea"></textarea>';
        }
        echo '</div>';
      }
      echo '
      <div class="test_finish_button">
        <input type="submit" class="button" value="Завершить тестирование" name="finish_test">
      </div>
      ';

      echo "</div>";
    }
    ?>
  </div>
</main>

<?php include "../templates/footer.html"; ?>
</body>
</html>