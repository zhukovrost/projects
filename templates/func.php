<?php

#------------------ all -----------------------

	function clear_post($loc){
		if (isset($_POST)){
			$_POST = [];
			return true;
			header('Location: '.$loc);
		}else{
			return false;
		}
	}

	function conn_check($conn){
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			return false;
		}else{
			return true;
		}
	}

	function render($replaces, $tpl_filename){
		$tpl = file_get_contents($tpl_filename);
		$tpl = strtr($tpl, $replaces);
		return $tpl;
	}



function check_the_login($way = "", $header = true){
  if (empty($_COOKIE['login'])){
    if ($header){
      header('Location: '.$way.'log.php?please_log=1');
    }else{
      return false;
    }
  }else if ($_COOKIE['login'] == ""){
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

function check_if_admin($conn, $login, $way=""){
  if (check_the_login($way, false)) {
    $check_sql = "SELECT status FROM users WHERE login='".$login."'";
    if ($check = $conn->query($check_sql)){
      foreach ($check as $user){
        $status = $user['status'];
      }
      if ($status == 'admin'){
        return true;
      }else if ($status == 'test'){
        return false;
      }
    }else{
      return false;
    }
  }
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

#------------------ construct ------------------------



?>