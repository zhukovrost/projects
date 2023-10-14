<?php
include "../templates/func.php";
include "../templates/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>

	<nav class="users-search-nav">
		<div class="container">
			<!-- Buttons to (sub / unsub) users -->

			<a class="button-text users-search-nav__item" href="c_exercises.php?my=1">Подписчики <img src="../img/arrow_white.svg" alt=""></a>
			<!-- Exercise search -->
			<div class="users-search-nav__search">
				<input class="users-search-nav__search-input" type="text" placeholder="Искать">
				<button class="users-search-nav__search-button"><img src="../img/search_black.svg" alt=""></button>
			</div>
		</div>
	</nav>

	<main class="users-list">
		<div class="container">
			<section class="user-card">
				<img class="user-card__image" src="../img/man_avatar.svg" alt="">
				<p class="user-card__name">Иван Иванов</p>
				<button class="button-text user-card__button user-card__button--add"><p>Добавить в друзья</p><img src="../img/add.svg" alt=""></button>
				<!-- <a class="button-text user-card__button"><p>Программа</p><img src="../img/my_programm.svg" alt=""></a> -->
			</section>
			<section class="user-card">
				<img class="user-card__image" src="../img/man_avatar.svg" alt="">
				<p class="user-card__name">Иван Иванов</p>
				<button class="button-text user-card__button user-card__button--add"><p>Добавить в друзья</p><img src="../img/add.svg" alt=""></button>
			</section>
			<section class="user-card">
				<img class="user-card__image" src="../img/man_avatar.svg" alt="">
				<p class="user-card__name">Иван Иванов</p>
				<button class="button-text user-card__button user-card__button--add"><p>Добавить в друзья</p><img src="../img/add.svg" alt=""></button>
			</section>
			<section class="user-card">
				<img class="user-card__image" src="../img/man_avatar.svg" alt="">
				<p class="user-card__name">Иван Иванов</p>
				<button class="button-text user-card__button user-card__button--add"><p>Добавить в друзья</p><img src="../img/add.svg" alt=""></button>
			</section>
			<section class="user-card">
				<img class="user-card__image" src="../img/man_avatar.svg" alt="">
				<p class="user-card__name">Иван Иванов</p>
				<button class="button-text user-card__button user-card__button--add"><p>Добавить в друзья</p><img src="../img/add.svg" alt=""></button>
			</section>
			<section class="user-card">
				<img class="user-card__image" src="../img/man_avatar.svg" alt="">
				<p class="user-card__name">Иван Иванов</p>
				<button class="button-text user-card__button user-card__button--add"><p>Добавить в друзья</p><img src="../img/add.svg" alt=""></button>
			</section>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>