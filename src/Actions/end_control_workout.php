<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (empty($_POST["id"]) || $_POST["id"] == '') // Redirect if the workout ID is empty or not set
    header("Location: ../Pages/coach.php");
$workout = new Control_Workout($conn, $_POST["id"]); // Create an object of Control_Workout using the provided ID from POST data

// Loop through the provided repetitions, set empty values to 0
foreach ($_POST["reps"] as $rep)
    if ($rep == '')
        $rep = 0;

$sql = "UPDATE control_workouts SET is_done=1 WHERE id=".$_POST["id"]; // Construct SQL UPDATE query to mark the workout as done and update repetitions

if (!$conn->query($sql)) // Execute the SQL query and handle any errors
    echo $conn->error;
$i=-1;
foreach ($workout->get_exercises() as $exercise){
    $i++;
    $sql = "UPDATE workout_exercises SET reps={$_POST["reps"][$i]} WHERE exercise_id={$exercise->get_id()} AND workout_id={$workout->get_id()}"; // Construct SQL UPDATE query to mark
    if (!$conn->query($sql)) echo $conn->error;// Execute the SQL query and handle any errors
}
header("Location: ../Pages/coach.php"); // Redirect to coach.php regardless of the SQL execution result