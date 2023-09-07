<?php
include "../templates/func.php";
include "../templates/settings.php";
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
					<section class="exercise-item exercise-item--workout">
						<!-- Exercise info button -->
						<button type="button"  class="exercise-item__info-btn"><img src="../img/info.svg" alt=""></button>
						<!-- Info text -->
						<div class="exercise-item__info-content">
							<button type="button" class="exercise-item__info-close"><img src="../img/close.svg" alt=""></button>
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
						<!-- Rating and difficult -->
						<div class="exercise-item__statistic">
							<div class="exercise-item__rating">
								<p class="exercise-item__score">{{ rating }}</p>
								<img class="exercise-item__star" src="../img/Star.svg" alt="">
							</div>
							<div class="exercise-item__difficult">
								<p class="exercise-item__difficult-number">{{ difficulty }}</p>
								<div class="exercise-item__difficult-item"></div>
							</div>
						</div>
						<!-- Count of repetitions -->
						<div class="exercise-item__caption">
							<p class="exercise-item__caption-title">{{ reps }} x {{ approaches }}</p>
						</div>
					</section>
				</section>
				<!-- Info about day workout -->
				<section class="workout_info">
					<!-- Muscle groups -->
					<div class="muscle_groups">
						<p>Руки: <span>70 %</span></p>
						<p>Ноги: <span>70 %</span></p>
						<p>Грудь: <span>70 %</span></p>
						<p>Спина: <span>70 %</span></p>
						<p>Пресс: <span>70 %</span></p>
						<p>Кардио: <span>70 %</span></p>
					</div>
					<!-- Decorative line -->
					<div class="line"></div>
					<!-- Exercise info -->
					<div class="exercise">
						<p>Упражнений: <span>15</span></p>
						<p>Подходов: <span>15</span></p>
						<p>Повторений: <span>15</span></p>
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
			<section class="other">
				<section class="title">
					<h1>Название:</h1>
					<input type="text" placeholder="Название тренировки">
				</section>
				<form action="" class="days">
					<h1>ДНИ НЕДЕЛИ</h1>
					<section>
						<div>
							<input type="checkbox" name="week_days" id="week_days1">
							<label class="busy" for="week_days1">Понедельник</label>
						</div>
						<div>
							<input type="checkbox" name="week_days" id="week_days2">
							<label class="free" for="week_days2">Вторник</label>
						</div>
						<div>
							<input type="checkbox" name="week_days" id="week_days3">
							<label class="busy" for="week_days3">Среда</label>
						</div>
						<div>
							<input type="checkbox" name="week_days" id="week_days4">
							<label class="free"  for="week_days4">Четверг</label>
						</div>
						<div>
							<input type="checkbox" name="week_days" id="week_days5">
							<label class="busy" for="week_days5">Пятница</label>
						</div>
						<div>
							<input type="checkbox" name="week_days" id="week_days6">
							<label class="busy" for="week_days6">Суббота</label>
						</div>
						<div>
							<input type="checkbox" name="week_days" id="week_days7">
							<label class="free"  for="week_days7">Воскресенье</label>
						</div>
					</section>
				</form>
				<button><p>Добавить в программу</p> <img src="../img/add.svg" alt=""></button>
			</section>
		</div>


		<section class="popup_exercise">
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

	<script>
        // Button to see exercise info
        let	InfoExerciseButton = document.querySelectorAll('.c_workout .exercise_item .info');
        let closeInfoExerciseButton = document.querySelectorAll('.c_workout .exercise_item .info_close');
        let infoBlock = document.querySelectorAll('.c_workout .exercise_item .info_block');

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
		let popupExerciseItem = document.querySelector('.popup_exercise .exercise_item');
		let popupExerciseWindow = document.querySelector('.popup_exercise');
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
		const closeBtn = document.querySelector('.popup_exercise .close');
		closeBtn.addEventListener('click', function(){
			popupExerciseWindow.classList.remove("open");
		});

		window.addEventListener('keydown', (e) => {
		if(e.key == "Escape"){
			popupExerciseWindow.classList.remove("open");
		}
		});

		document.querySelector('.popup_exercise .content').addEventListener('click', event => {
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