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
					<h2 class="staff-block__subtitle">Близжайшая тренировка</h2>
					<div class="staff-block__nearest-workout-content">
						<div class="staff-block__nearest-workout-date">12.12.2023</div>
						<a href="" class="staff-block__button-more"><p>Подробнее</p> <img src="../img/more_white.svg" alt=""></a>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Цели</h2>
					<ul class="staff-block__goals-list">
						<li class="staff-block__goals-item">
							<div class="staff-block__goals-item-cover">
								<div class="staff-block__goal-name">
									<p>Атжуания 20.5 раз</p> <img src="../img/green_check_mark.svg" alt="">
								</div>
								<div class="staff-block__goal-buttons">
									<button class="staff-block__goal-button staff-block__goal-button--text">Не выполненна</button>
									<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
									<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
								</div>
							</div>
						</li>
						<li class="staff-block__goals-item">
							<div class="staff-block__goals-item-cover">
								<div class="staff-block__goal-name">
									<p>Атжуания 20.5 раз</p> <img src="../img/blue_question_mark.svg" alt="">
								</div>
								<div class="staff-block__goal-buttons">
									<button class="staff-block__goal-button staff-block__goal-button--text">Выполненна</button>
									<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
									<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
								</div>
							</div>
						</li>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Контрольная тренировка</h2>
					<div class="staff-block__control-workout-nearest">
						<div class="staff-block__control-workout-info">
							<p class="staff-block__control-workout-text">Близжайшая:</p>
							<div class="staff-block__control-workouts-date">12.12.2023</div>
						</div>
						<a href="" class="staff-block__button-more"><p>Подробнее</p> <img src="../img/more_white.svg" alt=""></a>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Турниры и соревнования</h2>
					<div class="staff-block__competitions">
						<div class="staff-block__competition-item">
							<p class="staff-block__competition-text">Игра с балбесами</p>
							<div class="staff-block__item-buttons">
								<a class="staff-block__link-button" href="" download><img src="../img/file.svg" alt=""></a>
								<a class="staff-block__link-button" href=""><img src="../img/link.svg" alt=""></a>
								<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
								<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
							</div>
						</div>
						<div class="staff-block__competition-item">
							<p class="staff-block__competition-text">Игра с балбесами</p>
							<div class="staff-block__item-buttons">
								<a class="staff-block__link-button" href="" download><img src="../img/file.svg" alt=""></a>
								<a class="staff-block__link-button" href=""><img src="../img/link.svg" alt=""></a>
								<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
								<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
							</div>
						</div>
						<button class="button-text staff-block__item-button--add"><p>Добавить</p><img src="../img/add.svg" alt=""></button>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Полезные ссылки</h2>
					<div class="staff-block__useful-links">
						<div class="staff-block__useful-links-item">
							<p class="staff-block__useful-links-text">Атжумания</p>
							<div class="staff-block__item-buttons">
								<a class="staff-block__link-button" href="" download><img src="../img/file.svg" alt=""></a>
								<a class="staff-block__link-button" href=""><img src="../img/link.svg" alt=""></a>
								<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
								<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
							</div>
						</div>
						<div class="staff-block__useful-links-item">
							<p class="staff-block__useful-links-text">Атжумания</p>
							<div class="staff-block__item-buttons">
								<a class="staff-block__link-button" href="" download><img src="../img/file.svg" alt=""></a>
								<a class="staff-block__link-button" href=""><img src="../img/link.svg" alt=""></a>
								<button class="button-img staff-block__item-button"><img src="../img/edit.svg" alt=""></button>
								<button class="button-img staff-block__item-button"><img src="../img/delete.svg" alt=""></button>
							</div>
						</div>
						<button class="button-text staff-block__item-button--add"><p>Добавить</p><img src="../img/add.svg" alt=""></button>
					</div>
				</section>
			</section>
			<section class="coach-other">
				<section class="last-trainings last-trainings--coach">
					<h1 class="last-trainings__title">Последние тренировки</h1>
					<div class="last-trainings__content">
						<!-- Item -->
						<section class="last-trainings__card">
							<!-- Left part of last exercise item -->
							<div class="last-trainings__card-left">
								<!-- Time of training -->
								<div class="last-trainings__item">
								<img class="last-trainings__item-img" src="../img/time.svg" alt="">
								<p class="last-trainings__item-text"><span>12</span> мин</p>
								</div>
								<!-- Exercise count of training -->
								<div class="last-trainings__item">
								<img class="last-trainings__item-img" src="../img/cards.svg" alt="">
								<p class="last-trainings__item-text"><span>12</span> упражнений</p>
								</div>
							</div>
							<!-- Right part of last exercise item -->
							<div class="last-trainings__card-right">
								<!-- Muscle groups count of training -->
								<div class="last-trainings__item">
								<img class="last-trainings__item-img" src="../img/cards.svg" alt="">
								<p class="last-trainings__item-text"><span>12</span> группы мышц</p>
								</div>
								<!-- Button 'Подробнее' for more info about exercise -->
								<div class="last-trainings__item">
								<a class="button-text last-trainings__item-button" href="{{ link }}">Подробнее <img src="../img/other.svg" alt=""></a>
								</div>
							</div>
						</section>
						<!-- Item -->
						<section class="last-trainings__card">
							<!-- Left part of last exercise item -->
							<div class="last-trainings__card-left">
								<!-- Time of training -->
								<div class="last-trainings__item">
								<img class="last-trainings__item-img" src="../img/time.svg" alt="">
								<p class="last-trainings__item-text"><span>12</span> мин</p>
								</div>
								<!-- Exercise count of training -->
								<div class="last-trainings__item">
								<img class="last-trainings__item-img" src="../img/cards.svg" alt="">
								<p class="last-trainings__item-text"><span>12</span> упражнений</p>
								</div>
							</div>
							<!-- Right part of last exercise item -->
							<div class="last-trainings__card-right">
								<!-- Muscle groups count of training -->
								<div class="last-trainings__item">
								<img class="last-trainings__item-img" src="../img/cards.svg" alt="">
								<p class="last-trainings__item-text"><span>12</span> группы мышц</p>
								</div>
								<!-- Button 'Подробнее' for more info about exercise -->
								<div class="last-trainings__item">
								<a class="button-text last-trainings__item-button" href="{{ link }}">Подробнее <img src="../img/other.svg" alt=""></a>
								</div>
							</div>
						</section>
					</div>
				</section>
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
				<button class="button-text staff-other__button"><p>Программа</p> <img src="../img/my_programm.svg" alt=""></button>
				<button class="button-text staff-other__button"><p>Сравнить спортсменов</p> <img src="../img/my_programm.svg" alt=""></button>
				<button class="button-text staff-other__button"><p>Группы</p> <img src="../img/my_programm.svg" alt=""></button>
			</section>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>