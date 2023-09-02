<?php
include "../templates/func.php";
include "../templates/settings.php";

$user = $user_data;
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html"; ?>

	<main>
		<div class="container user_block">
            <!-- Info about user -->
			<section class="info">
				<section class="about">
                    <!-- User avatar, name and surname -->
					<div class="avatar_block">
						<img src="<?php echo $user->get_avatar($conn); ?>" alt="">
						<h1><?php echo $user->name." ",$user->surname; ?></h1>
                        <?php if ($user->online == 0){ ?>
						    <p>В поисках тренировок...</p>
                        <?php } else { ?>
                            <p><span><?php echo round((time() - $user->online) / 24, 0); ?></span> день назад</p>
                        <?php } ?>
					</div>
                    <!-- User info text -->
					<div class="content">
						<p><?php echo $user->description; ?></p>
						<button><img src="../img/edit_gray.svg" alt="">Изменить</button>
					</div>
				</section>
                <!-- User's news -->
				<section class="news">
                    <!-- Button 'Новая запись' -->
					<button class="new_post_btn">Новая запись <img src="../img/add.svg" alt=""></button>
					<!-- News item -->
                    <?php
                    if ($news = $user->get_news($conn)) {
                        foreach ($news as $new){
                            $date = date("d.m.Y", $new['date']);
                            $replacements = array(
                                "{{ user_name }}" => $new['name'].' '.$new['surname'],
                                "{{ link }}" => '',
                                "{{ date }}" => $date,
                                "{{ message }}" => $new['message'],
                                "{{ avatar }}" => $new['file']
                            );
                            echo render($replacements, "../templates/news_item.html");
                        }
                    }
                    ?>
				</section>
			</section>
			<section class="other">
				<section class="friends">
                    <!-- Title and button to search friends -->
                    <div class="title">
                        <h1>Друзья</h1>
                        <a href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends swiper -->
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
                <!-- User's staff (coach and doctor) -->
				<section class="staff">
                    <!-- Coach info and buttons to chat, ptofile and delete -->
					<div class="coach">
						<p>Тренер: <span>Штангов К.Г.</span></p>
						<button><img src="../img/message.svg" alt=""></button>
						<button><img src="../img/profile_black.svg" alt=""></button>
						<button><img src="../img/delete_black.svg" alt=""></button>
					</div>
                    <!-- Doctor info -->
					<div class="doctor">
						<p>Врач: <span>нет</span></p>
						<button><img src="../img/add_black.svg" alt=""></button>
					</div>
                    <!-- Count of subscribers and subscriptions -->
					<div class="users_count">
						<a href=""><span>25 подписчиков</span></a>
						<a href=""><span>15 подписок</span></a>
					</div>
				</section>
				<section class="last_trainings">
                    <!-- Title -->
                    <h1>Последние тренировки</h1>
                    <!-- Trainings content -->
                    <div class="content">
                        <!-- Item -->
                        <section class="item">
                            <!-- Left part of last exercise item -->
                            <div class="left">
                                <!-- Time of training -->
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <!-- Exercise count of training -->
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <!-- Right part of last exercise item -->
                            <div class="right">
                                <!-- Muscle groups count of training -->
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <!-- Button 'Подробнее' for more info about exercise -->
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
                <!-- Buttons to edit profile, search sportsmen and logout -->
				<section class="buttons">
					<button>Редактировать профиль <img src="../img/edit.svg" alt=""></button>
					<button>Поиск спортсменов <img src="../img/search_white.svg" alt=""></button>
					<a href="../clear.php" class="logout">Выйти <img src="../img/logout.svg" alt=""></a>
				</section>
			</section>
		</div>
	</main>

	<?php include "../templates/footer.html" ?>

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
</body>
</html>