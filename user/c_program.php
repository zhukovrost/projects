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

	<main class="c_program">
		<div class="container">
			<section class="cover" navigation="true">
				<?php
                    for($i = 0; $i < 7; $i++){
						$workout = new Workout($conn, $_SESSION["program"][$i], $i);
                        $workout->set_muscles();
                        $workout->print_workout_info_block($i, 0, $user_data->get_id());
                    } ?>
			</section>
			<section class="create_block">
				<section class="workouts_block">
					<section class="list">
						<div>
							<p>1. День рук</p>
							<button class="more"><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
							<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
						</div>
						<div>
							<p>2. День ног</p>
							<button class="more"><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
							<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
						</div>
						<div>
							<p>3. Без названия</p>
							<button class="more"><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
							<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
						</div>
					</section>
					<div class="buttons">
						<a href="c_workout.php"><p>Создать тренировку</p> <img src="../img/add.svg" alt=""></a>
						<button><p>Очистить программу</p> <img src="../img/delete.svg" alt=""></button>
					</div>
				</section>
				<form class="duration" method="post">
					<h4>Укажите продолжительность и название программы<br></h4>
                    <input type="text" name="name">
					<div>
						<input type="number" placeholder="Количество недель" name="weeks">
						<p>начать с</p>
                        <input type="date" name="date_start">
					</div>
					<button>Начать программу <img src="../img/arrow_white.svg" alt=""></button>
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

		<section class="popup_exercise popup_workout">
			<section class="content">
				<button class="close"><img src="../img/close.svg" alt=""></button>
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
				<section class="info">
					<div>
						<label for="c_exercise_circles">Количество подходов: </label>
						<input type="number" id="c_exercise_circles">
					</div>
					<div>
						<label for="c_exercise_reps">Количество повторений: </label>
						<input type="number" id="c_exercise_reps">
					</div>
					<button><p>Добавить в тренировку</p> <img src="../img/add.svg" alt=""></button>
				</section>
			</section>
		</section>
	</main>

	
	<?php include "../templates/footer.html" ?>

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
	<script>
		// Workout items
        let workoutItemArr = document.querySelectorAll('.c_program .cover .item .content');

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