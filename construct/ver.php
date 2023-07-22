<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }

$error_array = array(
  "success_verification" => false
);
# count checked and sum it with right answers
if (isset($_POST['id'])){
  if (isset($_POST['count'])){
    $new_right_answers = count($_POST['count']);
  }else{
    $new_right_answers = 0;
  }
  $select_sql2 = "SELECT id, login, right_answers, amount, position FROM verification_tests WHERE id='".$_POST['id']."'";
  if ($select_result2 = $conn->query($select_sql2)) {
    foreach ($select_result2 as $item) {
      $id = $item['id'];
      $login = $item['login'];
      $right_answers = $item['right_answers'] + $new_right_answers;
      $amount = $item['amount'];
      $position = $item['position'];

      # select and update marks array
      $select_sql3 = "SELECT user_tests_marks FROM users WHERE login='".$login."'";
      if ($select_result3 = $conn->query($select_sql3)){
        foreach ($select_result3 as $item2){
          $user_tests_marks = json_decode($item2['user_tests_marks']);
        }
        $user_tests_marks[$position] = mark($right_answers/$amount);
        $update_sql = "UPDATE users SET user_tests_marks='".json_encode($user_tests_marks)."' WHERE login='".$login."'";
        if ($conn->query($update_sql)){
          $delete_sql = "DELETE FROM verification_tests WHERE id='".$id."'";
          if ($conn->query($delete_sql)){
            $error_array['success_verification'] = true;
          }
        }
      }
      $select_result3->free();
    }
  }
  $select_result2->free();
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
<body>
<header class="question_header">
  <a class="back_button" href="construct.php">Конструктор</a>
  <a class="back_button" href="post_test.php">Выложить тест</a>
  <a class="back_button" href="ver.php">К ручной проверке</a>
  <a class="back_button" href="../index.php">На главную</a>
</header>
<main class="construct_main">
  <?php
  if ($error_array['success_verification']){
    print_success_message('Успешная проверка');
  }
  $select_sql = "SELECT id, login, test_id, answers, answers_ids FROM verification_tests ORDER BY id DESC";
  if ($select_result = $conn->query($select_sql)){
    $rowsCount = $select_result->num_rows;
    if ($rowsCount != 0){
      foreach ($select_result as $item){
        $id = $item['id'];
        $login = $item['login'];
        $test_id = (int)$item['test_id'];
        $answers = json_decode($item['answers']);
        $answers_ids = json_decode($item['answers_ids']);
        # select questions
        $select_test_sql = "SELECT test, name FROM tests WHERE id=".$test_id;
        if ($select_test_result = $conn->query($select_test_sql)){
          foreach ($select_test_result as $item2){
            $test = json_decode($item2['test']);
            $test_name = $item2['name'];
          }
          echo "<form class='construct_ver_form' method='post'>
        <label>".$test_name." (".$login."):</label>";
          for ($i = 0; $i < count($answers_ids); $i++){
            $answer_id = $answers_ids[$i];
            # print answers with checkboxes
            echo "<div><input type='checkbox' name='count[]'><label>".$test[$answer_id][0]."</label></div>";
            echo "<label>".$answers[$i]."</label>";
          }
          echo "<input type='hidden' name='id' value='".$id."'>";
          echo "<input class='construct_btn ver_btn' type='submit'>";
          echo "</form>";
        }
      }
    }else{
      print_success_message('Все тесты проверены!');
    }
  }
  ?>
</main>
</body>
</html>
