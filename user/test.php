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

$position = $_SESSION['position'];

# -------------- CHECK IF COMPLETED ------------------

$check_sql = "SELECT user_tests_marks FROM users WHERE login='".$login."'";
if ($check_result = $conn->query($check_sql)){
  foreach ($check_result as $item){
    $marks = json_decode($item['user_tests_marks']);
  }
  if ((int)$marks[$position] != 0){
    header("Location: my_tests.php?error_completed=1");
  }
}
$check_result->free();

if (empty($_SESSION['result'])){ $_SESSION['result'] = false; }

$test_id = $_SESSION['id'];
# ------------------- TIME ---------------------------

if (empty($_SESSION['start'])){
  $_SESSION['start'] = time();
}
$start = $_SESSION['start'];
$duration = $_SESSION['duration'];
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
  <script src='https://cdn.plot.ly/plotly-2.18.0.min.js'></script>
</head>
<body>
<header class="tests_header">
		<a class="back_button" href="../index.php">На главную</a>
</header>
<main class="question_main">

  <div class="container">

    <?php
    $select_test_sql = "SELECT name, test FROM tests WHERE id='".$test_id."'";
    if ($select_test_result = $conn->query($select_test_sql)){
      foreach ($select_test_result as $item) {
        $test = json_decode($item['test']);
        $name = $item['name'];
      }
      $all_questions = count($test);
      if (isset($_POST['finish_test'])){
        # ------------ RESULTS ----------------------
        if (empty($_POST['test_input'])){
          $amount = 0;
          $right_answers = 0;
          $not_answered = $all_questions;
        }else{
          $_SESSION['result'] = true;
          $not_answered = 0;

          $answer = $_POST['test_input'];
          $right_answers = 0;
          $ids_to_check = array();
          $answers_to_check = array();
          for ($i = 0; $i < $all_questions; $i++){
            $question = select_question($conn, $test[$i]);
            if (isset($answer[$i])){
              if ($question[4] == "radio" || $question[4] == "checkbox" || $question[4] == "missing_words"){
                if ($question[2] == $answer[$i]){ $right_answers++; }
              }else if ($question[4] == "definite"){
                foreach ($question[3] as $item){
                  if (mb_strtoupper(str_replace(" ", "", $item)) == mb_strtoupper(str_replace(" ", "", $answer[$i]))){
                    $right_answers += 1;
                  }
                }
              }else if ($question[4] == "definite_mc"){
                array_push($ids_to_check, $i);
                array_push($answers_to_check, $answer[$i]);
              }
            }else{
              $not_answered++;
            }
          }
          $wrong_answers = $all_questions - count($ids_to_check) - $right_answers - $not_answered;
          $amount = count($ids_to_check);
        }

        # ---------- GETTING THE LIST OF MARKS ------------------
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
          if ($conn->query($update_sql)){
            $error_array['success_verification'] = true;
          }
        }else{
          # ---------------- FAST VERIFICATION ------------------------
          $user_tests_marks[$position] = -1;
          $insert_sql = "INSERT INTO verification_tests (login, test_id, right_answers, amount, answers, answers_ids, position) VALUES ('".$login."', '".$test_id."', '".$right_answers."', '".$all_questions."', '".json_encode($answers_to_check, JSON_UNESCAPED_UNICODE)."', '".json_encode($ids_to_check, JSON_UNESCAPED_UNICODE)."', '".$position."')";
          if ($conn->query($insert_sql)){
            $update_sql2 = "UPDATE users SET user_tests_marks='".json_encode($user_tests_marks)."' WHERE login='".$login."'";
            if ($conn->query($update_sql2)){
              $error_array['success_verification'] = true;
            }
          }
        }

        # ----------------- TEXT OUTPUT ----------------

        echo "<div id='result_pie'></div>"; # pie diagram
        echo "<a class='my_test_btn' href='my_tests.php'>Назад</a>";

      } else {
        # -------------------- TEST PAGE ----------------------
        echo "<div class='timer'><p>15:30</p></div>";
        echo "<form method='post' class='test_output_form'><h2 style='margin-bottom: 20px; font-size: 30px;'>Тест №".$test_id.": ".$name."</h2>";
        echo "<input type='hidden' name='finish_test' value='1'>";

        for ($i = 0; $i < count($test); $i++){
          $question = select_question($conn, $test[$i]);
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
          }else if ($preview_type == "missing_words"){
            for ($j = 0; $j < $question[1]; $j++){
              echo "<label>".($j + 1)." word - </label><input type='text' name='test_input[".$i."][]' class='test_output_input test_output_input_text'><br>";
            }
          }
          echo '</div>';
        }
        echo '
        <div class="test_finish_button">
          <input type="submit" class="my_test_btn" value="Завершить тестирование">
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
<script>
  // ===========TIMER===========

    // Значение времени
    let time = <?php echo (int)$duration; ?>;
    const timer = document.querySelector('.timer p');

    const FinsishButton = document.querySelector('.test_finish_button input');

    FinsishButton.addEventListener('click', function(){
      clearInterval(IntervalTimer);
      time = 0;
    });

    //Если пользователь начал тестирование, то запускается таймер
    let IntervalTimer = setInterval(UpdateTime, 1000);

    function UpdateTime(){
        let minutes = Math.floor(time / 60);
        let seconds = time % 60;
        if (seconds < 10){
            seconds = '0' + seconds;
        }
        if (minutes < 10){
            minutes = '0' + minutes;
        }

        timer.innerHTML = `${minutes}:${seconds}`;
        
        time--;
        if(time == 0){
          clearInterval(IntervalTimer);
          FinsishButton.click();
        }
    }
</script>
<script>
    var data = [{
        values: [<?php
          if ($right_answers != 0){ echo $right_answers; }
          if ($wrong_answers != 0){ if ($right_answers != 0){ echo ", "; } echo $wrong_answers; }
          if (count($ids_to_check) != 0){ if ($right_answers != 0 || $wrong_answers != 0){ echo ", "; } echo count($ids_to_check); }
          if ($not_answered != 0){ if ($right_answers != 0 || $wrong_answers != 0 || count($ids_to_check) != 0){ echo ", "; } echo $not_answered; }
          ?>],
        labels: [<?php
          if ($right_answers != 0){ echo "'Правильные ответы'"; }
          if ($wrong_answers != 0){ if ($right_answers != 0){ echo ", "; } echo "'Неправильные ответы'"; }
          if (count($ids_to_check) != 0){ if ($right_answers != 0 || $wrong_answers != 0){ echo ", "; } echo "'На проверке'"; }
          if ($not_answered){ if ($right_answers != 0 || $wrong_answers != 0 || count($ids_to_check) != 0){ echo ", "; } echo "Не отвечено"; }
          ?>],
        type: 'pie'
    }];

    var layout = {
        height: 400,
        width: 500
    };

    Plotly.newPlot('result_pie', data, layout);
    

</script>
</body>
</html>