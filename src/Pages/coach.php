<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

if ($user_data->get_status() != "coach") // Check if the logged-in user is a coach
    header("Location: profile.php");

$user = NULL;
if (isset($_GET["user"]) && $_GET["user"] != ''){ // Check if a user is specified in the GET parameter
    $user = new User($conn, $_GET["user"]);
}

$is_selected = $user != NULL && $user->get_id() != NULL && in_array($user->get_id(), $user_data->get_sportsmen());
$sportsmen = $user_data->get_sportsmen_advanced($conn);

if (isset($_POST["request_name"])) { // Process form submissions
    $data = $user_data->get_coach_data($conn, $_POST["user_med"]);
    switch ($_POST["request_name"]) {
        case "add_competition": // Handling addition of competitions
            if (empty($_POST["name"]) || $_POST["name"] == "")
                break;
            if (!$date = strtotime($_POST["date"])){
                $date = NULL;
            }
            $sql = "INSERT INTO competitions (name, link, date) VALUES ('" . $_POST["name"] . "', '" . $_POST["link"] . "', '$date')"; // Insert into competitions table
            if ($conn->query($sql)) { // Update coach's competitions data in JSON format
                $id = mysqli_insert_id($conn);
                if ($data != NULL) {
                    $competitions = json_decode($data["competitions"]);
                    array_push($competitions, $id);
                    $competitions = json_encode($competitions, 256);
                    $data["competitions"] = $competitions;
                    $user_data->update_coach_data($conn, $data);
                } else {
                    echo "NULL";
                }
            } else {
                echo $conn->error;
            }
            break;
        case "add_info": // Handling addition of advice
            if (empty($_POST["name"]) || $_POST["name"] == "")
                break;
            $sql = "INSERT INTO coach_advice (name, link) VALUES ('" . $_POST["name"] . "', '" . $_POST["link"] . "')";
            if ($conn->query($sql)) {
                $id = mysqli_insert_id($conn);
                if ($data != NULL) {
                    $info = json_decode($data["info"]);
                    array_push($info, $id);
                    $info = json_encode($info, 256);
                    $data["info"] = $info;
                    $user_data->update_coach_data($conn, $data);
                } else {
                    echo "NULL";
                }
            } else {
                echo $conn->error;
            }
            break;
        case "add_goal": // Handling addition of goals
            if (empty($_POST["name"]) || $_POST["name"] == "")
                break;
            $sql = "INSERT INTO goals (name) VALUES ('" . $_POST["name"] . "')";
            if ($conn->query($sql)) {
                $id = mysqli_insert_id($conn);
                if ($data != NULL) {
                    $goals = json_decode($data["goals"]);
                    array_push($goals, $id);
                    $goals = json_encode($goals, 256);
                    $data["goals"] = $goals;
                    $user_data->update_coach_data($conn, $data);
                } else {
                    echo "NULL";
                }
            } else {
                echo $conn->error;
            }
            break;
    }
}

