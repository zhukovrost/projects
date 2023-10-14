<?php
include "../templates/func.php";
include "../templates/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>

	<main class="staff-cover">
		<div class="container">
			<section class="staff-block">
				<p class="staff-block__title">Спортсмен</p>
				<section class="staff-block__header">
					<img class="staff-block__avatar" src="../img/man_avatar.svg" alt="">
					<section class="staff-block__info">
						<div class="staff-block__name">
							<h1 class="staff-block__name-text">Иван Иванов</h1>
							<a class="staff-block__profile-link" href=""><img src="../img/profile_black.svg" alt=""></a>
						</div>
						<div class="staff-block__buttons">
							<a href="" class="staff-block__button staff-block__button--img"><img src="../img/vk.svg" alt=""></a>
							<a href="../img/tg.svg" class="staff-block__button staff-block__button--img"><img src="../img/tg.svg" alt=""></a>
							<button class="button-text staff-block__button staff-block__button--delite"><p>Удалить</p> <img src="../img/delete.svg" alt=""></button>
						</div>
					</section>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Курс лечения</h2>
					<div class="staff-block__medicines">
						<div class="staff-block__medicine-item">
							<p class="staff-block__medicine-name">Мазь</p>
							<div class="staff-block__medicine-dose">2 раза в день</div>
							<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
							<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
						</div>
						<div class="staff-block__medicine-item">
							<p class="staff-block__medicine-name">Мазь</p>
							<div class="staff-block__medicine-dose">2 раза в день</div>
							<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
							<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
						</div>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Переиод лечения</h2>
					<div class="staff-block__treatment-date">
						<div class="staff-block__treatment-date-item">
							12.02.2023
						</div>
						<div class="staff-block__treatment-date-line"></div>
						<div class="staff-block__treatment-date-item">
							12.03.2023
						</div>
					</div>
					<div class="staff-block__treatment-buttons">
						<button class="button-img staff-block__item-button staff-block__item-button--date"><img src="../img/edit.svg" alt=""></button>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Рекомендации по лечению</h2>
					<div class="staff-block__treatment-recommendation">
						Избегать физических нагрузок, побольше кайфа и чайку оформить. АААААААААААААААААААААА
					</div>
					<div class="staff-block__treatment-buttons">
						<button class="button-img staff-block__item-button staff-block__item-button--date"><img src="../img/edit.svg" alt=""></button>
					</div>
				</section>
			</section>
			<section class="staff-other">
				<section class="friends-block">
                    <!-- Title and button to search friends -->
                    <div class="friends-block__header">
                        <h1 class="friends-block__header-title">Другие спортсмены</h1>
                        <a class="friends-block__header-button" href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends' workout swiper -->
                   <section class="friends-block__cover" navigation="true">
						<a href="../user/profile.php?user={{ id }}" class="friends-block__item">
							<img class="friends-block__avatar" src="../img/man_avatar.svg" alt="">
							<p class="friends-block__name">Иван Иванов</p>
						</a>
						<a href="../user/profile.php?user={{ id }}" class="friends-block__item">
							<img class="friends-block__avatar" src="../img/man_avatar.svg" alt="">
							<p class="friends-block__name">Иван Иванов</p>
						</a>
						<a href="../user/profile.php?user={{ id }}" class="friends-block__item">
							<img class="friends-block__avatar" src="../img/man_avatar.svg" alt="">
							<p class="friends-block__name">Иван Иванов</p>
						</a>
						<a href="../user/profile.php?user={{ id }}" class="friends-block__item">
							<img class="friends-block__avatar" src="../img/man_avatar.svg" alt="">
							<p class="friends-block__name">Иван Иванов</p>
						</a>
					</section>
			</section>
			<section class="staff-other__buttons">
				<button class="button-text staff-other__button"><p>Группы</p> <img src="../img/my_programm.svg" alt=""></button>
			</section>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>