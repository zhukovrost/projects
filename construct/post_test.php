<?php
if (!isset($_SESSION)) {
  session_start();
}
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);
# if (!(check_if_admin($conn, $_COOKIE['login'], "../"))){ header("Location: ../index.php"); }

$error_array = array(
  "post_test_success" => false
);

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
<header class="question_header">
<?php
if (empty($_GET['test_id']) || $_GET['test_id'] == ''){
  echo '<a class="back_button" href="construct.php">Конструктор</a><a class="back_button" href="../index.php">На главную</a>';
}else{
  echo '<a class="back_button" href="post_test.php?test_id">Назад</a>';
}
?>
</header>
  <main class="question_main">
    <div class="container">
    <?php

    # --------------------  pushing tests to db  -------------------------

    if (isset($_POST['push_id'])){
      foreach ($_POST['users_array'] as $login){
        $select_arrays_sql = "SElECT user_tests_ids, user_tests_completed FROM users WHERE login='".$login."'";
        if ($select_arrays_result = $conn->query($select_arrays_sql)){
          foreach ($select_arrays_result as $item){
            $tests_array = json_decode($item['user_tests_ids']);
            $tests_completed = json_decode($item['user_tests_completed']);
          }
          $push_id = (int)$_POST['push_id'];
          array_push($tests_array, $push_id);
          array_push($tests_completed, 0);

          $update_sql = "UPDATE users SET user_tests_ids='".json_encode($tests_array)."', user_tests_completed='".json_encode($tests_completed)."' WHERE login='".$login."'";
          if ($conn->query($update_sql)){
            $error_array['post_test_success'] = true;
          }
        }
        $select_arrays_result->free();
      }
    }

    # -------------------- LIST OF TESTS -------------------------

    if (empty($_GET['test_id']) || $_GET['test_id'] == ''){
      if ($error_array['post_test_success']){
        echo "<div class='success_block'><label style='color: white;'>Тест был успешно выложен для выбранных учеников</label></div>";
      }
      $select_tests_sql = "SELECT id, name FROM tests ORDER BY id DESC";
      if ($select_tests_result = $conn->query($select_tests_sql)){
        echo "<div class='test_output_form'>";
        foreach ($select_tests_result as $item) {
          $id = $item['id'];
          $name = $item['name'];

          echo "<a href='post_test.php?test_id=".$id."'>".$id.": ".$name."</a><br>";
        }
        echo "</div>";
      }
      $select_tests_result->free();
    }else{
      # ------------------ IF THE TEST ID SELECTED ---------------------
      # ------------------- THE LIST OF USERS -----------------------

      $select_users_sql = "SELECT login, name, surname FROM users WHERE status='user' ORDER BY surname ASC";

      if ($select_users_result = $conn->query($select_users_sql)){
        echo "<form method='post' class='test_output_form' action='post_test.php?test_id'>";
        foreach ($select_users_result as $userdata){
          echo "<p><input type='checkbox' name='users_array[]' value='".$userdata['login']."'>".$userdata['surname']." ".$userdata['name']."</p>";
        }
        echo '
        <div class="test_finish_button">
          <input type="submit" class="button" value="Выложить тест" name="finish_test">
          <input type="hidden" name="push_id" value="'.$_GET['test_id'].'">
        </div>   
        ';
        echo "</form>";

      }
      $select_users_result->free();

      # ------------------- TEST PREVIEW -------------------------

      $select_test_sql = "SELECT name, test FROM tests WHERE id='".$_GET['test_id']."'";
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
        echo "</div>";
      }
      $select_test_result->free();
    }
    ?>
    </div>
  </main>
</body>
</html>
