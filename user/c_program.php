<?php
include "../templates/func.php";
include "../templates/settings.php";
$user_data->check_the_login();

if (empty($_SESSION["program"])){
    $_SESSION["program"] = array(0, 0, 0, 0, 0, 0, 0);
}

if (isset($_POST["weeks"]) && $_POST["weeks"] > 0){
    if (empty($_POST["date_start"])){
        $date_start = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    }else{
        $date_ex = explode('-', $_POST["date_start"]);
        $date_start = mktime(0, 0, 0, $date_ex[1], $date_ex[2], $date_ex[0]);
    }

    // insert_program and user_to_program and news

    $sql = "INSERT INTO programs (name, program, creator) VALUES ('".$_POST["name"]."', '".json_encode($_SESSION["program"])."', ".$user_data->get_id().")";
    if ($conn->query($sql)){
        $program_id = mysqli_insert_id($conn);
        if ($user_data->get_status() == "user") {
            $sql2 = "INSERT INTO program_to_user (user, program, date_start, weeks) VALUES (".$user_data->get_id().", $program_id, $date_start, ".$_POST['weeks'].")";
            $sql3 = "INSERT INTO news (message, user, date, personal) VALUES ('Пользователь начал программу.', ".$user_data->get_id().", ".time().", 0)";
            if ($conn->query($sql2) && $conn->query($sql3)){
                $_SESSION["workout"] = array();
                $_SESSION["program"] = array();
                header("Location: my_program.php");
            }else{
                echo $conn->error;
            }
        } else if ($user_data->get_status() == "coach") {
            $users = $_POST["users"];
            if (count($users) > 0){
                foreach ($users as $user){
                    $sql2 = "INSERT INTO program_to_user (user, program, date_start, weeks) VALUES (".$user.", $program_id, $date_start, ".$_POST['weeks'].")";
                    $sql3 = "INSERT INTO news (message, user, date, personal) VALUES ('Пользователь начал программу.', ".$user.", ".time().", 0)";
                    if (!$conn->query($sql2) || !$conn->query($sql3)){
                        echo $conn->error;
                    }
                }
                $_SESSION["workout"] = array();
                $_SESSION["program"] = array();
                header("Location: profile.php");
            }else{
                echo "Ошибка: вы не выбрали пользователя";
            }
        }
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

	<main class="c-program">
		<div class="container">
			<section class="day-workouts" navigation="true">
				<?php
                    for($i = 0; $i < 7; $i++){
						$workout = new Workout($conn, $_SESSION["program"][$i], $i);
                        $workout->set_muscles();
                        $workout->print_workout_info_block($i, 0, $user_data->get_id());
                    } ?>
			</section>
			<section class="c-program__create">
				<section class="c-program__workouts">
					<section class="c-program__workouts-list">
						<div class="c-program__workouts-item">
							<p class="c-program__workouts-name">1. День рук</p>
							<button class="button-img c-program__workouts-more"><img src="../img/more_white.svg" alt=""></button>
							<button class="button-img c-program__workouts-delete"><img src="../img/delete.svg" alt=""></button>
							<button class="c-program__workouts-favorite"><img src="../img/favorite.svg" alt=""></button>
						</div>
						<div class="c-program__workouts-item">
						<p class="c-program__workouts-name">2. ффффффя</p>
							<button class="button-img c-program__workouts-more"><img src="../img/more_white.svg" alt=""></button>
							<button class="button-img c-program__workouts-delete"><img src="../img/delete.svg" alt=""></button>
							<button class="c-program__workouts-favorite"><img src="../img/favorite.svg" alt=""></button>
						</div>
						<div class="c-program__workouts-item">
							<p class="c-program__workouts-name">3. Без названия</p>
							<button class="button-img c-program__workouts-more"><img src="../img/more_white.svg" alt=""></button>
							<button class="button-img c-program__workouts-delete"><img src="../img/delete.svg" alt=""></button>
							<button class="c-program__workouts-favorite"><img src="../img/favorite.svg" alt=""></button>
						</div>
					</section>
					<div class="c-program__workouts-buttons">
						<a class="button-text c-program__workouts-button c-program__workouts-button--create" href="c_workout.php"><p>Создать тренировку</p> <img src="../img/add.svg" alt=""></a>
						<a class="button-text c-program__workouts-button" href="clear.php"><p>Очистить программу</p> <img src="../img/delete.svg" alt=""></a>
					</div>
				</section>
				<form class="c-program__duration" method="post">
					<h4 class="c-program__duration-title">Укажите продолжительность программы<br></h4>
					<div class="c-program__duration-date">
						<input class="c-program__duration-weeks" type="number" placeholder="Количество недель" name="weeks">
						<p class="c-program__duration-date-text">начать с</p>
                        <input class="c-program__duration-date-start" type="date" name="date_start">
					</div>
					<button class="button-text c-program__duration-button">Начать программу <img src="../img/arrow_white.svg" alt=""></button>
				</form>
			</section>
			<section class="friends-block">
				<!-- Title and button to search friends -->
				<div class="friends-block__header">
					<h1 class="friends-block__header-title">Программы друзей</h1>
					<a class="friends-block__header-button img" href=""><img src="../img/search.svg" alt=""></a>
				</div>
				<!-- Friends' workout swiper -->
				<swiper-container class="friends-block__swiper" navigation="true">
					<!-- slide -->
                    <?php
                    $user_data->set_subscriptions($conn);
                    print_user_list($conn, $user_data->subscriptions);
                    ?>
				</swiper-container>
			</section>
		</div>

		<section class="popup-exercise">
			<section class="popup-exercise__content">
				<button class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
				<!-- Тренировка -->
				<section class="popup-exercise__info">
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

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
	<script>
		// Workout items
        let workoutItemArr = document.querySelectorAll('.day-workouts__card-content');

        let maxWorkoutItemHeight = 0;

        for(let i = 0; i < workoutItemArr.length; i++){
            if(workoutItemArr[i].clientHeight > maxWorkoutItemHeight){
                maxWorkoutItemHeight = workoutItemArr[i].clientHeight;
            }
        }

        for(let i = 0; i < workoutItemArr.length; i++){
            workoutItemArr[i].style.cssText = `height: ${maxWorkoutItemHeight}px;`;
        }

	</script>
</body>
</html>