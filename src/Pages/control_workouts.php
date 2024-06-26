<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями


if ($user_data->get_status() == "doctor" || empty($_GET["user"]) || !is_numeric($_GET["user"])) // Check if the user is a doctor or if the user ID is missing or invalid
    header("Location: coach.php"); // Redirect

if (isset($_POST['featured'])) // Handle a POST request to change the 'featured' status
    $user_data->change_featured($conn, $_POST['featured']);

$user = new User($conn, $_GET["user"]); // Create a new User object based on the provided user ID from the GET parameter

// get not-done and done workouts for the user
$not_done_workouts = $user->get_control_workouts($conn, 0, NULL);
$done_workouts = $user->get_control_workouts($conn, 1, NULL);
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body class="control-workouts-page"> 
    <?php include BASE_PATH . "/templates/header.php"; // print header template ?>

	<main class="workouts-block">
        <div class="container">
                <?php
                if (count($not_done_workouts) > 0){ // Check if there are pending workouts
                    $workout = $not_done_workouts[0]; // Retrieve the first pending workout
                    if ($workout->is_holiday()){ // Check if it's a holiday
                        include BASE_PATH . "/templates/holiday.html"; // Include holiday template
                    }else{ $workout->set_muscles(); // Set muscles for the workout ?>
                    <!-- Day's workout swiper -->
                    <section class="workouts-swiper">
                        <!-- Slide -->
                        <!-- slide(no arrows) -->
                        <section class="workouts-card">
                            <!-- Title and button to add to favorite collection -->
                            <div class="workouts-card__header">
                                <h2 class="workouts-card__date"><?php echo $workout->get_date(); // print date of workout ?></h2>
                                <!-- <button class="workouts-card__favorite-btn"><img src="../img/favorite.svg" alt=""></button> -->
                            </div>
                            <!-- Content of workout -->
                            <section class="workouts-card__content">
                                <!-- Exercises array -->
                                <form method="post" class="workouts-card__exercises-cover">
                                    <!-- Exercise items -->
                                    <?php $workout->print_control_exercises($conn, $user_data); // print exercises of workout ?>
                                </form>
                                <!-- Info about day workout -->
                                <?php $workout->print_control_workout_info($conn); // print workout info ?>
                            </section>
                        </section>
                    </section>
                    <?php
                    }
                }else { ?>
                    <div class="workouts-card__no-program">
                        <p class="workouts-card__no-program-title">Нет тренировки</p>
                    </div>
                <?php } ?>

            <section class="workout-other coach-other">
			    <section class="last-trainings">
					<h1 class="last-trainings__title">Последние тренировки</h1>
					<div class="last-trainings__content">
                        <?php if (count($done_workouts) != 0){
                            $reversedDoneWorkouts = array_reverse($done_workouts); // reverse array of done workouts
                            foreach ($reversedDoneWorkouts as $done_workout) { $done_workout->set_muscles(); // print last trainings list ?>
						    <!-- Item -->
                            <section class="last-trainings__card">
                                <!-- Left part of last exercise item -->
                                <div class="last-trainings__card-left">
                                    <!-- Time of training -->
                                    <div class="last-trainings__item">
                                        <img class="last-trainings__item-img" src="../../assets/img/time.svg" alt="">
                                        <p class="last-trainings__item-text"><span><?php echo $done_workout->get_date(); // print date ?></span></p>
                                    </div>
                                    <!-- Exercise count of training -->
                                    <div class="last-trainings__item">
                                        <img class="last-trainings__item-img" src="../../assets/img/cards.svg" alt="">
                                        <p class="last-trainings__item-text"><span><?php echo count($done_workout->get_exercises()) // print number of exercises ?></span> упражнений</p>
                                    </div>
                                </div>
                                <!-- Right part of last exercise item -->
                                <div class="last-trainings__card-right">
                                    <!-- Muscle groups count of training -->
                                    <div class="last-trainings__item">
                                    <img class="last-trainings__item-img" src="../../assets/img/cards.svg" alt="">
                                    <p class="last-trainings__item-text"><span><?php echo $done_workout->get_groups_amount(); // print number of muscle groups ?></span> группы мышц</p>
                                    </div>
                                    <!-- Button 'Подробнее' for more info about exercise -->
                                    <div class="last-trainings__item">
                                    <a class="button-text last-trainings__item-button" href="last_control_workout.php?id=<?php echo $done_workout->get_id(); // more info link ?>">Подробнее <img src="../../assets/img/other.svg" alt=""></a>
                                    </div>
                                </div>
                            </section>
						<?php }
                        } else { ?>
                            <p class="last-trainings__no-workout">Нет тренировок</p>
                        <?php } ?>
					</div>
				</section>
                <!-- Buttons favorite workouts and my program -->
                <section class="workout-other__buttons">
                    <?php if ($user_data->get_status() == "coach"){ // if status == coach, print new workout button ?>
                        <a class="button-text workout-other__button" href="c_control_workout.php?for=<?php echo $user->get_id(); ?>"><p>Новая</p> <img src="../../assets/img/my_programm.svg" alt=""></a>
                    <?php } ?>
                    </section>
            </section>
        </div>
    </main>
	
    <?php include BASE_PATH . "/templates/footer.html"; // print footer template ?>

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script>
        // check type of profile for start button style
        if(localStorage.getItem('profileType') && localStorage.getItem('profileType') != 'Тренер'){
            document.querySelector('.control-workouts-page .day-workouts__card-button--start').style.cssText = 'display: none;';
        }

        // Button to see exercise info
        let infoExerciseButton = document.querySelectorAll('.exercise-item__info-btn');
        let closeInfoExerciseButton = document.querySelectorAll('.exercise-item__info-close');
        let infoBlock = document.querySelectorAll('.exercise-item__info-content');

        for(let i = 0; i < infoExerciseButton.length; i++){
            infoExerciseButton[i].addEventListener('click', function(){ // open exercise's info
                infoBlock[i].style.cssText = `top: -1%;`;
            });
        }
        for(let i = 0; i < closeInfoExerciseButton.length; i++){
            closeInfoExerciseButton[i].addEventListener('click', function(){// close exercise's info
                infoBlock[i].style.cssText = `top: -101%;`;
            });
        }


        // Info slide items' spans width
        let infoItemsSpans = document.querySelectorAll('.workouts-card__item span');
        let maxSpanWidth = 0;

        for(let i = 0; i < infoItemsSpans.length; i++){
            maxSpanWidth = Math.max(maxSpanWidth, infoItemsSpans[i].clientWidth); // find maximun span height
        }

        for(let i = 0; i < infoItemsSpans.length; i++){
            infoItemsSpans[i].style.cssText = `width: ${maxSpanWidth}px;`; // set maximun span height
        }
    </script>
</body>
</html>