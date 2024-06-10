<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (!$user_data->get_auth() || $user_data->get_status() != "doctor") // Check user authentication and status
    header("Location: ../Pages/profile.php"); // Redirect if not authenticated or not a doctor

$sportsmen = $user_data->get_sportsmen();
$item = $_GET["option"];
$id = $_GET["id"];
$user = $_GET["user"];

if (!is_numeric($user) || !is_numeric($id) || !in_array($id, $sportsmen)) // Check the validity of parameters
    header("Location: ../Pages/profile.php"); // Redirect if parameters are invalid

$doctor_data = $user_data->get_doctor_data($conn, $user);

switch ($item){
    case "update": // get update functionality
        break;
    case "delete": // get delete functionality for medicines
        if ($doctor_data["medicines"] == NULL)
            header("Location: ../Pages/profile.php");
        $sql = "DELETE FROM medicines WHERE id=$id"; // Delete the medicine record from the database
        break;
}
if ($conn->query($sql)){ // Execute the SQL query and get redirection or error
    header("Location: ../Pages/doctor.php?user=$user");
}else{
    echo $conn->error; // Output error if query execution fails
    sleep(3);
    header("Location: ../Pages/profile.php"); // Redirect to profile page after an error
}
