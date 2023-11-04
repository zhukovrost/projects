<?php
include "../templates/func.php";
include "../templates/settings.php";

if (empty($_GET["id"]) || !is_numeric($_GET["id"]))
    header("Location: coach.php");

$workout = new Control_Workout($conn, $_GET["id"]);
$cnt_apps = 0;
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<form method="post" class="workout-session">
<header class="workout-session__header">
    <a href="../index.php" class="header__item header__item--logo header__item--logo--workout">
        <img src="../img/logo.svg" alt="">
        <p>Training</p>
    </a>
    <!-- Progress of test(in percents) -->
    <section class="workout-session__progress">
        <!-- Progress line and count of percent -->
        <h2 class="workout-session__progress-title">Progress</h2>
        <div class="workout-session__progress-percents">
            <p class="workout-session__percents-number">0%</p>
            <div class="workout-session__finish-line"></div>
        </div>
    </section>
</header>

<main class="session-exercises">
    <div class="container">
        <swiper-container class="session-exercises__swiper" pagination="true" pagination-clickable="true" navigation="true" space-between="30" loop="true">
            <!-- for loop -->
            <?php for ($i = 0; $i < $workout->loops; $i++) { foreach ($workout->exercises as $exercise){?>
                <swiper-slide class="session-exercises__slide">
                    <?php
                    $cnt_apps += $exercise->approaches;
                    $exercise->print_control_exercise($conn, 1);
                    ?>
                </swiper-slide>
            <?php } } ?>
        </swiper-container>
    </div>
</main>

<footer class="workout-session-footer workout-session-footer--с">
    <div class="workout-session-footer-cover">
        <h1 class="workout-session-footer__title">Осталось:</h1>
        <h2 class="workout-session-footer__item"><span><?php echo count($workout->exercises); ?></span> упражнений(ия)</h2>
    </div>

    <a href="end_control_workout.php?id=<?php echo $_GET["id"]; ?>" class="button-text workout-session-footer__button">Завершить</a>

</footer>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
<script>
    // Button to see exercise info
    let infoExerciseButton = document.querySelectorAll('.exercise-item__info-btn');
    let closeInfoExerciseButton = document.querySelectorAll('.exercise-item__info-close');
    let infoBlock = document.querySelectorAll('.exercise-item__info-content');

    for(let i = 0; i < infoExerciseButton.length; i++){
        infoExerciseButton[i].addEventListener('click', function(){
            infoBlock[i].style.cssText = `top: -1%;`;
        });
    }
    for(let i = 0; i < closeInfoExerciseButton.length; i++){
        closeInfoExerciseButton[i].addEventListener('click', function(){
            infoBlock[i].style.cssText = `top: -101%;`;
        });
    }


    //Difficult
    let difficultCountArr = document.querySelectorAll('.exercise-item__difficult-number');
    let difficultBlockArr = document.querySelectorAll('.exercise-item__difficult');

    for(let i = 0; i < difficultCountArr.length; i++){
        difficultBlockArr[i].innerHTML = '';
        for(let j = 0; j < 5; j++){
            let newElem = document.createElement('div');
            newElem.classList.add('exercise-item__difficult-item');
            if(j > Number(difficultCountArr[i].innerHTML) - 1){
                newElem.classList.add('exercise-item__difficult-item--disabled');
            }
            difficultBlockArr[i].appendChild(newElem);
        }
    }



    // Progress
    let repetDoneButtons = document.querySelectorAll('.exercise-item__done');
    let exercisesLeft = document.querySelectorAll('.workout-session-footer__item span')[0];
    let allrepetsLeft = document.querySelectorAll('.workout-session-footer__item span')[1];
    let currentRepetsLeft = document.querySelectorAll('.exercise-item__repetitions-title span');
    let exerciseTitle = document.querySelectorAll('.exercise-item__repetitions-title');

    let progressLine = document.querySelector('.workout-session__finish-line');
    let progressPercents = document.querySelector('.workout-session__percents-number');

    let allRepetsNumber = parseInt(allrepetsLeft.innerHTML);



    let maximunExerciseCardHeight = 0;
    let exerciseCards = document.querySelectorAll('.exercise-item');
    for(let i = 0; i < exerciseCards.length; i++){
        maximunExerciseCardHeight = Math.max(maximunExerciseCardHeight, exerciseCards[i].clientHeight);
    }

    for(let i = 0; i < exerciseCards.length; i++){
        exerciseCards[i].style.cssText = `height: ${maximunExerciseCardHeight}px;`;
    }
</script>
</form>
</html>