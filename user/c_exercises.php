<?php
include "../templates/func.php";
include "../templates/settings.php";
$user_data->check_the_login();

if (isset($_POST['featured'])){
    $user_data->change_featured($conn, $_POST['featured']);
}

if (isset($_POST['add'])){
    $user_data->add_exercise($conn, $_POST['add']);
}
if (isset($_POST['delete'])){
    $user_data->delete_exercise($conn, $_POST['delete']);
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
			<!-- Buttons to (my / all) exercises -->
            <a href="">Все <img src="../img/arrow_white.svg" alt=""></a>
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
				<section class="exercise_item">
					<!-- Exercise info -->
					<button type="button" class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button type="button" class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>{{ description }}</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">{{ muscle }}</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="{{ image }}" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>{{ name }}</h1>
					<div class="statistic">
						<div class="rating">
							<p>{{ rating }}</p>
							<img src="../img/Star.svg" alt="">
						</div>
						<div class="difficult">
							<p>{{ difficulty }}</p>
							<div></div>
						</div>
					</div>
					<div class="buttons">
							<button type="button" class="add" name="add" value="1">Добавить <img src="../img/add.svg" alt=""></button>
							<button class="favorite" name="featured" value="1"><img src="../img/favorite.svg" alt=""></button>
						</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button type="button" class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button type="button" class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>{{ description }}</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">{{ muscle }}</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="{{ image }}" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>{{ name }}</h1>
					<div class="statistic">
						<div class="rating">
							<p>{{ rating }}</p>
							<img src="../img/Star.svg" alt="">
						</div>
						<div class="difficult">
							<p>{{ difficulty }}</p>
							<div></div>
						</div>
					</div>
					<div class="buttons">
							<button type="button" class="add" name="add" value="1">Добавить <img src="../img/add.svg" alt=""></button>
							<button class="favorite" name="featured" value="1"><img src="../img/favorite.svg" alt=""></button>
						</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button type="button" class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button type="button" class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>{{ description }}</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">{{ muscle }}</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="{{ image }}" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>{{ name }}</h1>
					<div class="statistic">
						<div class="rating">
							<p>{{ rating }}</p>
							<img src="../img/Star.svg" alt="">
						</div>
						<div class="difficult">
							<p>{{ difficulty }}</p>
							<div></div>
						</div>
					</div>
					<div class="buttons">
					<button type="button" class="add" name="add" value="1">Добавить <img src="../img/add.svg" alt=""></button>
							<button class="favorite" name="featured" value="1"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
			</form>
        </div>

		<section class="popup_exercise">
			<section class="content">
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
						<label for="c_exercise_reps">Количество подходов: </label>
						<input type="number" id="c_exercise_reps">
					</div>
					<div>
						<label for="c_exercise_circles">Количество повторений: </label>
						<input type="number" id="c_exercise_circles">
					</div>
					<button><p>Добавить в тренировку</p> <img src="../img/add.svg" alt=""></button>
				</section>
			</section>
		</section>
	</main>

	<?php include "../templates/footer.html" ?>

	<script>
        // Button to see exercise info
        let infoExerciseButton = document.querySelectorAll('.exercise_block .exercise_item .info');
        let closeInfoExerciseButton = document.querySelectorAll('.exercise_block .exercise_item .info_close');
        let infoBlock = document.querySelectorAll('.exercise_block .exercise_item .info_block');

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
		let difficultCountArr = document.querySelectorAll('.exercise_block .exercise_item .statistic .difficult p');
		let difficultBlockArr = document.querySelectorAll('.exercise_block .exercise_item .statistic .difficult');

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
		let exercisesButtons = document.querySelectorAll('.exercise_block .exercise_item .buttons .add');
		let popupExerciseItem = document.querySelector('.popup_exercise .exercise_item');
		let popupExerciseWindow = document.querySelector('.popup_exercise');

		for(let i = 0; i < exercisesButtons.length; i++){
			exercisesButtons[i].addEventListener('click', function(){
				let item = exercisesButtons[i].parentElement.parentElement;
				popupExerciseItem.innerHTML = '';
				popupExerciseItem.innerHTML = item.innerHTML;
				popupExerciseItem.removeChild(popupExerciseItem.lastElementChild);

				popupExerciseWindow.style.cssText = `display: flex`;
			});
		}
	</script>
</body>
</html>