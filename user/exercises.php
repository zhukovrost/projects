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
		<a href="">Мои</a>
		<select name="exercise_sort" id="">
			<option value="value1" selected>По умолчанию</option>
			<option value="value2" selected>Избранные</option>
			<option value="value3">Рейтинг(возрастание)</option>
			<option value="value4">Рейтинг(убывание)</option>
			<option value="value5">Сложность(возрастание)</option>
			<option value="value6">Сложность(убывание)</option>
		</select>
		<div>
			<input type="text" placeholder="Искать">
			<img src="../img/search_black.svg" alt="">
		</div>
	</nav>

	<main>
		<div class="container">
			<section class="search_block">
				<form class="muscle_groups">
					<button type="button">Группы мышц <img src="../img/search_arrow.svg" alt=""></button>
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
				</form>
				<form class="difficult">
					<button type="button">Сложность <img src="../img/search_arrow.svg" alt=""></button>
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
					<div>
						<input type="checkbox" name="difficult_search" id="difficult_search6">
						<label for="difficult_search6">любой</label>
					</div>
				</form>
				<form class="rating">
					<button type="button">Рейтинг <img src="../img/search_arrow.svg" alt=""></button>
					<div>
						<input type="checkbox" name="rating_search" id="rating_search1">
						<label for="rating_search1">5</label>
					</div>
					<div>
						<input type="checkbox" name="difficult_search" id="rating_search2">
						<label for="rating_search2">4</label>
					</div>
					<div>
						<input type="checkbox" name="difficult_search" id="rating_search3">
						<label for="rating_search3">3</label>
					</div>
					<div>
						<input type="checkbox" name="difficult_search" id="rating_search4">
						<label for="rating_search4">2</label>
					</div>
					<div>
						<input type="checkbox" name="difficult_search" id="rating_search5">
						<label for="rating_search6">1</label>
					</div>
					<div>
						<input type="checkbox" name="difficult_search" id="rating_search6">
						<label for="rating_search6">любой</label>
					</div>
				</form>
				<button>Очистить</button>
			</section>
			<section class="exercise_block">
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="../img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
					<div class="statistic">
						<div class="rating">
							<p>4,5</p>
							<img src="img/Star.svg" alt="">
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
						<button class="add">Добавить <img src="img/add.svg" alt=""></button>
						<button class="favorite"><img src="img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
					<div class="statistic">
						<div class="rating">
							<p>4,5</p>
							<img src="img/Star.svg" alt="">
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
						<button class="add">Добавить <img src="img/add.svg" alt=""></button>
						<button class="favorite"><img src="img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
					<div class="statistic">
						<div class="rating">
							<p>4,5</p>
							<img src="img/Star.svg" alt="">
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
						<button class="add">Добавить <img src="img/add.svg" alt=""></button>
						<button class="favorite"><img src="img/favorite.svg" alt=""></button>
					</div>
				</section>
				<section class="exercise_item">
					<!-- Exercise info -->
					<button class="info"><img src="img/info.svg" alt=""></button>
					<div class="info_block">
						<button class="info_close"><img src="img/close.svg" alt=""></button>
						<p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
					</div>
					<!-- Exercise muscle groups -->
					<div class="muscle_groups">Руки - плечи - грудь</div>
					<!-- Exercise image -->
					<img class="exercise_img" src="img/exercises/arms/triceps_2.jpg" alt="">
					<!-- Decoration line -->
					<div class="line"></div>
					<!-- Exercise title -->
					<h1>Алмазные отжимания</h1>
					<div class="statistic">
						<div class="rating">
							<p>4,5</p>
							<img src="img/Star.svg" alt="">
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
						<button class="add">Добавить <img src="img/add.svg" alt=""></button>
						<button class="favorite"><img src="img/favorite.svg" alt=""></button>
					</div>
				</section>
			</section>
        </div>
	</main>

	<?php include "../templates/footer.html" ?>
</body>
</html>