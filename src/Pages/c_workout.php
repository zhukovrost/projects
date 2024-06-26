<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями


$user_data->check_the_login("../../"); // Check user login status
$flag = isset($_SESSION["workout"]);

if (empty($_SESSION['workout'])) // Check if $_SESSION['workout'] is set; if not, initialize it as an empty array
    $_SESSION['workout'] = array();


if (isset($_POST["week_days"])){ // Check if form data is submitted
	// get form data
    $name = $_POST["name"];
    $loops = $_POST["loops"];
    $user_id = $user_data->get_id(); // Get user ID
    $sql = "INSERT INTO workouts (creator, name, loops) VALUES ($user_id, '$name', $loops)"; // Create SQL query to insert workout details into the database
    if ($conn->query($sql)){
        $lid = mysqli_insert_id($conn); // Get the last inserted ID
        foreach ($_SESSION["workout"] as $exercise){
            $sql = "INSERT INTO workout_exercises (workout_id, exercise_id, creator, sets, reps) VALUES ($lid, {$exercise->get_id()}, $user_id, {$exercise->get_sets()}, {$exercise->get_reps()})";
            if (!$conn->query($sql)) echo $conn->error; // Insert workout-exercise relations into the database
        }
        foreach ($_POST["week_days"] as $week_day) { // Update $_SESSION["program"] with workout IDs for selected week days
            $_SESSION["program"][(int)$week_day] = $lid;
        }
        $_SESSION["workout"] = array(); // Clear $_SESSION["workout"] after processing
        header("Location: c_program.php"); // Redirect to the c_program.php page

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
				<section class="workouts-card__exercises-cover">
					<!-- Exercise items -->
                    <?php
                    if ($flag) {
                        foreach ($_SESSION["workout"] as $exercise){ // print exercises list
                            $exercise->print_it($conn);
                        }
                    }
                    ?>
				</section>
				<!-- Info about day workout -->
				<section class="workouts-card__info">
					<!-- Muscle groups -->
                    <?php print_workout_info_function($_SESSION["workout"]); // print workout info ?>
					<!-- Decorative line -->
					<div class="workouts-card__info-line"></div>
					<!-- Exercise info -->
                    <p class="workouts-card__item">Упражнений: <span><?php if ($flag) echo count($_SESSION["workout"]); else echo 0; ?></span></p>
					<!-- Decorative line -->
					<div class="workouts-card__info-line"></div>
					<!-- Buttons edit and start -->
					<div class="day-workouts__card-buttons day-workouts__card-buttons--c">
						<a class="button-text day-workouts__card-button day-workouts__card-button--add" href="c_exercises.php"><p>Добавить упражнение</p> <img src="../../assets/img/add.svg" alt=""></a>
						<a href="../Actions/clear_workout.php" class="button-text day-workouts__card-button"><p>Очистить</p> <img src="../../assets/img/delete.svg" alt=""></a>
					</div>
				</section>
			</section>
			<form method="post" class="c-workout__info">
				<section class="c-workout__info-header">
					<h1 class="c-workout__info-title">Название:</h1>
                    <input class="c-workout__info-name" type="text" placeholder="Название тренировки" value="" name="name">
				</section>
                <section class="c-workout__info-header c-workout__info-header--circle">
                    <h2 class="c-workout__info-subtitle">Количество кругов</h2>
                    <input class="c-workout__info-circles" type="number" value="1" name="loops">
                </section>
				<section class="c-workout__days">
					<h1 class="c-workout__days-title">ДНИ НЕДЕЛИ</h1>
					<section class="c-workout__days-content">
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days1" value="0">
							<label class="<?php busy_or_free($_SESSION["program"][0]); ?>" for="week_days1">Понедельник</label>
						</div>
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days2" value="1">
							<label class="<?php busy_or_free($_SESSION["program"][1]); ?>" for="week_days2">Вторник</label>
						</div>
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days3" value="2">
							<label class="<?php busy_or_free($_SESSION["program"][2]); ?>" for="week_days3">Среда</label>
						</div>
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days4" value="3">
							<label class="<?php busy_or_free($_SESSION["program"][3]); ?>"  for="week_days4">Четверг</label>
						</div>
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days5" value="4">
							<label class="<?php busy_or_free($_SESSION["program"][4]); ?>" for="week_days5">Пятница</label>
						</div>
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days6" value="5">
							<label class="<?php busy_or_free($_SESSION["program"][5]); ?>" for="week_days6">Суббота</label>
						</div>
						<div class="c-workout__days-item">
							<input class="c-workout__days-checkbox" type="checkbox" name="week_days[]" id="week_days7" value="6">
							<label class="<?php busy_or_free($_SESSION["program"][6]); ?>"  for="week_days7">Воскресенье</label>
						</div>
					</section>
				</section>
				<button class="button-text c-workout__days-add" type="submit"><p>Добавить в программу</p> <img src="../../assets/img/add.svg" alt=""></button>
                <a class="button-text c-workout__back-button" href="c_program.php">Назад</a>
			</form>
		</div>


		<section class="popup-exercise">
			<section class="popup-exercise__content">
				<button class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<section class="exercise-item">
					<!-- Exercise info -->
					<button type="button" class="exercise-item__info-btn"><img src="../../assets/img/info.svg" alt=""></button>
					<div class="exercise-item__info-content">
						<button type="button" class="exercise-item__info-close"><img src="../../assets/img/close.svg" alt=""></button>
						<p class="exercise-item__info-text">{{ description }}</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="exercise-item__muscle-groups">{{ muscle }}</div>
					<!-- Exercise image -->
					<img class="exercise-item__img" src="{{ image }}" alt="">
					<!-- Decoration line -->
					<div class="exercise-item__line"></div>
					<!-- Exercise title -->
					<h1 class="exercise-item__title">{{ name }}</h1>
					<div class="exercise-item__statistic">
						<div class="exercise-item__rating">
							<p class="exercise-item__score">{{ rating }}</p>
							<img class="exercise-item__star" src="../../assets/img/Star.svg" alt="">
						</div>
						<div class="exercise-item__difficult">
							<p class="exercise-item__difficult-number">{{ difficulty }}</p>
							<div class="exercise-item__difficult-item"></div>
						</div>
					</div>
				</section>
				<section class="popup-exercise__info">
					<div class="popup-exercise__info-item">
						<label class="popup-exercise__info-label" for="c_exercise_circles">Количество подходов: </label>
						<input class="popup-exercise__info-input" type="number" id="c_exercise_circles">
					</div>
					<div class="popup-exercise__info-item">
						<label class="popup-exercise__info-label" for="c_exercise_reps">Количество повторений: </label>
						<input class="popup-exercise__info-input" type="number" id="c_exercise_reps">
					</div>
					<button class="popup-exercise__add-button"><p>Добавить в тренировку</p> <img src="../../assets/img/add.svg" alt=""></button>
				</section>
			</section>
		</section>
	</main>

    <?php include BASE_PATH . "/templates/footer.html" ?>

	<script>
		// c_workout variables
		let exerciseCardsArray = document.querySelectorAll('.exercise-item--workout');
		let addWorkoutButton = document.querySelector('.c-workout__days-add');

		if(exerciseCardsArray.length == 0){
			addWorkoutButton.type = 'button';
		}
		else{
			addWorkoutButton.type = 'submit';
		}

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

		//Difficult of exercises
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

		// Info slide items' spans width
        let infoItemsSpans = document.querySelectorAll('.workouts-card__item span');
        let maxSpanWidth = 0;

        for(let i = 0; i < infoItemsSpans.length; i++){
            maxSpanWidth = Math.max(maxSpanWidth, infoItemsSpans[i].clientWidth);
        }

        for(let i = 0; i < infoItemsSpans.length; i++){
            infoItemsSpans[i].style.cssText = `width: ${maxSpanWidth}px;`;
        }

		// set name 'Без названия' if input == ''
		let addToPragramButton = document.querySelector('.c-workout__days-add');
		let workoutNameInput = document.querySelector('.c-workout__info-name');
		addToPragramButton.addEventListener('click', function(){
			if(workoutNameInput.value == ''){
				workoutNameInput.value = "Без названия";
			}
		});


		workoutNameInput.addEventListener('input', function() { // check name if workout
			if (this.value.length > 16) {
				this.value = this.value.slice(0, 16);
			}
		});

	</script>
</body>
</html>