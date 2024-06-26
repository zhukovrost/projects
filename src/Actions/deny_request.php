<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями


// Check if the 'id' parameter is empty or not provided
if (empty($_GET["id"]) || $_GET["id"] == ""){
    header("Location: ../Pages/requests.php"); // Redirect to requests.php if 'id' is empty or not provided
}

$sql = "DELETE FROM requests WHERE id=".$_GET["id"]." AND receiver=".$user_data->get_id(); // Construct SQL query to delete the specific request
$conn->query($sql); // Execute the SQL query to delete the request
header("Location: ../Pages/requests.php"); // Redirect to requests.php after deleting the request
