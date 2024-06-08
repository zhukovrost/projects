<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (isset($_GET["header"]) && $_GET["header"] == 0)
    $header = 0; // Set header flag to 0 if 'header' parameter is present and equal to 0
else
    $header = 1;

// Check for 'id' parameter in URL, user authentication, and subscription
if ($_GET["id"] && $user_data->get_auth() && !in_array($_GET["id"], $user_data->subscriptions)){
    $user = $_GET["id"]; // Assign 'id' from URL to $user
    $subscriber = $user_data->get_id(); // Get current user's ID and assign to $subscriber
    $sql = "DELETE FROM subs WHERE (user=$user AND subscriber=$subscriber)"; // SQL query to delete subscription entry from 'subs' table
    if ($conn->query($sql)){ // Execute SQL query and perform redirection based on success or failure
        if ($header)
            header("Location: ../Pages/profile.php?user=$user"); // Redirect to profile page if header flag is set
        else
            header("Location: ../Pages/search_users.php"); // Redirect to search users page if header flag is not set
    }else{
        header("Location: ../index.php"); // Redirect to index page in case of SQL query failure
    }
}else{
    header("Location: ../index.php"); // Redirect to index page
}
