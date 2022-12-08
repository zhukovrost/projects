<?php
function render($replaces, $tpl_filename){
  $tpl = file_get_contents($tpl_filename);
  $tpl = strtr($tpl, $replaces);
  return $tpl;
}

function conn_check($conn){
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }else{
    return true;
  }
}

function check_the_login($way = ""){
  if (empty($_COOKIE['login'])){
    header('Location: '.$way.'regenlog.php?please_log=1');
  }else if ($_COOKIE['login'] == ""){
    header('Location: '.$way.'regenlog.php?please_log=1');
  }
}

function not_empty($cond){
  if (isset($cond)){
    if ($cond != "" || $cond != null){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}

function success_log($login){
  setcookie("login", $login);
  # header('Location: ../index.php');
  header('Location: account.php');
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

?>