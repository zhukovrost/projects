<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once '../Helpers/func.php'; // Подключение файла с функциями


if (empty($_GET["id"]) || $_GET["id"] == ""){ // Redirect if the ID is empty or not provided
    header("Location: ../Pages/requests.php");
}

$user = $_GET["id"];
$sql2 = "DELETE FROM requests WHERE user=$user"; // Construct SQL queries based on the user's status (coach or doctor)
switch ($user_data->get_status()){
    case "coach":
        $sql3 = "INSERT INTO user_to_coach(user, coach) values ($user, ".$user_data->get_id().")";
        break;
    case "doctor":
        $sql3 = "INSERT INTO user_to_doctor(user, doctor) values ($user, ".$user_data->get_id().")";
        break;
}
// Execute the SQL queries to delete the request
$conn->query($sql2);
$conn->query($sql3);

header("Location: ../Pages/requests.php"); // Redirect to the requests page after processing the request
