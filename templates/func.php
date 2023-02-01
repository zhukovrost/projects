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

function get_week_day() {
  $week_day = date("l");
  switch ($week_day){
    case "Monday":
      $week_day = 0;
      break;
    case "Tuesday":
      $week_day = 1;
      break;
    case "Wednesday":
      $week_day = 2;
      break;
    case "Thursday":
      $week_day = 3;
      break;
    case "Friday":
      $week_day = 4;
      break;
    case "Saturday":
      $week_day = 5;
      break;
    case "Sunday":
      $week_day = 6;
      break;
  }
  return $week_day;
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

function check_if_passed($conn, $login){
  date_default_timezone_set('Europe/Moscow');
  $now = time();
  $select_sql = "SELECT program_duration, calendar, start_program, completed_program FROM users WHERE login='".$login."'";
  if ($select_result = $conn->query($select_sql)){
    foreach ($select_result as $item){
      $program_duration = $item['program_duration'];
      $calendar = json_decode($item['calendar']);
      $calendar2 = $calendar;
      $start_program = $item['start_program'];
      $completed_program = $item['completed_program'];
      $completed2 = $completed_program;
    }
    if ($start_program - $now >= 86400){
      if (!$completed_program) {

        for ($i = 0; $i < 7; $i++) {
          if ($calendar[0][$i] != 2) {
            $start_weekday_num = $i;
          }
        }

        $day_now = $start_weekday_num;
        for ($i = $now - $start_program; $i >= 0; $i = $i - 86400) {
          $day_now++;
        }
        $week_now = $day_now % 7;
        $day_now = (int)($day_now / 7);


        if ($week_now > $program_duration || ($week_now == $program_duration && $day_now >= $start_weekday_num)) {
          $week_now = $program_duration;
          if ($start_weekday_num == 0) {
            $day_now = 7;
          } else {
            $day_now = $start_weekday_num;
          }
          $completed = 1;
        } else {
          $completed = 0;
        }


        for ($week = $week_now; $week >= 0; $week--) {
          if ($week == $week_now) {
            for ($day = $day_now - 1; $day >= 0; $day--) {
              if ($calendar[$week][$day] == 1) {
                $calendar[$week][$day] = 4;
              }
            }
          } else {
            for ($day = 6; $day >= 0; $day--) {
              if ($calendar[$week][$day] == 1) {
                $calendar[$week][$day] = 4;
              }
            }
          }
        }

        if ($calendar != $calendar2 || $completed != $completed2) {
          $update_sql = "UPDATE users SET calendar='" . json_encode($calendar) . "', completed_program=$completed WHERE login='" . $login . "'";
          if ($conn->query($update_sql)) {
            $insert_sql = "INSERT INTO news (new_id, user, date) VALUES(1, '".$login."', $now)";
            if ($conn->query($insert_sql)){
              return true;
            }else{
              return false;
            }
          } else {
            return false;
          }
        } else {
          return true;
        }
      }else{
        return true;
      }
    }else{
      return true;
    }
  }else{
    return false;
  }
}

function check_if_sub($conn, $your_login, $user_login){
  $select_sql = "SELECT subscriptions FROM users WHERE login='".$your_login."'";
  if ($select_result = $conn->query($select_sql)){
    foreach ($select_result as $item) {
      $subscriptions = json_decode($item['subscriptions']);
    }
    $return = false;
    foreach ($subscriptions as $user){
      if ($user == $user_login){
        $return = true;
        break;
      }
    }
    return $return;
  }
  $select_result->free();
}

# check existing user function (404)

?>