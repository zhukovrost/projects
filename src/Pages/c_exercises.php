<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if (empty($_SESSION["c_workout"])){
	$_SESSION["c_workout"] = array();
}

$user_data->check_the_login(); // Check user login status
if (empty($_SESSION['workout'])){ // Initialize $_SESSION['workout'] array
    $_SESSION['workout'] = array();
}

if (isset($_POST['featured'])){ // Check if 'featured' value is set in POST data, then update user's featured status
    $user_data->change_featured($conn, $_POST['featured']);
}

if (isset($_POST['reps']) && isset($_POST['approaches'])){ // If 'reps' and 'approaches' values are set in POST data, create a User_Exercise object and add it to the session
    $user_exercise = new User_Exercise($conn, $_POST["id"], $_POST['reps'], $_POST['approaches']);
    $_SESSION['workout'][] = $user_exercise;
}

if (isset($_GET['my']) && is_numeric($_GET['my'])){ // Determine the value of 'my' variable based on the GET parameter 'my' or set a default value
    $my = $_GET['my'];
}else{
    $my = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body>
	<?php include BASE_PATH . "/templates/header.php"; //print header template ?>

	<!-- Exercise navigation -->
	<nav class="exercises-nav">
		<div class="container">
            <a class="button-text exercises-nav__item exercises-nav__item--text" href="c_workout.php">Назад</a>
			<!-- Buttons to (my / all) exercises -->
            <?php if ($my) { ?>
                <a class="button-text exercises-nav__item" href="c_exercises.php?my=0">Все <img src="../../assets/img/arrow_white.svg" alt=""></a>
            <?php } else { ?>
                <a class="button-text exercises-nav__item" href="c_exercises.php?my=1">Мои <img src="../../assets/img/arrow_white.svg" alt=""></a>
            <?php } ?>
			<!-- Main search -->
			<select class="exercises-nav__select" name="exercise_sort">
				<option value="default" selected>По умолчанию</option>
				<option value="favorites">Избранные</option>
				<option value="low-rating">Рейтинг(возрастание)</option>
				<option value="high-rating">Рейтинг(убывание)</option>
				<option value="low-difficult">Сложность(возрастание)</option>
				<option value="high-difficult">Сложность(убывание)</option>
			</select>
			<!-- Exercise search -->
			<div class="exercises-nav__search">
				<input class="exercises-nav__search-input" type="text" placeholder="Искать">
				<p class="exercises-nav__search-img"><img src="../../assets/img/search_black.svg" alt=""></p>
			</div>
		</div>
	</nav>

	<main class="exercises-block">
		<!-- Exercises and filter block -->
		<div class="container">
			<!-- Filter block -->
			<section class="exercises-filter">
				<!-- Muscle groups filter -->
				<section class="exercises-filter__muscle-groups">
					<button class="exercises-filter__button" type="button">Группы мышц <img src="../../assets/img/search_arrow.svg" alt=""></button>
					<div class="exercises-filter__content">
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="muscle_groups_search" id="muscle_groups_search1">
							<label class="exercises-filter__item-label" for="muscle_groups_search1">Спина</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="muscle_groups_search" id="muscle_groups_search2">
							<label class="exercises-filter__item-label" for="muscle_groups_search2">Ноги</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="muscle_groups_search" id="muscle_groups_search3">
							<label class="exercises-filter__item-label" for="muscle_groups_search3">Руки</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="muscle_groups_search" id="muscle_groups_search4">
							<label class="exercises-filter__item-label" for="muscle_groups_search4">Грудь</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="muscle_groups_search" id="muscle_groups_search5">
							<label class="exercises-filter__item-label" for="muscle_groups_search5">Пресс</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="muscle_groups_search" id="muscle_groups_search6">
							<label class="exercises-filter__item-label" for="muscle_groups_search6">Кардио</label>
						</div>
					</div>
				</section>
				<!-- Difficult filter -->
				<section class="exercises-filter__difficult">
					<button class="exercises-filter__button" type="button">Сложность <img src="../../assets/img/search_arrow.svg" alt=""></button>
					<div class="exercises-filter__content">
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="difficult_search" id="difficult_search1">
							<label class="exercises-filter__item-label" for="difficult_search1">5</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="difficult_search" id="difficult_search2">
							<label class="exercises-filter__item-label" for="difficult_search2">4</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="difficult_search" id="difficult_search3">
							<label class="exercises-filter__item-label" for="difficult_search3">3</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="difficult_search" id="difficult_search4">
							<label class="exercises-filter__item-label" for="difficult_search4">2</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="checkbox" name="difficult_search" id="difficult_search5">
							<label class="exercises-filter__item-label" for="difficult_search5">1</label>
						</div>
					</div>
				</section>
				<!-- Rating filter -->
				<section class="exercises-filter__rating">
					<button class="exercises-filter__button" type="button">Рейтинг <img src="../../assets/img/search_arrow.svg" alt=""></button>
					<div class="exercises-filter__content">
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="radio" name="rating_search" id="rating_search1">
							<label class="exercises-filter__item-label" for="rating_search1">5</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="radio" name="rating_search" id="rating_search2">
							<label class="exercises-filter__item-label" for="rating_search2">от 4</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="radio" name="rating_search" id="rating_search3">
							<label class="exercises-filter__item-label" for="rating_search3">от 3</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="radio" name="rating_search" id="rating_search4">
							<label class="exercises-filter__item-label" for="rating_search4">от 2</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="radio" name="rating_search" id="rating_search5">
							<label class="exercises-filter__item-label" for="rating_search5">от 1</label>
						</div>
						<div class="exercises-filter__item">
							<input class="exercises-filter__item-input" type="radio" name="rating_search" id="rating_search6">
							<label class="exercises-filter__item-label" for="rating_search6">любой</label>
						</div>
					</div>
				</section>
				<!-- Buttons search and clear -->
				<button class="exercises-filter__search-button" type="button" class="clear">Искать</button>
				<button class="exercises-filter__clear-button" type="button" class="clear">Очистить</button>
			</section>
			<!-- Exercises array -->
			<?php
			if ($my){ // Checking the value of $my
				if (count($user_data->get_my_exercises($conn)) > 0){ // Displaying user's exercises in a form
					echo "<form method='post' class='exercise-block'>";
					foreach ($user_data->get_my_exercises($conn) as $exercise_id){
                        if (!in_workout($_SESSION["c_workout"], $exercise_id)) { // getting and printing user's exercise details
                            $exercise = new Exercise($conn, $exercise_id);
                            $is_featured = $exercise->is_featured($user_data, $conn);
                            $exercise->print_it($conn, $is_featured, 1, 1);
                        }
					}
					echo "</form>";
				}else{ // If the user has no personal exercises, prompt them to add exercises from the common list
					echo "<h1 class='exercises__none'>У Вас нет своих упражнений. Вы можете добавить их на вкладке 'Упражнения'.</h1>";
				}
			}else{ // print all list of exercises
				$select_sql = "SELECT id FROM exercises";
				if ($select_result = $conn->query($select_sql)){
                    echo "<form method='post' class='exercise-block'>";
                    foreach ($select_result as $item) {
                        if (!in_workout($_SESSION["workout"], $item["id"])) {
                            $exercise = new Exercise($conn, $item['id']);
                            $is_featured = $exercise->is_featured($user_data, $conn);
                            $is_mine = $exercise->is_mine($user_data, $conn);
                            $exercise->print_it($conn, $is_featured, $is_mine, 1);
                        }
                    }
					echo "</form>";
				}else{
					echo $conn->error; // Displaying an error if the query fails
				}
			}
			?>
        </div>

		<section class="popup-exercise">
			<section class="popup-exercise__content">
				<button class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<section class="exercise-item">
					
				</section>
				<form method="post" class="popup-exercise__info">
					<div class="popup-exercise__info-item">
						<label class="popup-exercise__info-label" for="c_exercise_circles">Количество подходов: </label>
						<input class="popup-exercise__info-input" type="number" id="c_exercise_circles" name="approaches">
					</div>
					<div>
						<label class="popup-exercise__info-label" for="c_exercise_reps">Количество повторений: </label>
						<input class="popup-exercise__info-input" type="number" id="c_exercise_reps" name="reps">
					</div>
                    <input class="exercise_id" name="id" type="hidden" value="">
					<button type="button" class="button-text popup-exercise__add-button"><p>Добавить в тренировку</p> <img src="../../assets/img/add.svg" alt=""></button>
				</form>
			</section>
		</section>
	</main>

	<?php include BASE_PATH . "/templates/footer.html"; // footer template ?>

	<script>
		let exerciseAddButton = document.querySelector('.popup-exercise__add-button');
		let popupExerciseInputs = document.querySelectorAll('.popup-exercise__info-input');

		for(let i = 0; i < popupExerciseInputs.length; i++){
			
			popupExerciseInputs[i].addEventListener('input', function(){
				if(popupExerciseInputs[0].value.length == 0){
					exerciseAddButton.type = 'button';
				}
				if(popupExerciseInputs[1].value.length == 0){
					exerciseAddButton.type = 'button';
				}
				else{
					exerciseAddButton.type = 'submit';
				}

				if (this.value.length > 5) {
					this.value = this.value.slice(0, 5);
				}
			});
		}


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


		// Filter variables
		let FilterButtonsArr = document.querySelectorAll('.exercises-filter__button');
		let FilterBlocksArr = document.querySelectorAll('.exercises-filter__content');
		let FilterButtonsArrowArr = document.querySelectorAll('.exercises-filter__button img');


		// open filter's blocks logic
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

		// open muscle group filter
		FilterButtonsArr[0].click();


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


		// variables for popup windows
		let exercisesButtons = document.querySelectorAll('.exercise-item__add');
		let popupExerciseItem = document.querySelector('.popup-exercise .exercise-item');
		let popupExerciseWindow = document.querySelector('.popup-exercise');
		let inputExerciseId = document.querySelector('.popup-exercise .exercise_id');

		let popupExerciseItemInfo = document.querySelector('.popup-exercise .exercise-item__info-btn');
		let popupExerciseItemClose = document.querySelector('.popup-exercise__close-button');
		let popupExerciseItemContent = document.querySelector('.popup-exercise .exercise-item__info-content');

		// open popup exercise logic
		for(let i = 0; i < exercisesButtons.length; i++){
			exercisesButtons[i].addEventListener('click', function(){
				let item = exercisesButtons[i].parentElement.parentElement;
				popupExerciseItem.innerHTML = '';
				popupExerciseItem.innerHTML = item.innerHTML;
				inputExerciseId.value = exercisesButtons[i].value;
				popupExerciseItem.removeChild(popupExerciseItem.lastElementChild);

				popupExerciseWindow.classList.add("open");

				popupExerciseItemInfo = document.querySelector('.popup-exercise .exercise-item__info-btn');
				popupExerciseItemClose = document.querySelector('.popup-exercise .exercise-item__info-close');
				popupExerciseItemContent = document.querySelector('.popup-exercise .exercise-item__info-content');
			
				popupExerciseItemInfo.addEventListener('click', function(){
					popupExerciseItemContent.style.cssText = `top: -1%;`;
				});
				popupExerciseItemClose.addEventListener('click', function(){
					popupExerciseItemContent.style.cssText = `top: -101%;`;
				});
			});
		}

		
		
		// close buttons for popup windows
		const closeBtn = document.querySelector('.popup-exercise__close-button');
		closeBtn.addEventListener('click', function(){
			popupExerciseWindow.classList.remove("open");
		});

		window.addEventListener('keydown', (e) => {
		if(e.key == "Escape"){
			popupExerciseWindow.classList.remove("open");
		}
		});

		document.querySelector('.popup-exercise__content').addEventListener('click', event => {
			event.isClickWithInModal = true;
		});


		// Main filter of exercises
		let MainFilter = document.querySelector('.exercises-nav__select');
		let ExercisesArray = document.querySelectorAll('.exercise-item--list');
		let FavoritesExercises = document.querySelectorAll('.exercise-item__favorite--selected');
		let ExercisesRating = document.querySelectorAll('.exercise-item__score');

		let ExercisesRatingSorted = []
		let ExercisesDifficultSorted = []

		for(let i = ExercisesRating.length - 1; i >= 0; i--){
			let rate = parseFloat(ExercisesRating[i].innerHTML.split(' '));
			let item = {
				number: i,
				rate: rate
			};
			ExercisesRatingSorted.push(item);
		}

		for(let i = difficultCountArr.length - 1; i >= 0; i--){
			let difficult = parseInt(difficultCountArr[i].innerHTML.split(' '));
			let item = {
				number: i,
				difficult: difficult
			};
			ExercisesDifficultSorted.push(item);
		}

		// sort rarings and difficult of exercices
		ExercisesRatingSorted.sort((a, b) => a.rate > b.rate ? 1 : -1);
		ExercisesDifficultSorted.sort((a, b) => a.difficult > b.difficult ? 1 : -1);
		
		let exerciseBlock = document.querySelector('.exercise-block');


		// side panel filter
		let searchButton = document.querySelector('.exercises-filter__search-button');
		let clearButton = document.querySelector('.exercises-filter__clear-button');
		
		let MuscleGroupInputs = document.querySelectorAll('.exercises-filter__item-input[name="muscle_groups_search"]');
		let DifficultInputs = document.querySelectorAll('.exercises-filter__item-input[name="difficult_search"]');
		let RatingInputs = document.querySelectorAll('.exercises-filter__item-input[name="rating_search"]');

		let MuscleGroupLabels = document.querySelectorAll('.exercises-filter__muscle-groups .exercises-filter__item-label');
		let DifficultLabels = document.querySelectorAll('.exercises-filter__difficult .exercises-filter__item-label');
		let RatingLabels = document.querySelectorAll('.exercises-filter__rating .exercises-filter__item-label');

		RatingInputs[RatingInputs.length - 1].checked = true;

		let ExercisesMuscleGroups = document.querySelectorAll('.exercise-item__muscle-groups');


		// main filter logic
		MainFilter.addEventListener('change', function(){
			clearButton.click();

			ExercisesArray.forEach(function(elem){
				elem.classList.remove('hide');
			});

			if(exerciseBlock){
				exerciseBlock.innerHTML = '';
			}
			for(let i = 0; i < ExercisesArray.length; i++){
				if(exerciseBlock){
					exerciseBlock.appendChild(ExercisesArray[i]);
				}
			}

			if(MainFilter.value == 'default'){
				ExercisesArray.forEach(function(elem){
					elem.classList.remove('hide');
				});

				if(exerciseBlock){
					exerciseBlock.innerHTML = '';
				}
				for(let i = 0; i < ExercisesArray.length; i++){
					if(exerciseBlock){
						exerciseBlock.appendChild(ExercisesArray[i]);
					}
				}

			}

			if(MainFilter.value == 'favorites'){
				ExercisesArray.forEach(function(elem){
					elem.classList.add('hide');
				});

				for(let i = 0; i < FavoritesExercises.length; i++){
					let cur_exercise = FavoritesExercises[i].parentNode;
					cur_exercise = cur_exercise.parentNode;
					cur_exercise.classList.remove('hide');
				}
			}
			if(MainFilter.value == 'low-rating'){
				for(let i = 0; i < ExercisesRatingSorted.length; i++){
					if(ExercisesArray[ExercisesRatingSorted[i].number].innerHTML.split(' ') != ''){
						if(exerciseBlock){
							exerciseBlock.appendChild(ExercisesArray[ExercisesRatingSorted[i].number]);
						}
					}
				}
			}
			if(MainFilter.value == 'high-rating'){
				for(let i = ExercisesRatingSorted.length - 1; i >= 0; i--){
					if(ExercisesArray[ExercisesRatingSorted[i].number].innerHTML.split(' ') != ''){
						if(exerciseBlock){
							exerciseBlock.appendChild(ExercisesArray[ExercisesRatingSorted[i].number]);
						}
					}
				}
			}
			if(MainFilter.value == 'low-difficult'){
				for(let i = 0; i < ExercisesDifficultSorted.length; i++){
					if(ExercisesArray[ExercisesDifficultSorted[i].number].innerHTML.split(' ') != ''){
						if(exerciseBlock){
							exerciseBlock.appendChild(ExercisesArray[ExercisesDifficultSorted[i].number]);
						}
					}
				}
			}
			if(MainFilter.value == 'high-difficult'){
				for(let i = ExercisesDifficultSorted.length - 1; i >= 0; i--){
					if(ExercisesArray[ExercisesDifficultSorted[i].number].innerHTML.split(' ') != ''){
						if(exerciseBlock){
							exerciseBlock.appendChild(ExercisesArray[ExercisesDifficultSorted[i].number]);
						}
					}
				}
			}
		});


		// side panel filter(logic)
		searchButton.addEventListener('click', function(){
			MainFilter.value = 'default';

			if(exerciseBlock){
				exerciseBlock.innerHTML = '';
			}
			for(let i = 0; i < ExercisesArray.length; i++){
				let exerciseCheckMuscles = false;
				let checkCount = 0;
				for(let j = 0; j < MuscleGroupInputs.length; j++){
					if(MuscleGroupInputs[j].checked == true && MuscleGroupLabels[j].innerHTML == ExercisesMuscleGroups[i].innerHTML){
						exerciseCheckMuscles = true;
					}
					else if(MuscleGroupInputs[j].checked == true){
						checkCount += 1;
					}
				}

				if(checkCount == 0){
					exerciseCheckMuscles = true;
				}


				let exerciseCheckDifficult = false;
				checkCount = 0;

				for(let j = 0; j < DifficultInputs.length; j++){
					if(DifficultInputs[j].checked == true && DifficultLabels[j].innerHTML == difficultCountArr[i].innerHTML){
						exerciseCheckDifficult = true;
					}
					else if(DifficultInputs[j].checked == true){
						checkCount += 1;
					}
				}

				if(checkCount == 0){
					exerciseCheckDifficult = true;
				}


				let exerciseCheckRating = false;
				checkCount = 0;

				for(let j = 0; j < RatingInputs.length; j++){
					if(RatingInputs[j].checked == true && parseFloat(RatingLabels[j].innerHTML.split(' ')[1]) <= parseFloat(ExercisesRating[i].innerHTML.split(' '))){
						exerciseCheckRating = true;
					}
					else if(RatingInputs[j].checked == true){
						checkCount += 1;
					}
				}

				if(checkCount == 0 || RatingInputs[5].checked == true){
					exerciseCheckRating = true;
				}

				if(exerciseCheckMuscles && exerciseCheckDifficult && exerciseCheckRating && exerciseBlock){
					exerciseBlock.appendChild(ExercisesArray[i]);
				}
			}
		});


		// save input values
		let allFilterInputs = document.querySelectorAll('.exercises-filter__item-input');
		let allFilterInputsChecked = [];

		// clear filters
		clearButton.addEventListener('click', function(){
			for(let i = 0; i < MuscleGroupInputs.length; i++){
				MuscleGroupInputs[i].checked = false;
			}

			for(let i = 0; i < DifficultInputs.length; i++){
				DifficultInputs[i].checked = false;
			}

			for(let i = 0; i < RatingInputs.length; i++){
				RatingInputs[i].checked = false;
			}

			RatingInputs[RatingInputs.length - 1].checked = true;
			if(exerciseBlock){
				exerciseBlock.innerHTML = '';
				for(let i = 0; i < ExercisesArray.length; i++){
					exerciseBlock.appendChild(ExercisesArray[i]);
				}
			}
			

			for(let i = 0; i < allFilterInputsChecked.length; i++){
				allFilterInputsChecked[i] = false;
			}

			allFilterInputsChecked[allFilterInputsChecked.length - 1] = true;

			localStorage.setItem('c_allFilterInputsChecked', allFilterInputsChecked);
		});


		// get data from localstorage
		if(localStorage.getItem('c_allFilterInputsChecked')){
			allFilterInputsChecked = localStorage.getItem('c_allFilterInputsChecked').split(',');
			for(let i = 0; i < allFilterInputsChecked.length; i++){
				if(allFilterInputsChecked[i] == 'true'){
					allFilterInputsChecked[i] = true;
				}
				else{
					allFilterInputsChecked[i] = false;
				}
			}
			
			for(let i = 0; i < allFilterInputs.length; i++){
				allFilterInputs[i].checked = allFilterInputsChecked[i];
			}

			searchButton.click();
		}
		else{
			allFilterInputsChecked = [false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, true];
			localStorage.setItem('c_allFilterInputsChecked', allFilterInputsChecked);
		}

		for(let i = 0; i < allFilterInputs.length; i++){
			allFilterInputs[i].addEventListener('change', function(){
				for(let i = 0; i < allFilterInputsChecked.length; i++){
					allFilterInputsChecked[i] = allFilterInputs[i].checked;
				}
				localStorage.setItem('c_allFilterInputsChecked', allFilterInputsChecked);
			});
		}

		// Exercises search
		const search_input = document.querySelector('.exercises-nav__search-input');
		search_input.addEventListener('input', function(){
			SearchItems(search_input.value);
			searchButton.click();
		});

		let ExerciseNames = document.querySelectorAll('.exercise-item__title');

		// search logic
		function SearchItems(val){
			val = val.trim().replaceAll(' ', '').toUpperCase(); // get value of search's input
			if(val != ''){ // if value not none
				ExerciseNames.forEach(function(elem){
					if(elem.innerText.trim().replaceAll(' ', '').toUpperCase().search(val) == -1){
						let cur_exercise = elem.parentNode;
						if(cur_exercise){
							cur_exercise.classList.add('hide');
						}
					}
					else{ // if name matches print block
						let cur_exercise = elem.parentNode;
						if(cur_exercise){
							cur_exercise.classList.remove('hide');
						}
					}
				});
			}

			else{ // if value none print all cards
				ExerciseNames.forEach(function(elem){
					let cur_exercise = elem.parentNode;
					if(cur_exercise){
						cur_exercise.classList.remove('hide');
					}
				});
			}
		}
	</script>
</body>
</html>