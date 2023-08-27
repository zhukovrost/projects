<?php
date_default_timezone_set("Europe/Moscow");
require_once "Question.php";
require_once "Test.php";
require_once "User.php";

#------------------ settings and base -----------------------
function conn_check($conn){
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }else{
    return true;
  }
}


# ----------------- user data -----------------

function check_the_login($user_data, $way = "", $header = true){
  if (!$user_data['auth']){
    if ($header){
      header('Location: '.$way.'log.php?please_log=1');
    }else{
      return false;
    }
  }
  if (!$header){
    return true;
  }
}

function check_if_admin($user_data, $way=""){
  if (check_the_login($user_data, $way, false)) {
    if ($user_data['status'] == 'admin'){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}


function get_user_data($conn, $login, $is_id=false){
  $auth = false;
  $id = 0;
  $name = '';
  $surname = '';
  $thirdname = '';
  $status = '';
  $user_tests_ids = [];
  $user_tests_marks = [];
  $user_tests_durations = [];
  $avatar = '';

  if (isset($login) && $login != ''){
    if ($is_id){
      $select_sql = "SELECT * FROM users WHERE id=$login";
    }else {
      $select_sql = "SELECT * FROM users WHERE login='".$login."'";
    }
    if ($select_result = $conn->query($select_sql)) {
      foreach ($select_result as $item) {
        $id = $item['id'];
        $name = $item['name'];
        $surname = $item['surname'];
        $thirdname = $item['thirdname'];
        $status = $item['status'];
        $avatar = $item['avatar'];
      }

      $auth = true;
    }else{
      echo $conn -> error;
    }
    $select_result->free();
  }

  return array(
    "auth" => $auth,
    "id" => $id,
    "login" => $login,
    "name" => $name,
    "surname" => $surname,
    "thirdname" => $thirdname,
    "status" => $status,
    "user_tests_ids" => $user_tests_ids,
    "user_tests_marks" => $user_tests_marks,
    "user_tests_durations" => $user_tests_durations,
    "avatar" => $avatar,
  );
}

# -------------- other ------------------

function get_avatar($conn, $user_data){
  $avatar_id = $user_data['avatar'];
  $select_sql = "SELECT file FROM avatars WHERE id=$avatar_id";
  if ($result_sql = $conn->query($select_sql)){
    foreach ($result_sql as $item){
      $image = $item['file'];
    }
  }else{
    echo $conn->error;
  }

  return $image;
}


function get_overall_rating($conn){
  $cnt = 0;
  $report_quantity = 0;
  $select_sql = "SELECT rate FROM reports";
  if ($result_sql = $conn->query($select_sql)) {
    foreach ($result_sql as $item) {
      $rate = $item['rate'];
      $report_quantity++;
      $cnt += $rate;
    }
    return round($cnt/$report_quantity, 2);
  }else{
    return "Error 404";
  }

}

function get_theme_id($conn, $theme){
  $id = 0;
  if ($theme != '' && $theme != null){
    $select_item_sql = "SELECT id FROM themes WHERE theme='$theme'";
    $select_item_result = $conn->query($select_item_sql);
    foreach ($select_item_result as $item){
      $id = $item['id'];
    }
  }
  return $id;
}

#---------------- reg and log ----------------------

	function log_warning($if, $warning){
		if (isset($_POST['log_done'])){
			if ($if){
				echo "<p class='warning'>".$warning."</p>";
			}
		}
	}

	function reg_warning($if, $warning){
		if (isset($_POST['reg_done'])){
			if ($if){
				echo "<p class='warning'>".$warning."</p>";
			}
		}
	}

  function print_message($message, $type=3){
    # 0 - error, 1 - success, 2 - warning, 3 - nothing
    if ($type == 0){
      $beginning = "ERROR: ";
    } else if ($type == 1){
      $beginning = "SUCCESS: ";
    }else if ($type == 2){
      $beginning = "WARNING: ";
    }else{
      $beginning = "";
    }

    echo "<script>alert('".$beginning.$message."')</script>";
  }


# ----------------------- QUESTIONS AND TESTS ------------------------------------

function get_test_data($conn, $test_id){
  $select_test_sql = "SELECT * FROM tests WHERE id=$test_id";
  if ($select_test_result = $conn->query($select_test_sql)){
    foreach ($select_test_result as $test_data){
      return $test_data;
    }
  }else{
    return false;
  }
}

function get_tests_to_users_data($conn, $id){
  $select_sql = "SELECT * FROM tests_to_users WHERE id=$id";
  if ($select_result = $conn->query($select_sql)){
    foreach ($select_result as $data){
      return $data;
    }
  }else{
    return false;
  }
}

function get_stats($conn, $user_id){
  $select_sql = "SELECT * FROM stats WHERE user=$user_id";
  if ($select_result = $conn->query($select_sql)){
    foreach ($select_result as $data){
      return $data;
    }
  }else{
    return false;
  }
}


function check_deadline($conn, $deadline, $test_id, $tests_to_users_id, $user_id){
  $now = time();
  $deadline = (int)$deadline;

  if ($deadline == -1 || $deadline > $now){
    return true;
  }else{
    $answers = array();
    $question_number = count(json_decode(get_test_data($conn, $test_id)['test']));
    for ($i = 0; $i < $question_number; $i ++){
      array_push($answers, ['']);
    }
    $stats = get_stats($conn, $user_id);
    $user_not_answered_stats = (int)$stats['not_answered'] + count($answers);
    $user_tests_stats = (int)$stats['tests'] + 1;

    $update_sql_1 = "UPDATE tests_to_users SET mark=0, answers='".json_encode($answers)."' WHERE id=$tests_to_users_id";
    $update_sql_2 = "UPDATE stats SET not_answered=$user_not_answered_stats, tests=$user_tests_stats WHERE user=" . $user_id;
    if ($conn->query($update_sql_1) && $conn->query($update_sql_2)){
      return false;
    }else{
      echo $conn->error;
    }
  }
}

function check_the_test($conn, $id, $header=true, $get_stats=false){
  # collecting data
  $solve = get_tests_to_users_data($conn, $id);
  $user_answers = (array)json_decode($solve['answers']);
  $verified_scores = (array)json_decode($solve['verified_scores']);
  $user_id = $solve['user'];
  $test = new Test();
  $test->set_test_data($conn, $solve['test']);
  $test->get_questions($conn);
  # default values
  $all_scores = 0;
  $user_scores = 0;
  $correct = 0;
  $wrong = 0;
  $not_answered = 0;
  $verifying = 0;
  $ver_num = 0;
  # checking the test
  for ($i = 0; $i < count($test->test); $i++){
    $question = $test->questions[$i];
    $all_scores += $question->score;
    if (array_key_exists($i, $user_answers)){
      $user_answer = $user_answers[$i];
    }else{
      $user_answer = [''];
    }

    if ($question->type != 'definite_mc'){
      if ((count($user_answer) == count($question->get_right_answers()) || $question->type == 'definite') && !in_array('', $user_answer)){
        if ($question->type == 'radio' || $question->type == 'checkbox'){
          if ($question->get_right_answers() == $user_answer){
            $correct++;
            $user_scores += $question->score;
          }else{
            $wrong++;
          }
        } else if ($question->type == 'missing_words'){
          $flag = true;
          for ($j = 0; $j < count($question->get_right_answers()); $j++){
            if (mb_strtolower(str_replace(' ', '', $question->get_right_answers()[$j])) != mb_strtolower(str_replace(' ', '', $user_answer[$j]))){
              $flag = false;
              break;
            }
          }

          if ($flag){
            $correct++;
            $user_scores += $question->score;
          }else{
            $wrong++;
          }
        }else if ($question->type == 'definite'){
          $flag = false;
          $user_answer = mb_strtolower(str_replace(' ', '', str_replace('.', '', $user_answer[0])));
          foreach ($question->get_right_answers() as $answer){
            if (mb_strtolower(str_replace(' ', '', str_replace('.', '', $answer))) == $user_answer){
              $flag = true;
              break;
            }
          }

          if ($flag){
            $correct++;
            $user_scores += $question->score;
          }else{
            $wrong++;
          }
        }
      }else{
        $not_answered++;
      }
    }else if ($question->type == 'definite_mc'){
      if ($get_stats && $solve['mark'] == -2){
        $verifying += 1;
      }else {
        $ver_score = $verified_scores[$ver_num];
        $ver_num++;

        if ($ver_score > 0) {
          $correct++;
          $user_scores += $ver_score;
        } else if ($ver_score == 0 && $user_answer[0] != '') {
          $wrong++;
        } else {
          $not_answered++;
        }
      }
    }
  }

  # result
  $mark = round( $user_scores/$all_scores, 4) * 100;

  if($get_stats){
    $result_stats = array(
      "all_scores" => $all_scores,
      "user_scores" => $user_scores,
      "mark" => $mark,
      "all_questions" => count($test->test),
      "correct" => $correct,
      "wrong" => $wrong,
      "not_answered" => $not_answered,
      "verifying" => $verifying
    );
    return $result_stats;
  }else{
    $stats = get_stats($conn, $user_id);
    $stats_mark = (int)$stats['mark'] + $mark;
    $stats_tests = (int)$stats['tests'] + 1;
    $stats_correct = (int)$stats['correct'] + $correct;
    $stats_wrong = (int)$stats['wrong'] + $wrong;
    $stats_not_answered = (int)$stats['not_answered'] + $not_answered;

    $update_stats_sql = "UPDATE stats SET mark=$stats_mark, tests=$stats_tests, correct=$stats_correct, wrong=$stats_wrong, not_answered=$stats_not_answered WHERE user=$user_id";
    $conn->query($update_stats_sql);
    $update_sql = "UPDATE tests_to_users SET mark=$mark WHERE id=$id";
    if ($conn->query($update_sql)){
      if ($header){
        header("Location: result.php?id=$id");
      }else{
        return true;
      }
    }else{
      return false;
    }
  }
}


function print_test_info($conn, $test_info_array){
  $deadline = false;
  $test = new Test();
  $test->set_test_data($conn, $test_info_array['test']);
  $themes = array();
  foreach ($test->themes as $theme_id){
    $select_theme_sql = "SELECT theme FROM themes WHERE id=".$theme_id;
    foreach ($conn->query($select_theme_sql) as $item){
      array_push($themes, $item['theme']);
    }
  }
  $mark = (int)$test_info_array['mark'];
  if ($mark >= 0){
    $status = "passed";
  }else{
    if (check_deadline($conn, $test_info_array['deadline'], $test_info_array['test'], $test_info_array['id'], $test_info_array['user'])){
      switch ($mark){
        case -1:
        case -3:
          $status = "not passed";
          break;
        case -2:
          $status = "verifying...";
      }
    }else{
      $status = "passed";
      $deadline = true;
    }
  }
  ?>
  <section class="test">
    <img src="../img/test-1.png" alt="">
    <div>
      <p><span><?php echo $test->name; ?></span></p>
      <?php if (count($themes) != 0) { ?><p class="theme">Theme(s): <span> <?php foreach ($themes as $theme){ echo $theme.'; '; }?></span></p><?php } ?>
      <p class="time">Time for test: <span><?php echo date('i:s', $test_info_array['duration']); ?></span></p>
      <p class="questions_number">Number of questions: <span><?php echo count($test->test); ?></span></p>
      <?php
      if ($test_info_array['deadline'] != -1){
        $date = date('d.m.Y', $test_info_array['deadline']);
        echo "<p class='deadline'>Deadline: <span>$date</span></p>";
      }
      ?>
      <?php
        if ($status == "passed"){
          echo "<p class='status'>Status:<span>passed</span></p>";
        }
        else if($status == "not passed"){
          echo "<p class='status not_passed'>Status:<span>not passed</span></p>";
        }
        else if($status == "verifying..."){
          echo "<p class='status verifying'>Status:<span>verifying...</span></p>";
        }
       ?>
    </div>
    <?php if (($mark == -1 || $mark == -3) && !$deadline){ ?>
      <a class="start" href="test.php?id=<?php echo $test_info_array['id']; ?>">START</a>
    <?php }else{ ?>
      <a href="result.php?id=<?php echo $test_info_array['id']; ?>">RESULT</a>
    <?php } ?>
  </section>
<?php
  if ($deadline){
    header("Refresh: 0");
  }
}


function print_marks($conn, $user_id, $current_month, $next_month, $is_prev=false, $month){ ?>
  <div class="marks">
  <?php
  $select_sql = "SELECT mark FROM tests_to_users WHERE user=".$user_id." AND date >= $current_month AND date < $next_month AND mark >= 0 ORDER BY date";
  if ($select_result = $conn -> query($select_sql)){
    $amount_of_marks = $select_result->num_rows;
    $all_marks_sum = 0;
    if ($amount_of_marks > 0){
      foreach ($select_result as $mark_data){
        $mark = $mark_data['mark'];
        $all_marks_sum += $mark;
        echo "<div>$mark</div>";
      }
    }else{ ?>
      <p>No marks this month.</p>
    <?php }
    if ($amount_of_marks != 0){
      $average_mark = round($all_marks_sum / $amount_of_marks, 2);
    }else{
      $average_mark = 0;
    }

    $tests_done = $amount_of_marks;
    $verifying = 0;
    $not_done = 0;

    $select_sql2 = "SELECT mark FROM tests_to_users WHERE user=".$user_id." AND date >= $current_month AND date < $next_month AND mark < 0 ORDER BY date";
    if ($select_result2 = $conn->query($select_sql2)){
      foreach ($select_result2 as $mark_data) {
        $mark = $mark_data['mark'];
        switch ($mark){
          case -1:
          case -3:
            $not_done++;
            break;
          case -2:
            $verifying++;
            break;
        }
      }
    }

    $tests_done += $verifying;
  }
  ?>
  </div>
  <!-- Marks' statistic -->
  <div class="statistic">
    <div class="content">
        <h2>Completed tests: <span><?php echo $tests_done; ?></span></h2>
        <h2>Uncompleted tests: <span><?php echo $not_done; ?></span></h2>
        <h2>Verifying: <span><?php echo $verifying; ?></span></h2>
        <h1>Average mark: <span><?php echo $average_mark; ?></span></h1>
    </div>
    <!-- Button to next month -->
    <?php if (!$is_prev){ ?>
    <a href="my_marks.php?month=<?php echo $month + 1; ?>">Next month <img src="../img/Arrow 6.svg" alt=""></a>
    <?php } ?>
  </div>
<?php } ?>