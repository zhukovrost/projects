<?php

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

  return base64_encode($image);
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


function select_question($conn, $id){
  $select_item_sql = "SELECT question FROM questions WHERE id=".(int)$id;
  $select_item_result = $conn->query($select_item_sql);
  foreach ($select_item_result as $item){
    $question = json_decode($item['question']);
  }

  return $question;
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

function render($replaces, $tpl_filename){
  $tpl = file_get_contents($tpl_filename);
  $tpl = strtr($tpl, $replaces);
  return $tpl;
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

function get_question_data($conn, $question_id){
  $select_question_sql = "SELECT * FROM questions WHERE id=$question_id";
  if ($select_question_result = $conn->query($select_question_sql)){
    foreach ($select_question_result as $question_data){
      return $question_data;
    }
  }else{
    return false;
  }
}

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

function print_image($conn, $image_id){
  if ($image_id != 0){
    $select_image_sql = "SELECT image FROM test_images WHERE id=$image_id";
    if ($select_image_result = $conn->query($select_image_sql)){
      foreach ($select_image_result as $item){
        $image = $item['image'];
      }
      echo '<img src="data:image;base64,'.base64_encode($image).'"/>'; # image
    }
    $select_image_result->free();
  }
}

function print_question($conn, $question_id, $question_number=0, $extend=false, $user_answers_id=-1){
  $question_data = get_question_data($conn, $question_id);
  $type = $question_data['type'];
  $variants = json_decode($question_data['variants']);
  $right_answers = json_decode($question_data['right_answers']);
  $image_id = $question_data['image'];
  if ($user_answers_id != -1){
    $user_answers = (array)json_decode(get_tests_to_users_data($conn, $user_answers_id)['answers']);
  }
  ?>
  <section class="question">
    <div class="underline_title">
      <h2>Question: <span><?php echo $question_number + 1; ?></span></h2>
      <h2>Score: <span><?php echo $question_data['score']; ?></span></h2>
    </div>
    <div class="content">
      <h1><?php echo $question_data['question']; ?></h1>
      <?php
      switch ($type){
        case "radio":
          ?> <p>Choose one answer:</p><?php
          break;
        case "checkbox":
          ?> <p>Choose n answers:</p> <?php
          break;
        case "definite_mc":
        case "definite":
          ?> <p>Answer the question:</p><?php
          break;
        case "missing_words":
          ?> <p>Enter missing words:</p><?php
          break;
      }

      ?>
      <div class="answers">
        <?php

        if ($type == 'radio' || $type == 'checkbox'){
          for ($i = 0; $i < count($variants); $i++){
            ?> <div> <?php
            if ($user_answers_id == -1 || ($user_answers_id != -1 && $user_answers[$question_number][$i] != "on")){
              echo "<input type='$type' name='test_input[$question_number][]'>";
            }else if ($user_answers[$question_number][$i] == "on"){
              echo "<input type='$type' name='test_input[$question_number][]' checked>";
            }
            echo "<label>$variants[$i]</label>";

            ?> </div> <?php
          }
        }else if ($type == 'missing_words'){
          for ($i = 0; $i < count($right_answers); $i++){
            ?> <div> <?php
            if ($user_answers_id == -1) {
              echo "<input type='text' name='test_input[$question_number][]'>";
            }else{
              $value = $user_answers[$question_number][(string)$i];
              echo "<input type='text' name='test_input[$question_number][]' value='$value'>";
            }
            ?> </div> <?php
          }
        } else if ($type == "definite"){
          if ($user_answers_id == -1){
            echo "<div><input type='text' name='test_input[$question_number][]'></div>";
          }else{
            $value = $user_answers[$question_number][0];
            echo "<div><input type='text' name='test_input[$question_number][]' value='$value'></div>";
          }
        }else if ($type == "definite_mc"){
          if ($user_answers_id == -1 || $user_answers[$question_number] == null){
            echo "<div><textarea name='test_input[$question_number][]'></textarea></div>";
          }else{
            echo "<div><textarea name='test_input[$question_number][]'>".$user_answers[$question_number][0]."</textarea></div>";
          }
        }

          print_image($conn, $image_id);

          if ($extend && $type != "definite_mc"){

            echo "<p>Right answer(s): ";
            foreach ($right_answers as $right_answer) {
              if ($type == "missing_words" || $type == "definite"){
                echo $right_answer."; ";
              }else{
                echo $variants[$right_answer] . "; ";
              }
            }
            echo "</p>";

          }
          ?>
      </div>
    </div>
  </section>
<?php }


function print_test($conn, $test, $extend=false, $user_answers_id=-1){ ?>
<form method="post" class="questions_list">
  <div class="container">

    <?php
      for ($i = 0; $i < count($test); $i++){
        print_question($conn, $test[$i], $i, $extend, $user_answers_id);
      }

      if(!$extend && $user_answers_id == -1){ ?>
        <button class="finish" id="FinsishButton" name="finish" value="1">Finish</button>
      <?php } ?>
  </div>
</form>
<?php }

function print_test_by_id($conn, $test_id, $extend=false, $user_answers_id=-1){
  $test_data = get_test_data($conn, $test_id);
  print_test($conn, json_decode($test_data['test']), $extend, $user_answers_id);
}

function check_the_test($conn, $id, $header=true, $get_stats=false, $time=0){
  # collecting data
  $solve = get_tests_to_users_data($conn, $id);
  $user_answers = (array)json_decode($solve['answers']);
  $verified_scores = (array)json_decode($solve['verified_scores']);
  $test_data = get_test_data($conn, $solve['test']);
  $user_id = $solve['user'];
  $test = json_decode($test_data['test']);
  # default values
  $all_scores = 0;
  $user_scores = 0;
  $correct = 0;
  $wrong = 0;
  $not_answered = 0;
  $verifying = 0;
  # checking the test
  for ($i = 0; $i < count($test); $i++){
    $question_id = $test[$i];
    $question_data = get_question_data($conn, $question_id);
    $all_scores += $question_data['score'];
    $type = $question_data['type'];
    $right_answers = json_decode($question_data['right_answers']);
    if ($type != 'definite_mc'){
      if ($type == "radio" || $type == "checkbox"){
        if ($user_answers[$i] == $right_answers[$i]){
          $user_scores += $question_data['score'];
          $correct++;
        }else if ($user_answers[$i] == [] || $user_answers[$i] == '' || $user_answers[$i] == null){
          $not_answered++;
        }else{
          $wrong++;
        }
      }else{
        $flag = true;
        for ($j = 0; $j < count($right_answers); $j++){
          try {
            $user_answer = mb_strtolower(str_replace(' ', '', $user_answers[$i][$j]));
            if ($right_answers[$i][$j] != $user_answer){
              $flag = false;
              if ($user_answer == [] || $user_answer == '' || $user_answer == null){
                $not_answered++;
              }else{
                $wrong++;
              }
            }
          }catch (Exception $e) {
            $flag = false;
            $not_answered++;
            break;
          }
        }

        if ($flag){
          $correct += count($right_answers);
          $user_scores += $question_data['score'];
        }
      }
    }else{
      if ($get_stats && $solve['mark'] == -2){
        $verifying += 1;
      }else{
        $qid = 'q'.$i;
        if ($verified_scores[$qid] != null){
          $user_scores += (int)$verified_scores[$qid];
          if ((int)$verified_scores[$qid] == $question_data['score']){
            $correct++;
          }else if ($user_answers[$i][0] == ''){
            $not_answered++;
          }else if ((int)$verified_scores[$qid] == 0){
            $wrong++;
          }
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
      "time" => $time,
      "all_questions" => count($test),
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
    $stats_time = (int)$stats['time'] + $time;

    $update_stats_sql = "UPDATE stats SET mark=$stats_mark, tests=$stats_tests, correct=$stats_correct, wrong=$stats_wrong, not_answered=$stats_not_answered, time=$stats_time WHERE user=$user_id";
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
  $test_data = get_test_data($conn, $test_info_array['test']);
  $themes = array();
  foreach (json_decode($test_data['themes']) as $theme_id){
    $select_theme_sql = "SELECT theme FROM themes WHERE id=".$theme_id;
    foreach ($conn->query($select_theme_sql) as $item){
      array_push($themes, $item['theme']);
    }
  }
  $mark = (int)$test_info_array['mark'];
  if ($mark >= 0){
    $status = "passed";
  }else{
    switch ($mark){
      case -1:
      case -3:
        $status = "not passed";
        break;
      case -2:
        $status = "verifying...";
    }
  }
  ?>
  <section class="test">
    <img src="../img/test-1.png" alt="">
    <div>
      <p><span><?php echo $test_data['name']; ?></span></p>
      <?php if (count($themes) != 0) { ?><p class="theme">Theme(s): <span> <?php foreach ($themes as $theme){ echo $theme.'; '; }?></span></p><?php } ?>
      <p class="time">Time for test: <span><?php echo date('i:s', $test_info_array['duration']); ?></span></p>
      <p class="questions_number">Number of questions: <span><?php echo count(json_decode($test_data['test'])); ?></span></p>
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
    <?php if ($mark == -1 || $mark == -3){ ?>
      <a class="start" href="test.php?id=<?php echo $test_info_array['id']; ?>">START</a>
    <?php }else{ ?>
      <a href="result.php?id=<?php echo $test_info_array['id']; ?>">RESULT</a>
    <?php } ?>
  </section>
<?php
}


function print_marks($conn, $user_id, $current_month, $next_month, $is_prev=false){ ?>
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
    <button>Next month <img src="../img/Arrow 6.svg" alt=""></button>
    <?php } ?>
  </div>
<?php } ?>