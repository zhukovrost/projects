<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once '../Helpers/func.php'; // Подключение файла с функциями

if (!$user_data->get_auth() || $user_data->get_status() != "coach") // Check authentication and user status, redirect if not a coach
    header("Location: ../Pages/profile.php");

// Fetch necessary parameters from the GET request
$sportsmen = $user_data->get_sportsmen();
$done = (int)$_GET["val"];
$id = $_GET["id"];
$user = $_GET["user"];

if (!is_numeric($user) || !is_numeric($id) || !in_array($id, $sportsmen) || ($done != 0 && $done != 1)) // Validate parameters: $user and $id should be numeric, $id should exist in $sportsmen array, $done should be either 0 or 1
    header("Location: ../Pages/profile.php");

$sql = "UPDATE goals SET done=$done WHERE id=$id"; // Update the 'done' field in the 'goals' table based on provided parameters
if ($conn->query($sql)){
    header("Location: ../Pages/coach.php?user=$user"); // If successful, redirect to coach.php with the user parameter in the URL
}else{ // If query fails, display error, wait for 3 seconds, then redirect to profile.php
    echo $conn->error;
    header("Location: ../Pages/profile.php");
}