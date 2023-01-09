<?php
if (!isset($_SESSION)) {
  session_start();
}
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);

# if (!(check_if_admin($conn, $_COOKIE['login'], "../"))){ header("Location: ../index.php"); }

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>Constructor</title>
</head>
<body>
  <main>
    <?php

    # -------------------- LIST OF TESTS -------------------------

    if (empty($_GET['test_id']) || $_GET['test_id'] == ''){
      $select_tests_sql = "SELECT id, name FROM tests ORDER BY id DESC";
      if ($select_tests_result = $conn->query($select_tests_sql)){
        foreach ($select_tests_result as $item) {
          $id = $item['id'];
          $name = $item['name'];

          echo "<a href='post_test.php?test_id=".$id."'>".$id.": ".$name."</a><br>";
        }
      }
      $select_tests_result->free();
    }else{

      # ------------------- TEST PREVIEW -------------------------

      $select_test_sql = "SELECT name, test FROM tests WHERE id='".$_GET['test_id']."'";
      if ($select_test_result = $conn->query($select_test_sql)){
        foreach ($select_test_result as $item) {
          $test = json_decode($item['test']);
          $name = $item['name'];
        }

        echo "<h1>Тест №".$_GET['test_id']."</h1><br><h2>".$name."</h2>";

        for ($i = 0; $i < count($test); $i++){
          $question = $test[$i];
          $preview_type = $question[4];
          echo '<div style="border: black 1px solid; margin-bottom: 5px;">';
          echo '<p>Вопрос №'.($i + 1).': '.$question[0].'</p>';
          if ($preview_type == "radio" || $preview_type == "checkbox"){
            foreach ($question[3] as $answer){
              echo '<p><input type="'.$preview_type.'">'.$answer.'</p>';
            }
          }else if ($preview_type == "definite"){
            echo '<input type="text" style="width: 70%">';
          }else if ($preview_type == "definite_mc"){
            echo "<textarea style='width: 70%; height: 100px'></textarea>";
          }
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
          }else{
            echo "<p>Картинки нет</p>";
          }
          echo '</div>';
        }
      }
      $select_test_result->free();
    }
    ?>
  </main>
</body>
</html>
