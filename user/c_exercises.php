<?php
include "../templates/func.php";
include "../templates/settings.php";
$user_data->check_the_login();

if (empty($_SESSION['workout'])){
    $_SESSION['workout'] = array();
}

if (isset($_POST['featured'])){
    $user_data->change_featured($conn, $_POST['featured']);
}

if (isset($_POST['reps']) && isset($_POST['approaches'])){
    $user_exercise = new User_Exercise($conn, $_POST["id"], $_POST['reps'], $_POST['approaches']);
    array_push($_SESSION['workout'], $user_exercise);
}

if (isset($_GET['my']) && is_numeric($_GET['my'])){
    $my = $_GET['my'];
}else{
    $my = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html" ?>

	<!-- Exercise navigation -->
	<nav class="exercise_nav">
		<div class="container">
            <a href="c_workout.php">Назад</a>
			<!-- Buttons to (my / all) exercises -->
            <?php if ($my) { ?>
                <a href="c_exercises.php?my=0">Все <img src="../img/arrow_white.svg" alt=""></a>
            <?php } else { ?>
                <a href="c_exercises.php?my=1">Мои <img src="../img/arrow_white.svg" alt=""></a>
            <?php } ?>
			<!-- Main search -->
			<select name="exercise_sort" id="">
				<option value="value1" selected>По умолчанию</option>
				<option value="value2">Избранные</option>
				<option value="value3">Рейтинг(возрастание)</option>
				<option value="value4">Рейтинг(убывание)</option>
				<option value="value5">Сложность(возрастание)</option>
				<option value="value6">Сложность(убывание)</option>
			</select>
			<!-- Exercise search -->
			<div class="search">
				<input type="text" placeholder="Искать">
				<button><img src="../img/search_black.svg" alt=""></button>
			</div>
		</div>
	</nav>

	<main>
		<!-- Exercises and filter block -->
		<div class="container exercises_cover">
			<!-- Filter block -->
			<form class="filter_block">
				<!-- Muscle groups filter -->
				<section class="muscle_groups">
					<button type="button">Группы мышц <img src="../img/search_arrow.svg" alt=""></button>
					<div class="content">
						<div>
							<input type="checkbox" name="muscle_groups_search" id="muscle_groups_search1">
							<label for="muscle_groups_search1">Спина</label>
						</div>
						<div>
							<input type="checkbox" name="muscle_groups_search" id="muscle_groups_search2">
							<label for="muscle_groups_search2">Ноги</label>
						</div>
						<div>
							<input type="checkbox" name="muscle_groups_search" id="muscle_groups_search3">
							<label for="muscle_groups_search3">Руки</label>
						</div>
						<div>
							<input type="checkbox" name="muscle_groups_search" id="muscle_groups_search4">
							<label for="muscle_groups_search4">Грудь</label>
						</div>
						<div>
							<input type="checkbox" name="muscle_groups_search" id="muscle_groups_search5">
							<label for="muscle_groups_search5">Пресс</label>
						</div>
						<div>
							<input type="checkbox" name="muscle_groups_search" id="muscle_groups_search6">
							<label for="muscle_groups_search6">Кардио</label>
						</div>
					</div>
				</section>
				<!-- Difficult filter -->
				<section class="difficult">
					<button type="button">Сложность <img src="../img/search_arrow.svg" alt=""></button>
					<div class="content">
						<div>
							<input type="checkbox" name="difficult_search" id="difficult_search1">
							<label for="difficult_search1">5</label>
						</div>
						<div>
							<input type="checkbox" name="difficult_search" id="difficult_search2">
							<label for="difficult_search2">4</label>
						</div>
						<div>
							<input type="checkbox" name="difficult_search" id="difficult_search3">
							<label for="difficult_search3">3</label>
						</div>
						<div>
							<input type="checkbox" name="difficult_search" id="difficult_search4">
							<label for="difficult_search4">2</label>
						</div>
						<div>
							<input type="checkbox" name="difficult_search" id="difficult_search5">
							<label for="difficult_search5">1</label>
						</div>
					</div>
				</section>
				<!-- Rating filter -->
				<section class="rating">
					<button type="button">Рейтинг <img src="../img/search_arrow.svg" alt=""></button>
					<div class="content">
						<div>
							<input type="radio" name="rating_search" id="rating_search1">
							<label for="rating_search1">5</label>
						</div>
						<div>
							<input type="radio" name="rating_search" id="rating_search2">
							<label for="rating_search2">от 4</label>
						</div>
						<div>
							<input type="radio" name="rating_search" id="rating_search3">
							<label for="rating_search3">от 3</label>
						</div>
						<div>
							<input type="radio" name="rating_search" id="rating_search4">
							<label for="rating_search4">от 2</label>
						</div>
						<div>
							<input type="radio" name="rating_search" id="rating_search5">
							<label for="rating_search6">от 1</label>
						</div>
						<div>
							<input type="radio" name="rating_search" id="rating_search6">
							<label for="rating_search6">любой</label>
						</div>
					</div>
				</section>
				<!-- Buttons search and clear -->
				<button type="submit" class="clear">Искать</button>
				<button type="button" class="clear">Очистить</button>
			</form>
			<!-- Exercises array -->
			<form method="post" class="exercise_block">
				<?php
                if ($my){
                    foreach ($user_data->my_exercises as $exercise_id){
                        $exercise = new Exercise($conn, $exercise_id);
                        $is_featured = $exercise->is_featured($user_data);
                        $exercise->print_it($conn, $is_featured, 1, 1);
                    }
                }else{
                    $select_sql = "SELECT id FROM exercises";
                    if ($select_result = $conn->query($select_sql)){
                        foreach ($select_result as $item){
                            $exercise = new Exercise($conn, $item['id']);
                            $is_featured = $exercise->is_featured($user_data);
                            $is_mine = $exercise->is_mine($user_data);
                            $exercise->print_it($conn, $is_featured, $is_mine, 1);
                        }
                    }else{
                        echo $conn->error;
                    }
                }
                ?>
			</form>
        </div>

		<section class="popup_exercise">
			<section class="content">
				<button class="close"><img src="../img/close.svg" alt=""></button>
				<section class="exercise-item">
					<!-- Exercise info -->
					<button type="button" class="exercise-item__info-btn"><img src="../img/info.svg" alt=""></button>
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
					<div class="exercise-item__buttons">
						{{ button }}
						{{ button_featured }}
					</div>
				</section>
								<form method="post" class="info">
					<div>
						<label for="c_exercise_circles">Количество подходов: </label>
						<input type="number" id="c_exercise_circles" name="approaches">
					</div>
					<div>
						<label for="c_exercise_reps">Количество повторений: </label>
						<input type="number" id="c_exercise_reps" name="reps">
					</div>
                    <input class="exercise_id" name="id" type="hidden" value="">
					<button><p>Добавить в тренировку</p> <img src="../img/add.svg" alt=""></button>
				</form>
			</section>
		</section>
	</main>

	<?php include "../templates/footer.html" ?>

	<script>
        // Button to see exercise info
        let infoExerciseButton = document.querySelectorAll('.exercise-item__info-btn');
        let closeInfoExerciseButton = document.querySelectorAll('.exercise-item__info-close');
        let infoBlock = document.querySelectorAll('.exercise-item__info-content');

        for(let i = 0; i < infoExerciseButton.length; i++){
            infoExerciseButton[i].addEventListener('click', function(){
                infoBlock[i].style.cssText = `top: -1%;`;
            });
        }
        for(let i = 0; i < closeInfoExerciseButton.length; i++){
            closeInfoExerciseButton[i].addEventListener('click', function(){
                infoBlock[i].style.cssText = `top: -101%;`;
            });
        }


		// Filter buttons
		let FilterButtonsArr = document.querySelectorAll('.exercises_cover .filter_block section button');
		let FilterBlocksArr = document.querySelectorAll('.exercises_cover .filter_block .content');
		let FilterButtonsArrowArr = document.querySelectorAll('.exercises_cover .filter_block section button img');


		for(let i = 0; i < FilterButtonsArr.length; i++){
			FilterButtonsArr[i].addEventListener('click', function(){
				if(FilterBlocksArr[i].clientHeight == 0){
					FilterBlocksArr[i].style.cssText = `height: auto;`;
                    FilterButtonsArrowArr[i].style.cssText = `transform: rotate(180deg);`;
				}
				else{
					FilterBlocksArr[i].style.cssText = `height: 0px`;
                    FilterButtonsArrowArr[i].style.cssText = `transform: rotate(0deg);`;
				}
			});
		}


		//Difficult
		let difficultCountArr = document.querySelectorAll('.exercise-item__difficult-number');
		let difficultBlockArr = document.querySelectorAll('.exercise-item__difficult');

		for(let i = 0; i < difficultCountArr.length; i++){
			difficultBlockArr[i].innerHTML = '';
            for(let j = 0; j < 5; j++){
				let newElem = document.createElement('div');
				if(j > Number(difficultCountArr[i].innerHTML) - 1){
					newElem.classList.add('disabled');
				}
				difficultBlockArr[i].appendChild(newElem);
			}
        }

		FilterButtonsArr[0].click();

		// Popup exercises
		let exercisesButtons = document.querySelectorAll('.exercise-item__add');
		let popupExerciseItem = document.querySelector('.popup_exercise .exercise-item');
		let popupExerciseWindow = document.querySelector('.popup_exercise');
		let inputExerciseId = document.querySelector('.popup_exercise .exercise_id');

		for(let i = 0; i < exercisesButtons.length; i++){
			exercisesButtons[i].addEventListener('click', function(){
				let item = exercisesButtons[i].parentElement.parentElement;
				popupExerciseItem.innerHTML = '';
				popupExerciseItem.innerHTML = item.innerHTML;
				inputExerciseId.value = exercisesButtons[i].value;
				popupExerciseItem.removeChild(popupExerciseItem.lastElementChild);

				popupExerciseWindow.classList.add("open");
			});
		}

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
</body>
</html>