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

function check_the_login($way = "", $header = true){
  if (empty($_COOKIE['login'])){
    if ($header){
      header('Location: '.$way.'regenlog.php?please_log=1');
    }else{
      return false;
    }
  }else if ($_COOKIE['login'] == ""){
    if ($header){
      header('Location: '.$way.'regenlog.php?please_log=1');
    }else{
      return false;
    }
  }
  if (!$header){
    return true;
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

function clear_all(){
  $_SESSION = array();
  $_POST = array();
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