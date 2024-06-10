<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (!$user_data->get_auth() || $user_data->get_status() != "coach") // Check if the user is authenticated and has the role of a coach
    header("Location: ../Pages/profile.php");

// get sportsmen
$sportsmen = $user_data->get_sportsmen();
// get parameters from the URL
$item = $_GET["item"];
$id = $_GET["id"];
$user = $_GET["user"];

if (!is_numeric($user) || !is_numeric($id) || !in_array($id, $sportsmen)) // Check if the parameters are valid numeric values and if the ID is associated with the coach's sportsmen
    header("Location: ../Pages/profile.php");

// get coach data for a specific user
$coach_data = $user_data->get_coach_data($conn, $user);
if ($coach_data == NULL)
    header("Location: ../Pages/profile.php");// Redirect if coach data is not available for the user

// Perform actions based on the specified item type
switch ($item){
    case "goal": // Code to handle deletion of a goal
        if ($coach_data["goals"] == NULL)
            header("Location: ../Pages/profile.php");
        $sql = "DELETE FROM goals WHERE id=$id";
        break;
    case "competition": // Code to handle deletion of a competition
        if ($coach_data["competitions"] == NULL)
            header("Location: ../Pages/profile.php");
        $sql = "DELETE FROM competitions WHERE id=$id";
        break;
    case "info": // Code to handle deletion of coach advice or information
        if ($coach_data["info"] == NULL)
            header("Location: ../Pages/profile.php");
        $sql = "DELETE FROM coach_advice WHERE id=$id";
        break;
}
if ($conn->query($sql)){ // Execute the SQL query to delete the specified item
    header("Location: ../Pages/coach.php?user=$user");
}else{
    echo $conn->error; // Output error message if query execution fails
    header("Location: ../Pages/profile.php"); // Redirect to profile page after an error
}