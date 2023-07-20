<?php

#------------------ all -----------------------

	function clear_post($loc){
		if (isset($_POST)){
			$_POST = [];
			header('Location: '.$loc);
		}else{
			return false;
		}
	}

	function conn_check($conn){
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}else{
			return true;
		}
	}

	function render($replaces, $tpl_filename){
		$tpl = file_get_contents($tpl_filename);
		$tpl = strtr($tpl, $replaces);
		return $tpl;
	}



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

function get_user_data($conn, $login){
  $auth = false;
  $id = 0;
  $name = '';
  $surname = '';
  $thirdname = '';
  $status = '';
  $user_tests_ids = [];
  $user_tests_marks = [];
  $user_tests_durations = [];

  if (isset($login) && $login != ''){
    $select_sql = "SELECT * FROM users WHERE login='".$login."'";
    if ($select_result = $conn->query($select_sql)) {
      foreach ($select_result as $item) {
        $id = $item['id'];
        $name = $item['name'];
        $surname = $item['surname'];
        $thirdname = $item['thirdname'];
        $status = $item['status'];
        $user_tests_ids = $item['user_tests_ids'];
        $user_tests_marks = $item['user_tests_marks'];
        $user_tests_durations = $item['user_tests_durations'];
      }

      $auth = true;
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
    "user_tests_durations" => $user_tests_durations
    );
}

function select_question($conn, $id){
  $select_question_sql = "SELECT question FROM questions WHERE id=".(int)$id;
  $select_question_result = $conn->query($select_question_sql);
  foreach ($select_question_result as $item){
    $question = json_decode($item['question']);
  }

  return $question;
}

#---------------- reg and log ----------------------

	function success_log($login){
		$_COOKIE['login'] = $login;
		header('Location: ../index.php');
	}

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

  function print_warning($message){
    echo "<div class='warning_block'><label>".$message."</label></div>";
  }

  function print_success_message($message){
    echo "<div class='success_block'><label>".$message."</label></div>";
  }

#------------------ construct ------------------------

function mark($points){
  $points *= 100;
  return $points;
}

?>