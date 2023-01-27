<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);
session_start();
check_the_login("../");

$login = $_COOKIE['login'];
$error_array = array(
  "success_verification" => false
);

if (empty($_POST['id']) || $_POST['id'] == ''){
  if (empty($_SESSION['id']) || $_SESSION['id'] == ''){
    header("Location: my_tests.php");
  }
}else{
  $_SESSION['id'] = $_POST['id'];
  $_SESSION['duration'] = $_POST['duration'];
  $_SESSION['position'] = $_POST['position'];
  header("Location: test.php");
}

$test_id = $_SESSION['id'];
# ------------------- TIME ---------------------------

if (empty($_SESSION['start'])){
  $_SESSION['start'] = time();
}
$start = $_SESSION['start'];
$duration = $_SESSION['duration'];
$position = $_SESSION['position'];
$end = (int)$_SESSION['start'] + (int)$_SESSION['duration'];
/*
$start - время начала тестирования формата timestamp (секунды)
$duration - время отведённое на тест в секундах
$end - время окончания тестирования формата timestamp (секунды)
<?php echo $duration ?> - вывод переменной в HTML/JS код
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

    <?php
    $select_test_sql = "SELECT name, test FROM tests WHERE id='".$test_id."'";
    if ($select_test_result = $conn->query($select_test_sql)){
      foreach ($select_test_result as $item) {
        $test = json_decode($item['test']);
        $name = $item['name'];
      }
      if (isset($_POST['test_input'])){
        $answer = $_POST['test_input'];
        $all_questions = count($test);
        $right_answers = 0;
        $ids_to_check = array();
        $answers_to_check = array();
        for ($i = 0; $i < $all_questions; $i++){
          if (isset($answer[$i])){
            if ($test[$i][4] == "radio" || $test[$i][4] == "checkbox"){
              if ($test[$i][2] == $answer[$i]){ $right_answers++; }
            }else if ($test[$i][4] == "definite"){
              foreach ($test[$i][3] as $item){
                if (strtoupper(trim($item)) == strtoupper(trim($answer[$i]))){
                    $right_answers += 1;
                }
              }
            }else if ($test[$i][4] == "definite_mc"){
                array_push($ids_to_check, $i);
                array_push($answers_to_check, $answer[$i]);
            }
          }
        }
        $wrong_answers = $all_questions - count($ids_to_check) - $right_answers;
        echo $right_answers."/".$all_questions.", ".count($ids_to_check)." на проверке";
        echo "<a href='my_tests.php'>Назад</a>";
        $amount = count($ids_to_check);

        $select_user_sql = "SELECT user_tests_marks FROM users WHERE login='".$login."'";
        if ($select_user_result = $conn->query($select_user_sql)) {
          foreach ($select_user_result as $item) {
            $user_tests_marks = json_decode($item['user_tests_marks']);
          }
        }
        $select_user_result->free();
        if ($amount == 0){
          $mark = mark($right_answers/$all_questions);

          $user_tests_marks[$position] = $mark;
          $update_sql = "UPDATE users SET user_tests_marks='".json_encode($user_tests_marks)."' WHERE login='".$login."'";
        }else{
          # ---------------- FAST VERIFICATION ------------------------
          $user_tests_marks[$position] = -1;
          $insert_and_upadate_sql = "INSERT INTO verification_tests (login, test_id, right_answers, amount, answers, answers_ids, position) VALUES ('".$login."', '".$test_id."', '".$right_answers."', '".$all_questions."', '".json_encode($answers_to_check, JSON_UNESCAPED_UNICODE)."', '".json_encode($ids_to_check, JSON_UNESCAPED_UNICODE)."', '".$position."')";
          if ($conn->query($insert_and_upadate_sql)){
            $update_sql2 = "UPDATE users SET user_tests_marks='".json_encode($user_tests_marks)."' WHERE login='".$login."'";
            if ($conn->query($update_sql2)){
              $error_array['success_verification'] = true;
            }
          }

        }
      }else{
        echo "<form method='post' class='test_output_form'><h2 style='margin-bottom: 20px; font-size: 30px;'>Тест №".$_GET['test_id'].": ".$name."</h2>";

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
            for ($j = 0; $j < count($question[3]); $j++){
              echo '<input name="test_input['.$i.']" class="test_output_input" value="'.$j.'" type="radio"><label>'.$question[3][$j].'</label>';
            }
          } else if ($preview_type == "checkbox"){
            for ($j = 0; $j < count($question[3]); $j++){
              echo '<input name="test_input['.$i.'][]" class="test_output_input2" value="'.$j.'" type="checkbox"><label>'.$question[3][$j].'</label>';
            }
          }else if ($preview_type == "definite"){
            echo '<input name="test_input['.$i.']" class="test_output_input test_output_input_text" type="text">';
          }else if ($preview_type == "definite_mc"){
            echo '<textarea name="test_input['.$i.']" class="test_output_input test_output_input_textarea"></textarea>';
          }
          echo '</div>';
        }
        echo '
        <div class="test_finish_button">
          <input type="submit" class="button" value="Завершить тестирование">
        </div>
        ';
      }
      echo "</form>";
    }
    $select_test_result->free();
    $conn->close();

    ?>
  </div>
</main>
<?php include "../templates/footer.html"; ?>
</body>
</html>