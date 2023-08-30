<?php
include "../templates/func.php";
include "../templates/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html" ?>

	<main>
		<div class="container user_block">
			<section class="info">
				<section class="about">
					<div class="avatar_block">
						<img src="" alt="">
						<h1>Иван Иванов</h1>
						<p>Тренировался <span>1</span> день назад</p>
					</div>
					<div class="content">
						<p>Занимаюсь спортом, жму 40. Буду рад знакомству!</p>
						<div>
							<img src="../img/edit_gray.svg" alt="">
							<button>Изменить</button>
						</div>
						
					</div>
				</section>
				<section class="news">
					<button>Новая запись</button>
					<section class="item">
						<div class="info">
							<img src="" alt="">
							<div>
								<p>Иван иванов</p>
								<p>12.12.1212</p>
							</div>
							<img src="" alt="">
						</div>
						<div class="content">

						</div>
					</section>
				</section>
			</section>
			<section class="other">
				<section class="friends">
						<!-- Title and button to search friends -->
						<div class="title">
							<h1>Тренировки друзей</h1>
							<a href=""><img src="../img/search.svg" alt=""></a>
						</div>
						<!-- Friends' workout swiper -->
						<swiper-container class="content swiper_friends" navigation="true">
							<!-- slide -->
							<swiper-slide>
								<!-- friend item -->
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
							</swiper-slide>
							<swiper-slide>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
								<a href="" class="item">
									<img src="../img/man_avatar.svg" alt="">
									<p>Иван Иванов</p>
								</a>
							</swiper-slide>
						</swiper-container>
					</section>
				</section>
			</section>
			<section class="staff">
				<div class="coach">
					<p>Тренер: <span>Штангов К.Г.</span></p>
					<img src="../img/message.svg" alt="">
					<img src="../img/profile.svg" alt="">
					<img src="../img/delete.svg" alt="">
				</div>
				<div class="users_count">
					<a href=""><span>25 подписчиков</span></a>
					<a href=""><span>15 подписок</span></a>
				</div>
			</section>
			<section class="buttons">
				<button>Редактировать профиль <img src="../img/edit.svg" alt=""></button>
				<button>Поиск спортсменов <img src="../img/search.svg" alt=""></button>
				<button>Выйти <img src="../img/logout.svg" alt=""></button>
			</section>
		</div>
	</main>

	<?php include "../templates/footer.html" ?>
</body>
</html>