if ($is_selected){ // If a user is selected, fetch their data
    $data = $user_data->get_coach_data($conn, $user->get_id());
    $data["competitions"] = json_decode($data["competitions"]);
    $data["goals"] = json_decode($data["goals"]);
    $data["info"] = json_decode($data["info"]);
    $user->set_program($conn);
    $control_workouts = $user_data->get_control_workouts($conn, 0, $user->get_id());
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body class="coach-page">
    <?php include BASE_PATH . "/templates/header.php"; ?>

	<main class="staff-cover">
		<div class="container">
            <?php if ($is_selected){ // If a user is selected ?>
			<section class="staff-block">
				<p class="staff-block__title">Спортсмен</p>
				<section class="staff-block__header">
					<img class="staff-block__avatar" src="<?php echo $user->get_avatar($conn);//print avatar ?>" alt="">
					<section class="staff-block__info">
						<div class="staff-block__name">
							<h1 class="staff-block__name-text"><?php echo $user->name." ".$user->surname; ?></h1>
							<a class="staff-block__profile-link" href="profile.php?user=<?php echo $user->get_id(); // link to profile ?>"><img src="../../assets/img/profile_black.svg" alt=""></a>
						</div>
						<div class="staff-block__buttons">
                            <?php if ($user->vk != NULL) { ?>
                                <a href=<?php echo $user->vk; ?> class="staff-block__button staff-block__button--img"><img src="../../assets/img/vk.svg" alt=""></a>
                            <?php } if ($user->tg != NULL) { ?>
                                <a href="<?php echo $user->tg; ?>" class="staff-block__button staff-block__button--img"><img src="../../assets/img/tg.svg" alt=""></a>
                            <?php } ?>
							<a href="../Actions/delete_sportsman.php?user=<?php echo $user->get_id(); // link to delite sportsman ?>" class="button-text staff-block__button staff-block__button--delite"><p>Удалить</p> <img src="../../assets/img/delete.svg" alt=""></a>
						</div>
					</section>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Ближайшая тренировка</h2>
					<div class="staff-block__nearest-workout-content">
						<div class="staff-block__nearest-workout-date">
                        <?php
                            $user->set_program($conn); // Set the user's program
                            if ($user->set_program($conn)) {
                                $cl_w = $user->get_closest_workout($conn); // Check if the user's program is set and fetch the closest workout
                                if ($cl_w != NULL) { // If there's a closest workout, display its date in "dd.mm.YYYY" format
                                    echo date("d.m.Y", $cl_w);
                                } else echo "Тренировок не будет";
                            }else
                                echo "Тренировок не будет";
                        ?>
                        </div>
						<a href="my_program.php?user=<?php echo $user->get_id(); // link to more info about workout ?>" class="staff-block__button-more"><p>Подробнее</p> <img src="../../assets/img/more_white.svg" alt=""></a>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Цели</h2>
					<ul class="staff-block__goals-list">
                        <?php if (count($data["goals"]) > 0) // if goals > 0 print list of goals
                            foreach ($data["goals"] as $goal)
                                print_goal($conn, (int)$goal, $user->get_id());
                        else{ ?>
                            <li class="staff-block__goals-item">Нет назначенных целей</li>
                        <?php } ?>
					</ul>
					<button class="button-text staff-block__item-button--add staff-block__item-button--goal-add"><p>Добавить</p><img src="../../assets/img/add.svg" alt=""></button>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Контрольные тренировки</h2>
                    <?php if (count($control_workouts) > 0){ // constrol workout > 0 ?>
                        <div class="staff-block__control-workout-nearest">
                            <div class="staff-block__control-workout-info">
                                <p class="staff-block__control-workout-text">Ближайшая:</p>
                                <div class="staff-block__control-workouts-date"><?php echo date("d.m.Y", ($control_workouts[0])->date); // print date ?></div>
                            </div>
                            <a href="control_workouts.php?user=<?php echo $user->get_id(); ?>" class="staff-block__button-more"><p>Подробнее</p> <img src="../../assets/img/more_white.svg" alt=""></a>
                        </div>
                        <a href="control_workout_session.php?id=<?php echo $control_workouts[0]->id; ?>" class="button-text day-workouts__card-button day-workouts__card-button--start-c"><p>Начать</p><img src="../../assets/img/arrow_white.svg" alt=""></a>
				    <?php } else { ?>
                        <p class="staff-block__control-none">Нет назначенных контрольных тренировок</p>
                        <a class="staff-block__button-more staff-block__button-more--control" href="control_workouts.php?user=<?php echo $user->get_id(); // link to more info ?>"><p>Подробнее</p> <img src="../../assets/img/more_white.svg" alt=""></a>
                    <?php } ?>
                </section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Турниры и соревнования</h2>
					<div class="staff-block__competitions">
                        <?php if (count($data["competitions"]) > 0)
                            foreach ($data["competitions"] as $competition) // print competitions list
                                print_competition($conn, (int)$competition, $user->get_id());
                        else{ ?>
                            <p class="staff-block__control-none">Нет назначенных соревнований</p>
                        <?php } ?>
						<button class="button-text staff-block__item-button--add staff-block__item-button--competition-add"><p>Добавить</p><img src="../../assets/img/add.svg" alt=""></button>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Полезная информация</h2>
					<div class="staff-block__useful-links">
                        <?php if (count($data["info"]) > 0)
                            foreach ($data["info"] as $advice) // print advices list
                                print_advice($conn, (int)$advice, $user->get_id());
                        else{ ?>
                            <p class="staff-block__control-none">Нет полезной информации</p>
                        <?php } ?>
						<button class="button-text staff-block__item-button--add staff-block__item-button--link-add"><p>Добавить</p><img src="../../assets/img/add.svg" alt=""></button>
					</div>
				</section>
			</section>
            <?php } else { ?>
				<section class="staff-block">
					<p class="staff-block__title-none">Пользователь не выбран</p>
				</section>
            <?php } ?>
			<section class="coach-other">
                <?php if ($is_selected) $user->print_workout_history($conn); // print workotu history ?>
				<section class="friends-block">
                    <!-- Title and button to search friends -->
                    <div class="friends-block__header">
                        <h1 class="friends-block__header-title">Другие спортсмены</h1>
                        <a class="friends-block__header-button" href="search_sportsman.php"><img src="../../assets/img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends' workout swiper -->
                   <section class="friends-block__cover">
                       <?php
                       $cnt_sportsmen = count($sportsmen); // Get the count of sportsmen
                       if ($cnt_sportsmen > 4) // Limit the count to 4
                           $cnt_sportsmen = 4;
                       for ($i = 0; $i < $cnt_sportsmen; $i++) { // Loop through the sportsmen ?>
                           <a href="coach.php?user=<?php echo $sportsmen[$i]->get_id(); ?>" class="friends-block__item">
                               <img class="friends-block__avatar" src="<?php echo $sportsmen[$i]->get_avatar($conn); ?>" alt="">
                               <p class="friends-block__name"><?php echo $sportsmen[$i]->name?></p>
                           </a>
                       <?php } ?>
					</section>
			</section>
			<section class="staff-other__buttons">
				<?php if ($is_selected){ ?>
                    <a href="my_program.php?user=<?php echo $user->get_id(); // program of sportsman ?>" class="button-text staff-other__button"><p>Программа</p> <img src="../../assets/img/my_programm.svg" alt=""></a>
                    <a href="progress.php?user=<?php echo $user->get_id(); // progress of sportsman ?>" class="button-text staff-other__button"><p>Прогресс спортсмена</p> <img src="../../assets/img/progress.svg" alt=""></a>
                <?php }?>
                <a href="users_comparison.php" class="button-text staff-other__button"><p>Сравнить спортсменов</p> <img src="../../assets/img/comparison.svg" alt=""></a>
			</section>
		</div>


		<!-- Goals add -->
		<section class="popup-exercise popup-exercise--goals-add">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<input class="popup-exercise__input-item goals-add__name" type="text" placeholder="название цели" name="name">
                <input type="hidden" name="request_name" value="add_goal">
                <input type="hidden" name="user_med" value="<?php if (isset($_GET["user"])) echo $_GET["user"]; ?>">
				<button class="button-text popup-exercise__submit-button">Добавить</button>
			</form>
		</section>


		<!-- Competitions add-->
		<section class="popup-exercise popup-exercise--competitions-add">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<input class="popup-exercise__input-item competitions-add__name" type="text" placeholder="название соревнования" name="name">
				<input class="popup-exercise__input-item competitions-add__date" type="date" name="date">
				<input class="popup-exercise__input-item competitions-add__link" type="text" placeholder="вставьте ссылку" name="link">
                <input type="hidden" name="request_name" value="add_competition">
                <input type="hidden" name="user_med" value="<?php if (isset($_GET["user"])) echo $_GET["user"]; ?>">
				<button class="button-text popup-exercise__submit-button popup-exercise__submit-button--competition">Добавить</button>
			</form>
		</section>


		<!-- Useful links add-->
		<section class="popup-exercise popup-exercise--links-add">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<input class="popup-exercise__input-item links-add__name" type="text" placeholder="название" name="name">
				<input class="popup-exercise__input-item links-add__link" type="text" placeholder="вставьте ссылку" name="link">
                <input type="hidden" name="request_name" value="add_info">
                <input type="hidden" name="user_med" value="<?php if (isset($_GET["user"])) echo $_GET["user"]; ?>">
				<button class="button-text popup-exercise__submit-button popup-exercise__submit-button--useful">Добавить</button>
			</form>
		</section>
	</main>

    <?php include BASE_PATH . "/templates/footer.html" ?>

	<script>
        // variables of coach page
        let goalAddNameInput = document.querySelector('.goals-add__name');
        let competetionAddNameInput = document.querySelector('.competitions-add__name');
        let competetionAddDateInput = document.querySelector('.competitions-add__date');
        let competetionAddLinkInput = document.querySelector('.competitions-add__link');
        let addUsefulLinkInput = document.querySelector('.links-add__link');
        let addUsefulTextInput = document.querySelector('.links-add__name');
        let competetionAddSubmitButton = document.querySelector('.popup-exercise__submit-button--competition');
        let usefulTextAddSubmitButton = document.querySelector('.popup-exercise__submit-button--useful');

        // input checks
        goalAddNameInput.addEventListener('input', function(){ // check goal input
			if(this.value.length > 250){
				this.value = this.value.slice(0, 250);
			}
		});

        competetionAddNameInput.addEventListener('input', function(){// competition input
			if(this.value.length > 250){
				this.value = this.value.slice(0, 250);
			}
		});

        competetionAddSubmitButton.addEventListener('click', function(){ // competetion add button
            if (!competetionAddDateInput.value) { // if date is not selected, select today's date
				// set today's date
				const todayDate = new Date();
				let year = todayDate.getFullYear();
				let month = todayDate.getMonth() + 1;
				let day = todayDate.getDate();

				if (month < 10) {
					month = `0${month}`;
				}
				if (day < 10) {
					day = `0${day}`;
				}

				const formattedDate = `${year}-${month}-${day}`; // set data

				// set today's date in input
				competetionAddDateInput.value = formattedDate;
			}
		});


        // checks of socia; media inputs
        addUsefulLinkInput.addEventListener('input', function(){ // check link
			const urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;

            if (!urlRegex.test(this.value) && this.value.length > 0) {
                usefulTextAddSubmitButton.type = 'button';
                addUsefulLinkInput.style.cssText = 'border: 1px solid #ff2323';
            }
            else{
                usefulTextAddSubmitButton.type = 'submit';
                addUsefulLinkInput.style.cssText = 'border: 1px solid #7D7D7D;';
            }
		});

        addUsefulTextInput.addEventListener('input', function(){ // check text
			if(this.value.length > 250){
				this.value = this.value.slice(0, 250);
			}
		});


        competetionAddLinkInput.addEventListener('input', function(){ // check link input
			const urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;

            if (!urlRegex.test(this.value) && this.value.length > 0) {
                competetionAddSubmitButton.type = 'button';
                competetionAddLinkInput.style.cssText = 'border: 1px solid #ff2323';
            }
            else{
                competetionAddSubmitButton.type = 'submit';
                competetionAddLinkInput.style.cssText = 'border: 1px solid #7D7D7D;';
            }
		});



		// variables for coach page
		let GoalsEditPopup = document.querySelector('.popup-exercise--goals-edit');
		let GoalsAddPopup = document.querySelector('.popup-exercise--goals-add');
		let CompetitionsEditPopup = document.querySelector('.popup-exercise--competitions-edit');
		let CompetitionsAddPopup = document.querySelector('.popup-exercise--competitions-add');
		let LinksEditPopup = document.querySelector('.popup-exercise--links-edit');
		let LinksAddPopup = document.querySelector('.popup-exercise--links-add');

		let GoalsAddButton = document.querySelector('.staff-block__item-button--goal-add');
		let CompetitionsAddButton = document.querySelector('.staff-block__item-button--competition-add');
		let LinksAddButton = document.querySelector('.staff-block__item-button--link-add');

		let GoalNameText = document.querySelectorAll('.staff-block__goal-text');
		let CompetitionNameText = document.querySelectorAll('.staff-block__competition-text');
		let CompetitionFileText = document.querySelectorAll('.staff-block__link-button--competitions-file');
		let CompetitionLinkText = document.querySelectorAll('.staff-block__link-button--competitions-link');
		let InfoNameText = document.querySelectorAll('.staff-block__useful-links-text');
		let InfoFileText = document.querySelectorAll('.staff-block__link-button--info-file');
		let InfoLinkText = document.querySelectorAll('.staff-block__link-button--info-link');


        // open popup window to edit goals' list
        if(GoalsAddButton){
            GoalsAddButton.addEventListener('click', function(){
                GoalsAddPopup.classList.add("open");
            });
        }

        // open popup window to edit competitions' list
        if(CompetitionsAddButton){
            CompetitionsAddButton.addEventListener('click', function(){
			CompetitionsAddPopup.classList.add("open");
		});
        }

        // open popup window to edit usefil links' list
        if(LinksAddButton){
            LinksAddButton.addEventListener('click', function(){
                LinksAddPopup.classList.add("open");
            });
        }

        // buttons to close popup windows
		const closeBtn = document.querySelectorAll('.popup-exercise__close-button');
		for(let i = 0; i < closeBtn.length; i++){
			closeBtn[i].addEventListener('click', function(){ // close popup
				GoalsAddPopup.classList.remove("open");
				CompetitionsAddPopup.classList.remove("open");
				LinksAddPopup.classList.remove("open");
			});
		}

		window.addEventListener('keydown', (e) => {// close popup
		if(e.key == "Escape"){
			GoalsAddPopup.classList.remove("open");
			CompetitionsAddPopup.classList.remove("open");
			LinksAddPopup.classList.remove("open");
		}
		});
	</script>
</body>
</html>