<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (empty($_POST["id"]) || $_POST["id"] == '') // Redirect if the workout ID is empty or not set
    header("Location: ../Pages/coach.php");
$workout = new Control_Workout($conn, $_GET["id"]); // Create an object of Control_Workout using the provided ID from POST data

// Loop through the provided repetitions, set empty values to 0
foreach ($_POST["reps"] as $rep)
    if ($rep == '')
        $rep = 0;

$reps = json_encode($_POST["reps"]); // Encode the repetitions array into JSON format
$sql = "UPDATE control_workouts SET is_done=1, reps='$reps' WHERE id=".$_POST["id"]; // Construct SQL UPDATE query to mark the workout as done and update repetitions

if (!$conn->query($sql)) // Execute the SQL query and handle any errors
    echo $conn->error;
header("Location: ../Pages/coach.php"); // Redirect to coach.php regardless of the SQL execution result