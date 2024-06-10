<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями


if (!$user_data->get_auth() || ($user_data->get_status() != "coach" && $user_data->get_status() != "doctor") || empty($_GET["user"]) || $_GET["user"] == ''){ // Check user authentication and status (coach or doctor) and presence of required parameters
    header("Location: ../Pages/profile.php");
}
// Define SQL queries to delete relationships based on the user's status
$sql2 = "DELETE FROM user_to_coach WHERE coach=".$user_data->get_id()." AND user=".$_GET["user"];
$sql1 = "DELETE FROM user_to_doctor WHERE doctor=".$user_data->get_id()." AND user=".$_GET["user"];
$sql3 = "DELETE FROM doctor_data WHERE doctor=".$user_data->get_id()." AND user=".$_GET["user"];
$sql4 = "DELETE FROM coach_data WHERE coach=".$user_data->get_id()." AND user=".$_GET["user"];

// Perform deletion based on user status (coach or doctor)
switch ($user_data->get_status()){
    case "coach":
        $conn->query($sql2);
        $conn->query($sql4);
        header("Location: ../Pages/coach.php");
        break;
    case "doctor":
        $conn->query($sql1);
        $conn->query($sql3);
        header("Location: ../Pages/doctor.php");
        break;
    default:
        header("Location: ../Pages/profile.php");
}
