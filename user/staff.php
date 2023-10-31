<?php
include "../templates/func.php";
include "../templates/settings.php";
if ($user_data->get_status() != "user" && $user_data->get_status() != "admin")
    header("Location: profile.php");

$user_data->set_staff($conn);
$has_coach = $user_data->coach != NULL;
$has_doctor = $user_data->doctor != NULL;
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>
	<main class="staff-cover">
		<div class="container">
            <?php if ($has_coach){ ?>
			<section class="staff-block">
				<p class="staff-block__title">Тренер</p>
				<section class="staff-block__header">
					<img class="staff-block__avatar" src="<?php echo $user_data->coach->get_avatar($conn); ?>" alt="">
					<section class="staff-block__info">
						<div class="staff-block__name">
							<h1 class="staff-block__name-text"><?php echo $user_data->coach->name." ".$user_data->coach->surname; ?></h1>
							<a class="staff-block__profile-link" href="profile.php?user=<?php echo $user_data->coach->get_id(); ?>"><img src="../img/profile_black.svg" alt=""></a>
						</div>
						<div class="staff-block__buttons">
                            <!-- <a href="" class="staff-block__button staff-block__button--img"><img src="../img/vk.svg" alt=""></a>
							<a href="../img/tg.svg" class="staff-block__button staff-block__button--img"><img src="../img/tg.svg" alt=""></a> -->
							<a href="delete_coach.php?id=<?php echo $user_data->coach->get_id(); ?>" class="button-text staff-block__button staff-block__button--delite"><p>Удалить</p> <img src="../img/delete.svg" alt=""></a>
						</div>
					</section>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Ближайшая тренировка</h2>
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
							<div class="staff-block__goals-item-cover"><p>Атжуания 20.5 раз</p> <img src="../img/green_check_mark.svg" alt=""></div>
						</li>
						<li class="staff-block__goals-item">
							<div class="staff-block__goals-item-cover"><p>Атжуания 20.5 раз</p> <img src="../img/blue_question_mark.svg" alt=""></div>
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
							</div>
						</div>
						<div class="staff-block__competition-item">
							<p class="staff-block__competition-text">Игра с балбесами</p>
							<div class="staff-block__item-buttons">
								<a class="staff-block__link-button" href="" download><img src="../img/file.svg" alt=""></a>
								<a class="staff-block__link-button" href=""><img src="../img/link.svg" alt=""></a>
							</div>
						</div>
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
							</div>
						</div>
						<div class="staff-block__useful-links-item">
							<p class="staff-block__useful-links-text">Атжумания</p>
							<div class=staff-block__item-buttons">
								<a class="staff-block__link-button" href="" download><img src="../img/file.svg" alt=""></a>
								<a class="staff-block__link-button" href=""><img src="../img/link.svg" alt=""></a>
							</div>
						</div>
					</div>
					
				</section>
			</section>
            <?php } else { ?>
				<section class="staff-block">
					<p class="staff-block__title-none">У вас нет тренера</p>
					<button class="button-text staff-block__button-add"><p>Добавить</p><img src="../img/add.svg" alt=""></button>
				</section>
            <?php }
            if ($has_doctor){ ?>
			<section class="staff-block">
				<p class="staff-block__title">Врач</p>
				<section class="staff-block__header">
					<img class="staff-block__avatar" src="<?php echo $user_data->doctor->get_avatar($conn); ?>" alt="">
					<section class="staff-block__info">
						<div class="staff-block__name">
							<h1 class="staff-block__name-text"><?php echo $user_data->doctor->name." ".$user_data->doctor->surname; ?></h1>
							<a class="staff-block__profile-link" href="profile.php?user=<?php echo $user_data->doctor->get_id(); ?>"><img src="../img/profile_black.svg" alt=""></a>
						</div>
						<div class="staff-block__buttons">
							<!--<a href="" class="staff-block__button staff-block__button--img"><img src="../img/vk.svg" alt=""></a>
							<a href="../img/tg.svg" class="staff-block__button staff-block__button--img"><img src="../img/tg.svg" alt=""></a>-->
							<a href="delete_doctor.php?id=<?php echo $user_data->doctor->get_id(); ?>" class="button-text staff-block__button staff-block__button--delite"><p>Удалить</p> <img src="../img/delete.svg" alt=""></a>
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
						</div>
						<div class="staff-block__medicine-item">
							<p class="staff-block__medicine-name">Мазь</p>
							<div class="staff-block__medicine-dose">2 раза в день</div>
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
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Рекомендации по лечению</h2>
					<div class="staff-block__treatment-recommendation">
						Избегать физических нагрузок, побольше кайфа и чайку оформить. АААААААААААААААААААААА
					</div>
				</section>
			</section>
            <?php } else { ?>
				<section class="staff-block">
					<p class="staff-block__title-none">У вас нет врача</p>
					<button class="button-text staff-block__button-add"><p>Добавить</p><img src="../img/add.svg" alt=""></button>
				</section>
            <?php } ?>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>