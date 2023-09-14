<?php
include "../templates/func.php";
include "../templates/settings.php";
$user_data->check_the_login();

if (empty($_SESSION["program"])){
    $_SESSION["program"] = array(0, 0, 0, 0, 0, 0, 0);
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
                        $workout->print_workout_info($i);
                    } ?>
			</section>

			<!-- <section class="cover" navigation="true">
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<h2>День рук</h2>
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
						<div class="buttons">
							<button><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/edit.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<h2>День рук</h2>
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
						<div class="buttons">
							<button><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/edit.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<h2>День рук</h2>
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
						<div class="buttons">
							<button><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/edit.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<h2>День рук</h2>
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
						<div class="buttons">
							<button><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/edit.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<h2>День рук</h2>
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
						<div class="buttons">
							<button><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/edit.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<div class="day_off">Выходной</div>
					</div>
				</section>
				<section class="item">
					<h3>Понедельник</h3>
					<div class="content">
						<h2>День ффффффффф</h2>
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
						<div class="buttons">
							<button><img src="../img/more_white.svg" alt=""></button>
							<button><img src="../img/edit.svg" alt=""></button>
							<button><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
			</section> -->
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
				<section class="duration">
					<h4>Укажите продолжительность программы<br><span>(по желанию)</span></h4>
					<div>
						<input type="number" placeholder="Количество дней">
						<p>начало с</p>
						<input type="date">
					</div>
					<button>Начать программу <img src="../img/arrow_white.svg" alt=""></button>
				</section>
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