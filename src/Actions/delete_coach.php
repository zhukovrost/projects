<?php
require_once '../../config/settings.php'; // Подключение файла с настройкамиrequire_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (!$user_data->get_auth()){ // Check if the user is authenticated
    header("Location: ../Pages/profile.php"); // Redirect if not authenticated
}
$sql = "DELETE FROM user_to_coach WHERE user=".$user_data->get_id(); // Define SQL query to delete user-to-coach relationship
if ($conn->query($sql)){ // Attempt to execute the deletion query
    header("Location: ../Pages/profile.php"); // Redirect to profile after successful deletion
}else{
    echo $conn->error; // Output error message if query execution fails
    sleep(3);
    header("Location: ../Pages/profile.php"); // Redirect to profile page after an error
}
