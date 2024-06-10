<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

$user_data->check_the_login();
if (empty($_SESSION["program"])){ // If $_SESSION["program"] is empty, initialize it with default values
    $_SESSION["program"] = array(0, 0, 0, 0, 0, 0, 0);
}

if (isset($_POST["weeks"]) && $_POST["weeks"] > 0){ // Check if form data is submitted and weeks value is greater than 0
    if (empty($_POST["date_start"])){ // Set the start date for the program
        $date_start = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
    }else{
        $date_ex = explode('-', $_POST["date_start"]);  // Splitting the date into an array
        $date_start = mktime(0, 0, 0, $date_ex[1], $date_ex[2], $date_ex[0]); // Creating a Unix timestamp based on the extracted year, month, and day values
    }

    // insert_program and user_to_program and news
    $sql = "INSERT INTO programs (name, creator) VALUES ('{$_POST["name"]}', {$user_data->get_id()})";
    if ($conn->query($sql)){
        $program_id = mysqli_insert_id($conn);
        for ($i = 0; $i < 7; $i++){
            $workout_id = $_SESSION["program"][$i];
            $sql = "INSERT INTO program_workouts(program_id, workout_id, week_day) VALUES ($program_id, $workout_id, $i)";
            if (!$conn->query($sql)){
                echo $conn->error;
            }
        }

        if ($user_data->get_status() == "user") { // If the user is a regular user
            $sql2 = "INSERT INTO program_to_user (user, program, date_start, weeks) VALUES (".$user_data->get_id().", $program_id, FROM_UNIXTIME($date_start), ".$_POST['weeks'].")";
            $sql3 = "INSERT INTO news (message, user, date, personal) VALUES ('Пользователь начал программу.', ".$user_data->get_id().", ".time().", 0)";
            if ($conn->query($sql2)){ // Insert program details, create news entry, and redirect to my_program.php
                $_SESSION["workout"] = array();
                $_SESSION["program"] = array();
                header("Location: my_program.php");
            }else{
                echo $conn->error; // Output any database errors
            }
        } else if ($user_data->get_status() == "coach") {
            $users = $_POST["users"];
            if (count($users) > 0){ // If users are selected
                foreach ($users as $user){
                    $sql2 = "INSERT INTO program_to_user (user, program, date_start, weeks) VALUES (".$user.", $program_id, FROM_UNIXTIME($date_start), ".$_POST['weeks'].")";
                    $sql3 = "INSERT INTO news (message, user, date, personal) VALUES ('Пользователь начал программу. (Тренер).', ".$user.", ".time().", 0)";
                    if (!$conn->query($sql2) || !$conn->query($sql3)){ // Insert program details for selected users, create news entries, and redirect to profile.php
                        echo $conn->error;
                    }
                }
                $_SESSION["workout"] = array();
                $_SESSION["program"] = array();
                header("Location: profile.php");
            }else{
                echo "Ошибка: вы не выбрали пользователя"; // Output error if no users are selected
            }
        }
    }else{
        echo $conn->error; // output any database errors
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body>
    <?php include BASE_PATH . "/templates/header.php"; // print header template ?>

	<main class="c-program">
		<div class="container">
			<section class="day-workouts" navigation="true">
                <section class="day-workouts__block--adaptive">
                    <?php
                    $workout_array = array(); // print first 4 cards of program
                        for($i = 0; $i < 4; $i++){
                            $workout = new Workout($conn, $_SESSION["program"][$i], $i);
                            if ($_SESSION["program"][$i] != 0){
                                $flag = true;
                                foreach ($workout_array as $item){
                                    if ($item->get_id() == $workout->get_id()){
                                        $flag = false;
                                        break;
                                    }
                                }
                                if ($flag)
                                    array_push($workout_array, $workout);
                            }
                            $workout->set_muscles();
                            $workout->print_workout_info_block($i, 0, $user_data->get_id());
                        } ?>
                </section>
                <section class="day-workouts__block--adaptive">
                    <?php
                    $workout_array = array();
                        for($i = 4; $i < 7; $i++){  // print last 3 cards of program
                            $workout = new Workout($conn, $_SESSION["program"][$i], $i);
                            if ($_SESSION["program"][$i] != 0){
                                $flag = true;
                                foreach ($workout_array as $item){
                                    if ($item->get_id() == $workout->get_id()){
                                        $flag = false;
                                        break;
                                    }
                                }
                                if ($flag)
                                    array_push($workout_array, $workout);
                            }
                            $workout->set_muscles();
                            $workout->print_workout_info_block($i, 0, $user_data->get_id());
                        } ?>
                </section>
                <section class="day-workouts__block">
                    <?php
                    $workout_array = array();
                        for($i = 0; $i < 7; $i++){ // print all cards of program
                            $workout = new Workout($conn, $_SESSION["program"][$i], $i);
                            if ($_SESSION["program"][$i] != 0){
                                $flag = true;
                                foreach ($workout_array as $item){
                                    if ($item->get_id() == $workout->get_id()){
                                        $flag = false;
                                        break;
                                    }
                                }
                                if ($flag)
                                    $workout_array[] = $workout;
                            }
                            $workout->set_muscles();
                            $workout->print_workout_info_block($i, 0, $user_data->get_id());
                        } ?>
                    </section>
			</section>
			<section class="c-program__create">
				<section class="c-program__workouts">
					<section class="c-program__workouts-list">
                        <?php
                        if (count($workout_array) == 0){?>
                            <p class="c-program__workouts-list-none">Список тренировок пуст</p>
                        <?php }
                        for ($i = 0; $i < count($workout_array); $i++){ // print workouts' names list ?>
						<div class="c-program__workouts-item">
							<p class="c-program__workouts-name"><?php echo $i + 1 . ". " .  ($workout_array[$i])->get_name(); ?></p>
							<a href="../Actions/delete_workout.php?id=<?php echo ($workout_array[$i])->get_id(); ?>" class="button-img c-program__workouts-delete"><img src="../../assets/img/delete.svg" alt=""></a>
						</div>
                        <?php } ?>
					</section>
					<div class="c-program__workouts-buttons">
						<a class="button-text c-program__workouts-button c-program__workouts-button--create" href="c_workout.php"><p>Создать тренировку</p> <img src="../../assets/img/add.svg" alt=""></a>
						<a class="button-text c-program__workouts-button" href="../Actions/clear_program.php"><p>Очистить программу</p> <img src="../../assets/img/delete.svg" alt=""></a>
					</div>
				</section>
				<form class="c-program__duration" method="post">
					<?php if ($user_data->get_status() == "coach"){ // if coach adding program to user ?>
					    <h4 class="c-program__duration-title">Выберите спортсменов<br></h4>
                    <?php }?>
					<?php if ($user_data->get_status() == "user"){  // if user, print duration blocks ?>
						<h4 class="c-program__duration-title">Укажите продолжительность программы<br></h4>
						<div class="c-program__duration-date">
							<input class="c-program__duration-weeks" type="number" placeholder="Количество недель" name="weeks">
							<p class="c-program__duration-date-text">начать с</p>
							<input class="c-program__duration-date-start" type="date" name="date_start">
						</div>
                    <?php } ?>
                    <?php if ($user_data->get_status() == "coach"){ // print nutton 'Добавить спортсменов' for coach?>
					    <button type="button" class="button-text c-program__add-button"><p>Добавить спортсменов</p><img src="../../assets/img/add.svg" alt=""></button>
                    <?php } else { ?>
					<button class="button-text c-program__duration-button"><p>Начать программу</p> <img src="../../assets/img/arrow_white.svg" alt=""></button>
					<?php } ?>
				</form>
			</section>
			<section class="friends-block">
				<!-- Title and button to search friends -->
				<div class="friends-block__header">
					<h1 class="friends-block__header-title">Программы друзей</h1>
					<a class="friends-block__header-button img" href="search_users.php"><img src="../../assets/img/search.svg" alt=""></a>
				</div>
				<!-- Friends' workout swiper -->
				<swiper-container class="friends-block__swiper" navigation="true">
					<!-- slide -->
                    <?php
                    $user_data->set_subscriptions($conn); // Set user subscriptions
                    print_user_list($conn, $user_data->get_subscriptions()); // Print the list of users from subscriptions
                    ?>
				</swiper-container>
			</section>
		</div>

		<!-- Popup form for add users (for coach) -->
        <section class="popup-exercise popup-exercise--add-users">
			<form method="post" class="popup-exercise__content popup-exercise--add-users__form">
                <button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
                <h4 class="c-program__duration-title">Укажите продолжительность программы<br></h4>
                <div class="c-program__duration-date c-program__duration-date--popup">
                    <input class="c-program__duration-weeks" type="number" placeholder="Количество недель" name="weeks">
                    <p class="c-program__duration-date-text">начать с</p>
                    <input class="c-program__duration-date-start" type="date" name="date_start">
                </div>
				<div class="workouts-card__info-line"></div>
                <?php
                $cnt = 0;
                foreach ($user_data->get_sportsmen() as $sportsman_id){ // print list of sportsmen
                    $sportsman = new User($conn, $sportsman_id);
                    if (!$sportsman->has_program($conn)){
                        $cnt++; ?>
                <div class="popup-exercise--add-users__item">
					<input class="popup-exercise--add-users__input" type="checkbox" id="users-list1" name="users[]" value="<?php echo $sportsman_id; ?>"/>
					<label class="popup-exercise--add-users__label" for="users-list1"><?php echo $sportsman->get_full_name(); ?></label>
				</div>
                <?php }
                    }
                if ($cnt != 0){ ?>
                    <button class="button-text popup-exercise--add-users__button-add" type="submit"><p>Выложить</p><img src="../../assets/img/add.svg" alt=""></button>
                <?php } else { ?>
                    <p class="c-program__duration-title-none">У Вас нет спортсменов</p>
                <?php } ?>
			</form>
		</section>
	</main>

	
	<?php include BASE_PATH . "/templates/footer.html" ?>

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
	<script>
        // variables for c program page
        let programAddButton = document.querySelector('.c-program__duration-button');
        let dateWokoutInput = document.querySelector('.c-program__duration-date-start');

        document.querySelector('.c-program__duration-weeks').addEventListener('input', function(){ // checks for duration inputs
            if(this.value.length == 0){
                programAddButton.type = 'button';
            }
            else{
                programAddButton.type = 'submit';
            }

            if (this.value > 16) {
                this.value = 16;
            }
        });

        if(programAddButton){
            programAddButton.addEventListener('click', function(){ // if date is not selected, set today's date
                if (!dateWokoutInput.value) {
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

                    const formattedDate = `${year}-${month}-${day}`;

                    // set today's date in input
                    dateWokoutInput.value = formattedDate;
                }
            });
        }


		// Workout items
        let workoutItemArr = document.querySelectorAll('.day-workouts__card-content');

        let maxWorkoutItemHeight = 0;

        for(let i = 0; i < workoutItemArr.length; i++){
            if(workoutItemArr[i].clientHeight > maxWorkoutItemHeight){
                maxWorkoutItemHeight = workoutItemArr[i].clientHeight;
            }
        }

        for(let i = 0; i < workoutItemArr.length; i++){
            workoutItemArr[i].style.cssText = `height: ${maxWorkoutItemHeight}px;`;
        }

        // Info slide items' spans width
        let infoItemsSpans = document.querySelectorAll('.workouts-card__item span');
        let maxSpanWidth = 0;

        for(let i = 0; i < infoItemsSpans.length; i++){
            maxSpanWidth = Math.max(maxSpanWidth, infoItemsSpans[i].clientWidth);
        }

        for(let i = 0; i < infoItemsSpans.length; i++){
            infoItemsSpans[i].style.cssText = `width: ${maxSpanWidth}px;`;
        }



		// Popup window to add users for coach
		let StartProgramButton = document.querySelector('.c-program__add-button');
		let UsersListPopup = document.querySelector('.popup-exercise--add-users');

		if(StartProgramButton){
			StartProgramButton.addEventListener('click', function(){
				UsersListPopup.classList.add("open");
			});
		}


        // buttons to close popup windows
        const closeBtn = document.querySelectorAll('.popup-exercise__close-button');
		for(let i = 0; i < closeBtn.length; i++){
			closeBtn[i].addEventListener('click', function(){ // close popup window
				UsersListPopup.classList.remove("open");
			});
		}

		window.addEventListener('keydown', (e) => { // close popup window
            if(e.key == "Escape"){
				UsersListPopup.classList.remove("open");
            }
		});

	</script>

    <!-- Test for clear workout buttons -->
    <!-- <script src="../tests/test_clear_program_contructor.js"></script> -->
</body>
</html>