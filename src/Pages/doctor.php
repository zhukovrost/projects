<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями

// Redirect if the user is not a doctor
if ($user_data->get_status() != "doctor")
    header("Location: profile.php");

$user = NULL;
if (isset($_GET["user"]) && $_GET["user"] != ''){ // Check if a specific user is selected and exists in the list of sportsmen
    $user = new User($conn, $_GET["user"]);
}

// Check if a user is selected and present in the list of sportsmen
$is_selected = $user != NULL && $user->get_id() != NULL && in_array($user->get_id(), $user_data->get_sportsmen());
$sportsmen = $user_data->get_sportsmen_advanced($conn); // get advanced sportsmen data

// get different POST requests related to doctor's functionalities
if (isset($_POST["request_name"])){
    $data = $user_data->get_doctor_data($conn, $_POST["user_med"]);
    switch ($_POST["request_name"]){
        case "add_medicine": // Add medicine
            if (empty($_POST["name"]) || $_POST["name"] == "")
                break;
            $sql = "INSERT INTO medicines (user, name, caption) VALUES ({$user->get_id()}, '".$_POST["name"]."', '".$_POST["caption"]."')"; // Insert medicine into the database and update user's medical data
            if (!$conn->query($sql)){
                echo $conn->error;
            }
            break;
        case "update_period": // Update period
			// Update the intake start and end date in the doctor's data for the user
            $start = (int)strtotime($_POST["start"]);
            $end = (int)strtotime($_POST["end"]);
            if (!$start && !$end){
                break;
            }
            if (!$start)
                $start = $end;
            if (!$end)
                $end = $start;
            $sql = "INSERT INTO treatment_period (user, intake_start, intake_end) VALUES ({$_GET["user"]}, FROM_UNIXTIME($start), FROM_UNIXTIME($end))"; // Insert medicine into the database and update user's medical data
            if (!$conn->query($sql)){
                $sql = "UPDATE treatment_period SET intake_start=FROM_UNIXTIME($start), intake_end=FROM_UNIXTIME($end) WHERE user={$user->get_id()}"; // Insert medicine into the database and update user's medical data
                if (!$conn->query($sql)){
                    echo $conn->error;
                }
            }
            break;
        case "update_recommendations": // Update recommendations
			// Update the recommendations in the doctor's data for the user
            if ($_POST["text"] == "")
                break;
            $sql = "INSERT INTO recommendations (user, recommendation) VALUES ({$user->get_id()}, '{$_POST["text"]}')"; // Insert medicine into the database and update user's medical data
            if (!$conn->query($sql)){
                $sql = "UPDATE recommendations SET recommendation='{$_POST["text"]}' WHERE user={$user->get_id()}"; // Insert medicine into the database and update user's medical data
                if (!$conn->query($sql)){
                    echo $conn->error;
                }
            }
            break;
    }
    header("Refresh: 0");
}
if ($is_selected){ // If a user is selected, fetch and process the doctor's data for that user
    $data = $user_data->get_doctor_data($conn, $user->get_id());
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body class="doctor-page">
    <?php include BASE_PATH . "/templates/header.php"; // print header template ?>

	<main class="staff-cover">
		<div class="container">
            <?php if ($is_selected){ // if sportsman os selected ?>
			<section class="staff-block">
				<p class="staff-block__title">Спортсмен</p>
				<section class="staff-block__header">
					<img class="staff-block__avatar" src="<?php echo $user->get_avatar($conn); // get avatar ?>" alt="">
					<section class="staff-block__info">
						<div class="staff-block__name">
							<h1 class="staff-block__name-text"><?php echo $user->get_full_name(); ?></h1>
							<a class="staff-block__profile-link" href="profile.php?user=<?php echo $user->get_id(); // link to profile ?>"><img src="../../assets/img/profile_black.svg" alt=""></a>
						</div>
						<div class="staff-block__buttons">
                            <?php if ($user->get_vk() != NULL) { ?>
							<a href=<?php echo $user->get_vk(); // get_vk() link ?> class="staff-block__button staff-block__button--img"><img src="../../assets/img/get_vk().svg" alt=""></a>
                            <?php } if ($user->get_tg() != NULL) { ?>
							<a href="<?php echo $user->get_tg(); // get_tg() link ?>" class="staff-block__button staff-block__button--img"><img src="../../assets/img/get_tg().svg" alt=""></a>
                            <?php } ?>
							<a href="../Actions/delete_sportsman.php?user=<?php echo $user->get_id(); // link to delite sportsman ?>" class="button-text staff-block__button staff-block__button--delite"><p>Удалить</p> <img src="../../assets/img/delete.svg" alt=""></a>
						</div>
					</section>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Приём лекарств</h2>
					<div class="staff-block__medicines">
                        <?php if ($data["medicines"]->num_rows > 0) // if medicines > 0
                            foreach ($data["medicines"] as $medicine) // print medicines' items
                                print_medicine($medicine, $user->get_id());
                        else{ ?>
                            <p class="staff-block__medicines-none">Нет назначенных лекарств</p>
                        <?php } ?>
					</div>
					<button class="button-text staff-block__item-button--add"><p>Добавить</p><img src="../../assets/img/add.svg" alt=""></button>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Период лечения</h2>
					<div class="staff-block__treatment-date">
						<div class="staff-block__treatment-date-item"><?php if ($data["intake_start"] == NULL) echo "Не выбрано"; else echo $data["intake_start"] // print start date ?></div>
						<div class="staff-block__treatment-date-line"></div>
						<div class="staff-block__treatment-date-item"><?php if ($data["intake_end"] == NULL) echo "Не выбрано"; else echo $data["intake_end"] // print end date ?></div>
					</div>
					<div class="staff-block__treatment-buttons">
						<button class="button-img staff-block__item-button staff-block__item-button--date"><img src="../../assets/img/edit.svg" alt=""></button>
					</div>
				</section>
				<div class="staff-block__line"></div>
				<section class="staff-block__item">
					<h2 class="staff-block__subtitle">Рекомендации по лечению</h2>
					<div class="staff-block__treatment-recommendation"><?php if (isset($data["recommendations"]) && $data["recommendations"] != "") echo $data["recommendations"]; else echo "Нет рекоммендаций"; // print recommendations ?></div>
					<div class="staff-block__treatment-buttons">
						<button class="button-img staff-block__item-button staff-block__item-button--recommendation"><img src="../../assets/img/edit.svg" alt=""></button>
					</div>
				</section>
			</section>
            <?php } else { ?>
                <section class="staff-block">
					<p class="staff-block__title-none">Пользователь не выбран</p>
				</section>
            <?php } ?>
			<section class="staff-other">
				<section class="friends-block">
                    <!-- Title and button to search friends -->
                    <div class="friends-block__header">
                        <h1 class="friends-block__header-title">Другие спортсмены</h1>
                        <a class="friends-block__header-button" href="search_sportsman.php">ЗАЯВКИ</a>
                    </div>
                    <!-- Friends' workout swiper -->
                   <section class="friends-block__cover" navigation="true">
                       <?php
                       $cnt_sportsmen = count($sportsmen); // Get the count of sportsmen
                       if ($cnt_sportsmen > 4) // If there are more than 4 sportsmen, limit the count to 4
                           $cnt_sportsmen = 4;
                       for ($i = 0; $i < $cnt_sportsmen; $i++) { // Loop through the sportsmen (maximum of 4) and generate HTML elements ?>
                           <a href="doctor.php?user=<?php echo $sportsmen[$i]->get_id(); ?>" class="friends-block__item">
                               <img class="friends-block__avatar" src="<?php echo $sportsmen[$i]->get_avatar($conn); ?>" alt="">
                               <p class="friends-block__name"><?php echo $sportsmen[$i]->name?></p>
                           </a>
                       <?php } ?>
					</section>
			</section>
		</div>

		<!-- Edit medicine -->
		<section class="popup-exercise popup-exercise--edit-medicine">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<input class="popup-exercise__input-item edit-medicine__name" type="text" placeholder="название">
				<input class="popup-exercise__input-item edit-medicine__dose" type="text" placeholder="доза">
				<button class="button-text popup-exercise__submit-button">Сохранить</button>
			</form>
		</section>

		<!-- Add medicine -->
		<section class="popup-exercise popup-exercise--add-medicine">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<input name="name" class="popup-exercise__input-item add-medicine__name" type="text" placeholder="название">
				<input name="caption" class="popup-exercise__input-item add-medicine__dose" type="text" placeholder="доза">
                <input type="hidden" name="request_name" value="add_medicine">
                <input type="hidden" name="user_med" value="<?php if (isset($_GET["user"])) echo $_GET["user"]; ?>">
				<button type="submit" class="button-text popup-exercise__submit-button">Добавить</button>
			</form>
		</section>

		<!-- Treatment date -->
		<section class="popup-exercise popup-exercise--treatment-date">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<input name="start" class="popup-exercise__input-item treatment-date__start" type="date" placeholder="начало">
				<input name="end" class="popup-exercise__input-item treatment-date__end" type="date" placeholder="конец">
                <input type="hidden" name="request_name" value="update_period">
                <input type="hidden" name="user_med" value="<?php if (isset($_GET["user"])) echo $_GET["user"]; ?>">
				<button class="button-text popup-exercise__submit-button">Сохранить</button>
			</form>
		</section>

		<!-- Treatment recommendation -->
		<section class="popup-exercise popup-exercise--treatment-recommendation">
			<form method="post" class="popup-exercise__content">
				<button type="button" class="popup-exercise__close-button"><img src="../../assets/img/close.svg" alt=""></button>
				<textarea class="doctor-texterea-item treatment-recommendation__edit" name="text" id="" placeholder="рекомендации"></textarea>
                <input type="hidden" name="request_name" value="update_recommendations">
                <input type="hidden" name="user_med" value="<?php if (isset($_GET["user"])) echo $_GET["user"]; ?>">
				<button class="button-text popup-exercise__submit-button">Сохранить</button>
			</form>
		</section>
	</main>

    <?php include BASE_PATH . "/templates/footer.html" ?>

	<script>
		let medicineAddNameInput = document.querySelector('.add-medicine__name');
		let medicineAddDoseInput = document.querySelector('.add-medicine__dose');
		
		// medicine inputs checks
		medicineAddNameInput.addEventListener('input', function(){
			if(this.value.length > 90){
				this.value = this.value.slice(0, 90);
			}
		});

		medicineAddDoseInput.addEventListener('input', function(){
			if(this.value.length > 70){
				this.value = this.value.slice(0, 70);
			}
		});


		let recommendationTextarea = document.querySelector('.treatment-recommendation__edit');
		recommendationTextarea.addEventListener('input', function(){
			if(this.value.length > 5000){
				this.value = this.value.slice(0, 5000);
			}
		});


		// Popup window for doctor page
		let MedicineEditPopup = document.querySelector('.popup-exercise--edit-medicine');
		let MedicineAddPopup = document.querySelector('.popup-exercise--add-medicine');
		let TreatmentDatePopup = document.querySelector('.popup-exercise--treatment-date');
		let RecommendationPopup = document.querySelector('.popup-exercise--treatment-recommendation');
		
		let MedicineEditButtons = document.querySelectorAll('.staff-block__medicines .staff-block__item-button--edit');
		let MedicineAddButton = document.querySelector('.staff-block__item-button--add');
		let TreatmentDateEditButton = document.querySelector('.staff-block__item-button--date');
		let RecommendationEditButton = document.querySelector('.staff-block__item-button--recommendation');

		let MedicineNameText = document.querySelectorAll('.staff-block__medicine-name');
		let MedicineDoseText = document.querySelectorAll('.staff-block__medicine-dose');

		// open popup window to edit medicines' list
		for(let i = 0; i < MedicineEditButtons.length; i++){
			MedicineEditButtons[i].addEventListener('click', function(){
				document.querySelector('.edit-medicine__name').value = MedicineNameText[i].innerHTML;
				document.querySelector('.edit-medicine__dose').value = MedicineDoseText[i].innerHTML;
				MedicineEditPopup.classList.add("open");
			});
		}

		// popuw window to add medicine
		MedicineAddButton.addEventListener('click', function(){
			MedicineAddPopup.classList.add("open");
		});

		let TreatmentDateText = document.querySelectorAll('.staff-block__treatment-date-item');

		// popup window to edit treatment date
		TreatmentDateEditButton.addEventListener('click', function(){ // open popup windows
			document.querySelector('.treatment-date__start').value = TreatmentDateText[0].innerHTML;
			document.querySelector('.treatment-date__end').value = TreatmentDateText[1].innerHTML;
			TreatmentDatePopup.classList.add("open");
		});

		// popup window to edit recommendation
		RecommendationEditButton.addEventListener('click', function(){ // open popup windows
			document.querySelector('.treatment-recommendation__edit').value = document.querySelector('.staff-block__treatment-recommendation').innerHTML;
			RecommendationPopup.classList.add("open");
		});

		// buttons to close popup windows
		const closeBtn = document.querySelectorAll('.popup-exercise__close-button');
		for(let i = 0; i < closeBtn.length; i++){
			closeBtn[i].addEventListener('click', function(){ // close popup windows
				MedicineEditPopup.classList.remove("open");
				MedicineAddPopup.classList.remove("open");
				TreatmentDatePopup.classList.remove("open");
				RecommendationPopup.classList.remove("open");
			});
		}

		window.addEventListener('keydown', (e) => { // close popup windows
		if(e.key == "Escape"){
			MedicineEditPopup.classList.remove("open");
			MedicineAddPopup.classList.remove("open");
			TreatmentDatePopup.classList.remove("open");
			RecommendationPopup.classList.remove("open");
		}
		});

	</script>
</body>
</html>