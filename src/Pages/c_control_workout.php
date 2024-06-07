<?php

require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями


if (empty($_SESSION['c_workout'])) // Initialize  $_SESSION['c_workout'] array
    $_SESSION['c_workout'] = array();


if ($user_data->get_status() != "coach" || empty($_GET["for"]) || !is_numeric($_GET["for"])) // Check user status and GET parameters; redirect if conditions aren't met
    header("Location: coach.php");

$user_id = $_GET["for"]; // Extract user ID from GET parameter

$flag = isset($_SESSION["c_workout"]);

if (isset($_POST['featured'])) // Check if 'featured' value is set in POST data, then update user's featured status
    $user_data->change_featured($conn, $_POST['featured']);

if (isset($_POST["name"]) && isset($_POST["date"])){ // If 'name' and 'date' values are set in POST data, prepare and execute an SQL query to insert control workout data
    $name = $_POST["name"];
    $loops = $_POST["loops"];
    $exercises = [];
    $date = strtotime($_POST["date"]);
    foreach ($_SESSION["c_workout"] as $exercise){// Collect exercises from $_SESSION["c_workout"]
        array_push($exercises, $exercise->get_id());
    }

	// Prepare and execute SQL query to insert control workout data
    $sql = "INSERT INTO control_workouts (creator, user, name, exercises, date) VALUES (".$user_data->get_id().", $user_id, '$name', '".json_encode($exercises)."', $date)";
    echo $sql;
    if ($conn->query($sql)){
        $_SESSION["c_workout"] = array();
        header("Location: control_workouts.php?user=".$user_id);
    }else{
        echo $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body>
    <?php include BASE_PATH . "/templates/header.php"; // print header template ?>

	<main class="c-workout">
		<div class="container">
			<!-- Content of workout -->
			<section class="workouts-card workouts-card--c">
				<!-- Exercises array -->
				<form method="post" class="workouts-card__exercises-cover">
					<!-- Exercise items -->
                    <?php
                    if ($flag) { // Checks if $flag is set; iterates through the exercises in $_SESSION["c_workout"]
                        foreach ($_SESSION["c_workout"] as $exercise){ // For each exercise print control details
                            $is_featured = $exercise->is_featured($user_data);
                            $exercise->print_control_exercise($conn, $is_featured, false);
                        }
                    }
                    ?>
				</form>
				<!-- Info about day workout -->
				<section class="workouts-card__info">
					<!-- Muscle groups -->
                    <?php print_workout_info_function($_SESSION["c_workout"]); // print info of workout ?>
					<!-- Decorative line -->
					<div class="workouts-card__info-line"></div>
					<!-- Exercise info -->
                    <p class="workouts-card__item">Упражнений: <span><?php if ($flag) echo count($_SESSION["c_workout"]); else echo 0; ?></span></p>
					<!-- Decorative line -->
					<div class="workouts-card__info-line"></div>
					<!-- Buttons edit and start -->
					<div class="day-workouts__card-buttons day-workouts__card-buttons--c">
						<a class="button-text day-workouts__card-button day-workouts__card-button--add" href="c_control_exercises.php?for=<?php echo $user_id; ?>"><p>Добавить упражнение</p> <img src="../../assets/img/add.svg" alt=""></a>
						<a href="../Actions/c_clear.php?for=<?php echo $user_id; ?>" class="button-text day-workouts__card-button"><p>Очистить</p> <img src="../../assets/img/delete.svg" alt=""></a>
					</div>
				</section>
			</section>
			<form method="post" class="c-workout__info">
				<section class="c-workout__info-header c-workout__info-header--control">
					<div class="c-workout__info-header-item">
						<h1 class="c-workout__info-title">Название:</h1>
						<input class="c-workout__info-name" type="text" placeholder="Название тренировки" name="name">
					</div>
					<div class="c-workout__info-header-item">
						<h1 class="c-workout__info-title">Дата:</h1>
						<input class="c-workout__info-name" type="date" name="date">
					</div>
				</section>
				<button class="button-text c-workout__days-add" type="submit"><p>Добавить тренировку</p> <img src="../../assets/img/add.svg" alt=""></button>
                <a class="button-text c-workout__back-button" href="control_workouts.php?user=<?php echo $user_id; ?>">Назад</a>
			</form>
		</div>

	</main>

    <?php include BASE_PATH . "/templates/footer.html" ?>
	<script>


		// Button to see exercise info
        let	InfoExerciseButton = document.querySelectorAll('.exercise-item__info-btn');
        let closeInfoExerciseButton = document.querySelectorAll('.exercise-item__info-close');
        let infoBlock = document.querySelectorAll('.exercise-item__info-content');

        for(let i = 0; i < InfoExerciseButton.length; i++){
            InfoExerciseButton[i].addEventListener('click', function(){
                infoBlock[i].style.cssText = `top: -1%;`;
            });
        }
        for(let i = 0; i < closeInfoExerciseButton.length; i++){
            closeInfoExerciseButton[i].addEventListener('click', function(){
                infoBlock[i].style.cssText = `top: -101%;`;
            });
        }


        // Button submit
		let workoutNameInput = document.querySelector('.c-workout__info-name');
		let dateWokoutInput = document.querySelector('.c-workout__info-name[type=date]');
		let addToPragramButton = document.querySelector('.c-workout__days-add');

		let exerciseItemsArray = document.querySelectorAll('.exercise-item');
		if(!exerciseItemsArray.length){
			addToPragramButton.type = 'button';
		}

		// if values are empty and button is clicked
		addToPragramButton.addEventListener('click', function(){
			if(workoutNameInput.value == ''){
				workoutNameInput.value = "Без названия";
			}
			if (!dateWokoutInput.value) {
				// set today's date
				const todayDate = new Date();
				let year = todayDate.getFullYear();
				let month = todayDate.getMonth() + 1;
				let day = todayDate.getDate();

				if (month < 10) {
					month = `0${month}`;
				}
				if (day < 10) {
					day = `0${day}`;
				}

				const formattedDate = `${year}-${month}-${day}`;

				// set today's date in input
				dateWokoutInput.value = formattedDate;
			}
		});


		//Difficult
		let difficultCountArr = document.querySelectorAll('.exercise-item__difficult-number');
		let difficultBlockArr = document.querySelectorAll('.exercise-item__difficult');

		for(let i = 0; i < difficultCountArr.length; i++){
			difficultBlockArr[i].innerHTML = '';
			for(let j = 0; j < 5; j++){
				let newElem = document.createElement('div');
				newElem.classList.add('exercise-item__difficult-item');
				if(j > Number(difficultCountArr[i].innerHTML) - 1){
					newElem.classList.add('exercise-item__difficult-item--disabled');
				}
				difficultBlockArr[i].appendChild(newElem);
			}
		}
	</script>
</body>
</html>