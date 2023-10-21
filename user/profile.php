<?php
include "../templates/func.php";
include "../templates/settings.php";
if ($_GET["user"]){
    $user = new User($conn, $_GET["user"]);
}else{
    $user = $user_data;
}
$user->set_subscriptions($conn);
$user->set_subscribers($conn);

# ---------------- avatar upload ------------------------

if(isset($_POST['image_to_php'])) {
    $user->update_avatar($conn, $_POST['image_to_php']);
}

?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html"; ?>

	<main class="user-block">
        <section class="preview_cover">
            <div class="preview_block">
                <img id="preview" src="#" alt="Preview"/>
            </div>
            <button id="saveAvatar">Сохранить</button>
        </section>

		<div class="container">
            <!-- Info about user -->
			<section class="user-block__info">
				<section class="user-about">
					<!-- User avatar, name and surname -->
                    <section class="user-block__avatar">
                        <form id="avatar_form" class="avatar" method="post">
                            <img id="profileImage" src="<?php echo $user->get_avatar($conn); ?>">
                            <input type="file" id="avatar_file" accept="image/*" />
                            <label for="avatar_file" class="uppload_button">Choose photo</label>
                            <input type="hidden" id="image_to_php" name="image_to_php" value="">
                        </form>
                        <div class="user-block__avatar-name">
                            <h1>Иван Иванов</h1>
                        </div>
                        <button class="user-block__avatar-more"><img src="../img/info.svg" alt=""><p>Подробнее</p></button>
                    </section>
                    
                    <!-- User info text -->
					<div class="user-about__description">
                        <?php if ($user->description){ ?>
						    <p class="user-about__description-text"><?php echo $user->description; ?></p>
                        <?php } else{ ?>
                            <p class="user-about__description-text">Нет описания</p>
                        <?php } ?>
                        <?php if ($user->get_auth()){ ?>
						    <button class="user-about__description-button"><img src="../img/edit_gray.svg" alt="">Изменить</button>
                        <?php } ?>
					</div>
				</section>
                <!-- User's news -->
				<section class="user-news">
                    <!-- Button 'Новая запись' -->
                    <!-- <?php if ($user->get_auth()){ ?>
					    <button class="button-text user-news__add">Новая запись <img src="../img/add.svg" alt=""></button>
                    <?php } ?> -->
					<!-- News item -->
                    <?php
                    if ($news = $user->get_my_news($conn)) {
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
                    }?>
				</section>
			</section>
			<section class="user-block__other">
				<section class="friends-block">
                    <!-- Title and button to search friends -->
                    <div class="friends-block__header">
                        <h1 class="friends-block__header-title">Подписки</h1>
                        <a class="friends-block__header-button" href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends swiper -->
                    <swiper-container class="friends-block__swiper" navigation="true">
                        <?php print_user_list($conn, $user->subscriptions); ?>
					</swiper-container>
                </section>
                <!-- User's staff (coach and doctor) -->
				<section class="user-block__staff">
                    <!-- Coach info and buttons to chat, ptofile and delete -->
					<div class="user-block__coach">
						<p class="user-block__staff-title">Тренер: <span>Штангов К.</span></p>
						<button class="user-block__staff-button"><img src="../img/message.svg" alt=""></button>
						<button class="user-block__staff-button"><img src="../img/profile_black.svg" alt=""></button>
						<button class="user-block__staff-button"><img src="../img/delete_black.svg" alt=""></button>
					</div>
                    <!-- Doctor info -->
					<div class="user-block__doctor">
						<p class="user-block__staff-title">Врач: <span>нет</span></p>
						<button class="user-block__staff-button"><img src="../img/add_black.svg" alt=""></button>
					</div>
                    <!-- Count of subscribers and subscriptions -->
					<div class="user-block__sub-count">
						<a class="user-block__sub-count-item" href=""><span><?php echo count($user->subscribers); ?> подписчик(ов)</span></a>
						<a class="user-block__sub-count-item" href=""><span><?php echo count($user->subscriptions); ?> подписок</span></a>
					</div>
				</section>
				<?php $user->print_workout_history($conn); ?>
                <!-- Buttons to edit profile, search sportsmen and logout -->
				<section class="user-block__buttons">
					<a href="search_users.php" class="button-text user-block__button"><p>Поиск спортсменов</p> <img src="../img/search_white.svg" alt=""></a>
                    <?php if ($user->get_auth()){ ?>
                    <button class="button-text user-block__button"><p>Редактировать профиль</p> <img src="../img/edit.svg" alt=""></button>
					<a href="../clear.php" class="button-text user-block__button-logout">Выйти <img src="../img/logout.svg" alt=""></a>
                    <?php }else{
                        if (in_array($user_data->get_id(), $user->subscribers)){ ?>
                            <p>Вы подписаны</p>
                            <a href="unsub.php?id=<?php echo $user->get_id(); ?>">Отписаться</a>
                        <?php }else{ ?>
                            <a href="sub.php?id=<?php echo $user->get_id(); ?>">Подписаться</a>
                        <?php }
                    }?>
				</section>
			</section>
		</div>


        <!-- User more information -->
        <section class="popup-exercise popup-exercise--user-info">
			<section class="popup-exercise__content popup-exercise__content--user-info">
                <button type="button" class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
                <!-- Спортсмен / тренер / врач -->
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип пользователя</p>
                    <p class="popup-user__info-item-info">Спортсмен</p>
                </div>
                <!-- Для тренера
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип тренера</p>
                    <p class="popup-user__info-item-info">Тренер команды / Личный тренер</p>
                </div> -->
                <!-- Для врача
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип тренера</p>
                    <p class="popup-user__info-item-info">Врач команды / Личный врач</p>
                </div> -->

                <!-- Люитель / профессионал / не указан -->
				<div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип спортсмена</p>
                    <p class="popup-user__info-item-info">Любитель</p>
                </div>

                <!-- низкий / средний / высокий / не указан-->
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Уровень физической подготовки</p>
                    <p class="popup-user__info-item-info">Не указан</p>
                </div>

                <button class="popup-user__edit-button"><img src="../img/edit_gray.svg" alt="">Изменить</button>
			</section>
		</section>

        <!-- User more information (version for editting) -->
        <section class="popup-exercise popup-exercise--user-info-edit">
			<form method="post" class="popup-exercise__content popup-exercise__content--user-info">
                <button type="button" class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
                <!-- Спортсмен / тренер / врач -->
                <div class="popup-user__info-item">
                <p class="popup-user__info-item-name">Тип пользователя</p>
                    <p class="popup-user__info-item-info">Спортсмен</p>
                </div>
                <!-- Для тренера
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип тренера</p>
                    <select class="popup-user__select" name="" id="">
                        <option class="popup-user__option" selected value="не указан">не указан</option>
                        <option class="popup-user__option" value="личный тренер">личный тренер</option>
                        <option class="popup-user__option" value="тренер команды">тренер команды</option>
                    </select>
                </div> -->
                <!-- Для врача
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип тренера</p>
                    <select class="popup-user__select" name="" id="">
                        <option class="popup-user__option" selected value="не указан">не указан</option>
                        <option class="popup-user__option" value="личный врач">личный тренер</option>
                        <option class="popup-user__option" value="врач команды">тренер команды</option>
                    </select>
                </div> -->

                <!-- Люитель / профессионал / не указан -->
				<div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Тип спортсмена</p>
                    <select class="popup-user__select" name="" id="">
                        <option class="popup-user__option" selected value="не указан">не указан</option>
                        <option class="popup-user__option" value="личный тренер">любитель</option>
                        <option class="popup-user__option" value="тренер команды">профессионал</option>
                    </select>
                </div>

                <!-- низкий / средний / высокий / не указан-->
                <div class="popup-user__info-item">
                    <p class="popup-user__info-item-name">Уровень физической подготовки</p>
                    <select class="popup-user__select" name="" id="">
                        <option class="popup-user__option" selected value="не указан">не указан</option>
                        <option class="popup-user__option" value="личный тренер">низкий</option>
                        <option class="popup-user__option" value="тренер команды">средний</option>
                        <option class="popup-user__option" value="тренер команды">высокий</option>
                    </select>
                </div>

                <button type="submit" class="button-text popup-user__save-button">Сохранить</button>
            </form>
		</section>

        <!-- description edit -->
		<section class="popup-exercise popup-exercise--description-edit">
			<form method="post" class="popup-exercise__content">
                <button type="button" class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
				<textarea class="popup-user__description-edit-text" name="" id=""></textarea>
				<button class="button-text popup-exercise__submit-button">Сохранить</button>
			</form>
		</section>
	</main>

	<?php include "../templates/footer.html";?>

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <!-- Подключение скриптов для Cropper.js и jQuery (если требуется) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        avatar_file.addEventListener('click', function(){
            document.querySelector('.preview_cover').style.cssText = `display: flex;`;
        });

        $(document).ready(function () {
          var croppedImageDataURL; // Переменная для хранения Data URL обрезанного изображения
      
          // При выборе файла для загрузки
          $("#avatar_file").on("change", function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();
      
            // Чтение файла и отображение его в элементе img#preview
            reader.onload = function (event) {
              $("#preview").attr("src", event.target.result);
              initCropper();
            };
      
            reader.readAsDataURL(file);
          });
      
          // Инициализация Cropper.js
          function initCropper() {
            var image = document.getElementById("preview");
            var cropper = new Cropper(image, {
                aspectRatio: 1, // Соотношение сторон 1:1 для круглой области обрезки
                viewMode: 2,    // Позволяет обрезать изображение внутри области обрезки


                // Позиционируем область обрезки в центре
                autoCropArea: 0.6,


                // Обработка обрезки изображения
                crop: function (event) {
                    // Получение координат и размеров обрезанной области
                    var x = event.detail.x;
                    var y = event.detail.y;
                    var width = event.detail.width;
                    var height = event.detail.height;

                    // Создание canvas с обрезанным изображением
                    var canvas = cropper.getCroppedCanvas({
                        width: width,
                        height: height,
                        left: x,
                        top: y,
                    });

                    // Преобразование canvas в Data URL изображения
                    croppedImageDataURL = canvas.toDataURL("png");
                },
            });
        }
      
          // Обработка сохранения обновленной аватарки
          $("#saveAvatar").on("click", function () {
            if (croppedImageDataURL) {
                location.reload()
                image_to_php.value = croppedImageDataURL
                document.getElementById("avatar_form").submit();
            } 
            else {
              alert("Сначала выберите и обрежьте изображение");
            }
          });
        });

        saveAvatar.addEventListener('click', function(){
            document.querySelector('.preview_cover').style.cssText = `display: none;`;
        });





        // =========Подробная информация=========
        let MoreInfoButton = document.querySelector('.user-block__avatar-more');

        let UserInfoPopup = document.querySelector('.popup-exercise--user-info');

        MoreInfoButton.addEventListener('click', function(){
			UserInfoPopup.classList.add("open");
		});



        // Подробная информация (изменение)
        let MoreInfoEditButton = document.querySelector('.popup-user__edit-button');

        let UserInfoEditPopup = document.querySelector('.popup-exercise--user-info-edit');

        MoreInfoEditButton.addEventListener('click', function(){
            UserInfoPopup.classList.remove("open");
			UserInfoEditPopup.classList.add("open");
		});



        // Изменение описания
        let DescriptionEditButton = document.querySelector('.user-about__description-button');

        let DescriptionPopup = document.querySelector('.popup-exercise--description-edit');

        DescriptionEditButton.addEventListener('click', function(){
            document.querySelector('.popup-user__description-edit-text').value = document.querySelector('.user-about__description-text').innerHTML;
			DescriptionPopup.classList.add("open");
		});



        const closeBtn = document.querySelectorAll('.popup-exercise__close-button');
		for(let i = 0; i < closeBtn.length; i++){
			closeBtn[i].addEventListener('click', function(){
				UserInfoPopup.classList.remove("open");
			    UserInfoEditPopup.classList.remove("open");
                DescriptionPopup.classList.remove("open");
			});
		}

		window.addEventListener('keydown', (e) => {
            if(e.key == "Escape"){
                UserInfoPopup.classList.remove("open");
                UserInfoEditPopup.classList.remove("open");
                DescriptionPopup.classList.remove("open");
            }
		});

		document.querySelector('.popup-exercise__content').addEventListener('click', event => {
			event.isClickWithInModal = true;
		});

    </script>
</body>