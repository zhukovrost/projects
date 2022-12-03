<?php
	function render($replaces, $tpl_filename){
		$tpl = file_get_contents($tpl_filename);
		$tpl = strtr($tpl, $replaces);
		return $tpl;
	}

	function conn_check($conn){
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			return false;
		}else{
			return true;
		}
	}

	function success_log($login){
		$_COOKIE['login'] = $login;
		header('Location: ../index.php');
	}

	function success_reg($success){
		echo "<p class='reg_success'>Регистрация прошла успешно</p>";
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
	
	function check_the_login($way = ""){
		if (empty($_COOKIE['login'])){
			header('Location: '.$way.'regenlog.php?please_log=1');
		}
	}


?>