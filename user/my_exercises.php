<?php
include "../templates/func.php";
include "../templates/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html" ?>

	<nav class="exercise_nav">
		<div class="container">
			<a href="exercises.php">Все <img src="../img/arrow_exercise.svg" alt=""></a>
			<select name="exercise_sort" id="">
				<option value="value1" selected>По умолчанию</option>
				<option value="value2">Избранные</option>
				<option value="value3">Рейтинг(возрастание)</option>
				<option value="value4">Рейтинг(убывание)</option>
				<option value="value5">Сложность(возрастание)</option>
				<option value="value6">Сложность(убывание)</option>
			</select>
			<div class="search">
				<input type="text" placeholder="Искать">
				<button><img src="../img/search_black.svg" alt=""></button>
			</div>
		</div>
	</nav>

	<main>
		<div class="container exercises_cover">
			<form class="filter_block">
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
				<button type="submit" class="clear">Искать</button>
				<button type="button" class="clear">Очистить</button>
			</form>
			<section class="exercise_block">
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="../img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
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
							<div class="disabled"></div>
						</div>
					</div>
					<div class="buttons">
						<button class="add delete">Удалить <img src="../img/delete.svg" alt=""></button>
						<button class="favorite"><img src="../img/favorite.svg" alt=""></button>
					</div>
				</section>
			</section>
        </div>
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

		FilterButtonsArr[0].click();
	</script>
</body>
</html>