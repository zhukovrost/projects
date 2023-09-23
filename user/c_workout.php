<?php
include "../templates/func.php";
include "../templates/settings.php";

$user_data->check_the_login();
$flag = isset($_SESSION["workout"]);

if (isset($_POST["week_days"])){
    $name = $_POST["name"];
    $loops = $_POST["loops"];
    $exercises = [];
    $reps = [];
    $approaches = [];
    foreach ($_SESSION["workout"] as $exercise){
        array_push($exercises, $exercise->get_id());
        array_push($reps, $exercise->reps);
        array_push($approaches, $exercise->approaches);
    }
    $user_id = $user_data->get_id();
    $sql = "INSERT INTO workouts (creator, name, exercises, reps, approaches, loops) VALUES ($user_id, '$name', '".json_encode($exercises)."', '".json_encode($reps)."', '".json_encode($approaches)."', $loops)";
    if ($conn->query($sql)){
        $lid = mysqli_insert_id($conn);

        foreach ($_POST["week_days"] as $week_day) {
            $_SESSION["program"][(int)$week_day] = $lid;
        }
        $_SESSION["workout"] = array();
        header("Location: c_program.php");

    }else{
        echo $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>

	<main class="c_workout">
		<div class="container">
			<!-- Content of workout -->
			<section class="content">
				<!-- Exercises array -->
				<section class="exercise_cover">
					<!-- Exercise items -->
                    <?php
                    if ($flag) {
                        foreach ($_SESSION["workout"] as $exercise){
                            $exercise->print_it($conn);
                        }
                    }
                    ?>
				</section>
				<!-- Info about day workout -->
				<section class="workout_info">
					<!-- Muscle groups -->
                    <?php print_workout_info_function($_SESSION["workout"]); ?>
					<!-- Decorative line -->
					<div class="line"></div>
					<!-- Exercise info -->
					<div class="exercise">
                        <p>Упражнений: <span><?php if ($flag) echo count($_SESSION["workout"]); else echo 0; ?></span></p>
					</div>
					<!-- Decorative line -->
					<div class="line"></div>
					<!-- Buttons edit and start -->
					<div class="buttons">
						<a href="c_exercises.php"><p>Добавить</p> <img src="../img/add.svg" alt=""></a>
						<button><p>Очистить</p> <img src="../img/delete.svg" alt=""></button>
					</div>
				</section>
			</section>
			<form method="post" class="other">
				<section class="title">
					<h1>Название:</h1>
                    <input type="text" placeholder="Название тренировки" value="Моя тренировка" name="name">
				</section>
                <section class="title">
                    <h2>Количество куругов</h2>
                    <input type="number" value="1" name="loops">
                </section>
				<section class="days">
					<h1>ДНИ НЕДЕЛИ</h1>
					<section>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days1" value="0">
							<label class="<?php busy_or_free($_SESSION["program"][0]); ?>" for="week_days1">Понедельник</label>
						</div>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days2" value="1">
							<label class="<?php busy_or_free($_SESSION["program"][1]); ?>" for="week_days2">Вторник</label>
						</div>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days3" value="2">
							<label class="<?php busy_or_free($_SESSION["program"][2]); ?>" for="week_days3">Среда</label>
						</div>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days4" value="3">
							<label class="<?php busy_or_free($_SESSION["program"][3]); ?>"  for="week_days4">Четверг</label>
						</div>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days5" value="4">
							<label class="<?php busy_or_free($_SESSION["program"][4]); ?>" for="week_days5">Пятница</label>
						</div>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days6" value="5">
							<label class="<?php busy_or_free($_SESSION["program"][5]); ?>" for="week_days6">Суббота</label>
						</div>
						<div>
							<input type="checkbox" name="week_days[]" id="week_days7" value="6">
							<label class="<?php busy_or_free($_SESSION["program"][6]); ?>"  for="week_days7">Воскресенье</label>
						</div>
					</section>
				</form>
				<button type="submit"><p>Добавить в программу</p> <img src="../img/add.svg" alt=""></button>
                <a class="button-text c-workout__back-button" href="c_program.php">Назад</a>
			</section>
		</div>


		<section class="popup-exercise">
			<section class="popup-exercise__content">
				<button class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
				<section class="exercise_item">
					<!-- Exercise info button -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<!-- Info text -->
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>{{ description }}</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/biceps_4.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h2>атжумания</h2>
					<!-- Rating and difficult -->
					<div class="statistic">
						<div class="rating">
							<p>4,5</p>
							<img src="../img/Star.svg" alt="">
						</div>
						<div class="difficult">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div>
					</div>
				</section>
				<section class="popup-exercise__inf">
					<div class="popup-exercise__info-item">
						<label class="popup-exercise__info-label" for="c_exercise_circles">Количество подходов: </label>
						<input class="popup-exercise__info-input" type="number" id="c_exercise_circles">
					</div>
					<div class="popup-exercise__info-item">
						<label class="popup-exercise__info-label" for="c_exercise_reps">Количество повторений: </label>
						<input class="popup-exercise__info-input" type="number" id="c_exercise_reps">
					</div>
					<button class="popup-exercise__add-button"><p>Добавить в тренировку</p> <img src="../img/add.svg" alt=""></button>
				</section>
			</section>
		</section>
	</main>

    <?php include "../templates/footer.html" ?>

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


		// Popup exercises
		let exercisesButtonsEdit = document.querySelectorAll('.c_workout .exercise_item .buttons .edit');
		let popupExerciseItem = document.querySelector('.popup-exercise .exercise_item');
		let popupExerciseWindow = document.querySelector('.popup-exercise');
		let popupExerciseReps = document.querySelector('#c_exercise_reps');
		let popupExerciseCircles = document.querySelector('#c_exercise_circles');

		for(let i = 0; i < exercisesButtonsEdit.length; i++){
			exercisesButtonsEdit[i].addEventListener('click', function(){
				let item = exercisesButtonsEdit[i].parentElement.parentElement;
				popupExerciseItem.innerHTML = '';
				popupExerciseItem.innerHTML = item.innerHTML;
				popupExerciseItem.removeChild(popupExerciseItem.lastElementChild);
				
				let caption = item.children[7].children[0].innerHTML;
				popupExerciseReps.value = caption.split(" x ")[0];
				popupExerciseCircles.value = caption.split(" x ")[1];


				popupExerciseWindow.classList.add("open");
			});
		}

		// Popup exercise window
		const closeBtn = document.querySelector('.popup-exercise .close');
		closeBtn.addEventListener('click', function(){
			popupExerciseWindow.classList.remove("open");
		});

		window.addEventListener('keydown', (e) => {
		if(e.key == "Escape"){
			popupExerciseWindow.classList.remove("open");
		}
		});

		document.querySelector('.popup-exercise .content').addEventListener('click', event => {
			event.isClickWithInModal = true;
		});

		popupExerciseWindow.addEventListener('click', event =>{
		if(event.isClickWithInModal) return;
			event.currentTarget.classList.remove('open');
		});

	</script>
    </script>
</body>
</html>