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

	<main>
        <section class="preview_cover">
            <div class="preview_block">
                <img id="preview" src="#" alt="Preview"/>
            </div>
            <button id="saveAvatar">Сохранить</button>
        </section>

		<div class="container user_block">
            <!-- Info about user -->
			<section class="info">
				<section class="about">
					<!-- User avatar, name and surname -->
                    <form id="avatar_form" class="avatar" method="post">
                        <img id="profileImage" src="<?php echo $user->get_avatar($conn); ?>">
                        <input type="file" id="avatar_file" accept="image/*" />
                        <label for="avatar_file" class="uppload_button">Choose photo</label>
                        <input type="hidden" id="image_to_php" name="image_to_php" value="">
                    </form>
                    <!-- User info text -->
					<div class="content">
						<p><?php echo $user->description; ?></p>
                        <?php if ($user->get_auth()){ ?>
						    <button><img src="../img/edit_gray.svg" alt="">Изменить</button>
                        <?php } ?>
					</div>
				</section>
                <!-- User's news -->
				<section class="news">
                    <!-- Button 'Новая запись' -->
                    <?php if ($user->get_auth()){ ?>
					    <button class="new_post_btn">Новая запись <img src="../img/add.svg" alt=""></button>
                    <?php } ?>
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
			<section class="other">
				<section class="friends">
                    <!-- Title and button to search friends -->
                    <div class="title">
                        <h1>Подписки</h1>
                        <a href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends swiper -->
                    <swiper-container class="content swiper_friends" navigation="true">
                        <?php print_user_list($conn, $user->subscriptions); ?>
					</swiper-container>
                </section>
                <!-- User's staff (coach and doctor) -->
				<section class="staff">
                    <!-- Coach info and buttons to chat, ptofile and delete -->
					<div class="coach">
						<p>Тренер: <span>Штангов К.</span></p>
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
						<a href=""><span><?php echo count($user->subscribers); ?> подписчик(ов)</span></a>
						<a href=""><span><?php echo count($user->subscriptions); ?> подписок</span></a>
					</div>
				</section>
				<?php $user->print_workout_history($conn); ?>
                <!-- Buttons to edit profile, search sportsmen and logout -->
				<section class="buttons">
					<button>Поиск спортсменов <img src="../img/search_white.svg" alt=""></button>
                    <?php if ($user->get_auth()){ ?>
                    <button>Редактировать профиль <img src="../img/edit.svg" alt=""></button>
					<a href="../clear.php" class="logout">Выйти <img src="../img/logout.svg" alt=""></a>
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
    </script>
</body>