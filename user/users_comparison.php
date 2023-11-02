<?php
include "../templates/func.php";
include "../templates/settings.php";
$user1 = NULL;
$user2 = NULL;
$sportsmen = $user_data->get_sportsmen();
if (isset($_GET["user1"]) && is_numeric($_GET["user1"]) && in_array($_GET["user1"], $sportsmen))
    $user1 = new User($conn, $_GET["user1"]);

if (isset($_GET["user2"]) && is_numeric($_GET["user2"]) && in_array($_GET["user2"], $sportsmen))
    $user2 = new User($conn, $_GET["user2"]);
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>

	<main class="user-comparison">
		<div class="container">
			<section class="comparison-block">
				<p class="staff-block__title">Первый спортсмен</p>
                    <?php if ($user1 == NULL){ ?>
                        <section class="staff-block__header">
                            <button class="button-text comparison-block__add-button"><p>Добавить спортсмена</p> <img src="../img/add.svg" alt=""></button>
                        </section>
                    <?php } else {
                        $reps = get_reps_for_comparison($user1, $conn, 1, $_GET["user2"]);
                        echo render($reps, "../templates/comparison_block.html");
                    } ?>
			</section>
			<section class="comparison-block">
				<p class="staff-block__title">Второй спортсмен</p>
                    <?php if ($user2 == NULL){ ?>
                        <section class="staff-block__header">
                            <button class="button-text comparison-block__add-button"><p>Добавить спортсмена</p> <img src="../img/add.svg" alt=""></button>
                        </section>
                    <?php } else {
                        $reps = get_reps_for_comparison($user2, $conn, 2, $_GET["user1"]);
                        echo render($reps, "../templates/comparison_block.html");
                    } ?>
			</section>
		</div>

        <section class="popup-exercise popup-exercise--user-first">
			<form class="popup-exercise__content popup-exercise--add-users__form">
				<button type="button" type="button" class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
                <div class="popup-exercise--add-users__item">
					<input class="popup-exercise--add-users__input" type="checkbox" id="users-list1" name="users[]" value="1"/>
					<label class="popup-exercise--add-users__label" for="users-list1">sdvsadvsadv</label>
				</div>
				<button type="submit" class="button-text popup-exercise--add-users__button-add" type="submit"><p>Добавить</p><img src="../img/add.svg" alt=""></button>
			</form>
		</section>

        <section class="popup-exercise popup-exercise--user-second">
            <form class="popup-exercise__content popup-exercise--add-users__form">
				<button type="button" type="button" class="popup-exercise__close-button"><img src="../img/close.svg" alt=""></button>
                <div class="popup-exercise--add-users__item">
					<input class="popup-exercise--add-users__input" type="checkbox" id="users-list2" name="users[]" value="1"/>
					<label class="popup-exercise--add-users__label" for="users-list2">sdvsadvsadv</label>
				</div>
				<button type="submit" class="button-text popup-exercise--add-users__button-add" type="submit"><p>Добавить</p><img src="../img/add.svg" alt=""></button>
			</form> 
		</section>
	</main>
	</main>

    <?php include "../templates/footer.html" ?>

    <script>
        let FirstUserAddPopup = document.querySelector('.popup-exercise--user-first');
        let SecondUserAddPopup = document.querySelector('.popup-exercise--user-second');

        let userAddButton = document.querySelectorAll('.comparison-block__add-button');

        for(let i = 0; i < userAddButton.length; i++){
            userAddButton[i].addEventListener('click', function(){
                console.log(1)
                if(i == 0){
                    FirstUserAddPopup.classList.add("open");
                }
                if(i == 1){
                    SecondUserAddPopup.classList.add("open");
                }
            });
        }

        const closeBtn = document.querySelectorAll('.popup-exercise__close-button');
		for(let i = 0; i < closeBtn.length; i++){
			closeBtn[i].addEventListener('click', function(){
				FirstUserAddPopup.classList.remove("open");
                SecondUserAddPopup.classList.remove("open");
			});
		}

		window.addEventListener('keydown', (e) => {
            if(e.key == "Escape"){
                FirstUserAddPopup.classList.remove("open");
                SecondUserAddPopup.classList.remove("open");
            }
		});

		document.querySelector('.popup-exercise__content').addEventListener('click', event => {
			event.isClickWithInModal = true;
		});
    </script>
</body>
</html>