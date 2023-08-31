<?php
date_default_timezone_set("Europe/Moscow");
require_once "User.php";
require_once "Report.php";
require_once "Exercise.php";
require_once "Program.php";
require_once "Workout.php";

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

function inc_head($title="OpenDoor", $main_dir=false){
    if ($main_dir){
        $way = '';
    }else{
        $way = '../';
    }
    include "head.php";
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

function translate_group ($group){
    switch ($group){
        case "arms":
            return "Руки";
        case "legs":
            return "Ноги";
        case "press":
            return "Пресс";
        case "back":
            return "Спина";
        case "chest":
            return "Грудь";
        case "cardio":
            return "Кардио";
    }
}

function get_day($day_number) {
    // Create an array with day names
    $days_of_week = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");
    // Check if the day number is within a valid range (1-7)
    // Get the corresponding day of the week
    $day_of_week = $days_of_week[$day_number];
    return $day_of_week;
}
