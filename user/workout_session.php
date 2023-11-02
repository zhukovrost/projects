<?php
include "../templates/func.php";
include "../templates/settings.php";

$weekday = date("N") - 1;
$user_data->set_program($conn);
$workout_id = $user_data->program->program[$weekday];
$workout = new Workout($conn, $workout_id, $weekday);
$cnt_apps = 0;
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body class="workout-session">
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
            <div class="session-exercises__info-cover">
                <section class="session-exercises__info">
                    <div class="session-exercises__help">
                        <p class="session-exercises__help-item session-exercises__help-item--green">время на подход</p>
                        <p class="session-exercises__help-item"> | </p>
                        <p class="session-exercises__help-item session-exercises__help-item--white">время на отдых</p>
                    </div>
                    <!-- Timer -->
                    <div class="workout-session__time">
                        00:00
                    </div>
                </section>
            </div>
            <swiper-container class="session-exercises__swiper" pagination="true" pagination-clickable="true" navigation="true" space-between="30" loop="true">
                <!-- for loop -->
                <?php for ($i = 0; $i < $workout->loops; $i++) { foreach ($workout->exercises as $exercise){?>
                <swiper-slide class="session-exercises__slide">
                    <?php
                    $cnt_apps += $exercise->approaches;
                    $exercise->print_it($conn, false, false, false, true);
                    # echo render($replaces, "../templates/user_exercise.html");
                    ?>
                </swiper-slide>
                <?php } } ?>
            </swiper-container>
        </div>
    </main>

    <footer class="workout-session-footer">
        <h1 class="workout-session-footer__title">Осталось:</h1>
        <h2 class="workout-session-footer__item"><span><?php echo count($workout->exercises); ?></span> упражнений</h2>
        <h2 class="workout-session-footer__item"><span><?php echo $cnt_apps; ?></span> подходов</h2>
        <form method="post">
            <input class="workout-session-footer__input" type="hidden" value="0">
            <a href="end_workout.php" class="button-text workout-session-footer__button">Завершить</a>
        </form>
        
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

        

        // Timer
        let TimeForWork = localStorage.getItem('TimeForWork') * 60;
        let TimeForRest = localStorage.getItem('TimeForRest') * 60;
        let currentWorkTime = 0;
        let currentRestTime = 0;
        const timer = document.querySelector('.workout-session__time');
        let FinsishButton = document.querySelector('.workout-session-footer__button');
        let time = 0;

        let workoutSessionInputTime = document.querySelector('.workout-session-footer__input');

        if(localStorage.getItem(`SpendWorkoutTime`) && localStorage.getItem(`SpendWorkoutTime`) != -1){
            time = localStorage.getItem(`SpendWorkoutTime`);
            workoutSessionInputTime.value = time;

            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            if (seconds < 10){
                seconds = '0' + seconds;
            }
            if (minutes < 10){
                minutes = '0' + minutes;
            }
            timer.innerHTML = `${minutes}:${seconds}`;

            if (localStorage.getItem(`Period`) == 'Rest'){
                timer.style.cssText = 'background-color: rgb(1, 221, 34); box-shadow: 0px 3px 0px rgba(0, 94, 13, 1);';
            }
            else{
                timer.style.cssText = 'color: #ffffff; background-color: rgba(0, 94, 13, 1); box-shadow: 0px 3px 0px rgb(1, 221, 34);';
            }
        }
        else{
            localStorage.setItem(`SpendWorkoutTime`, 0);
            localStorage.setItem(`Period`, 'Work');
        }

        if(TimeForWork != -1 && TimeForRest != -1){
            let IntervalTimer = setInterval(UpdateTime, 1000);
            
            time++;
            workoutSessionInputTime.value = time;
            localStorage.setItem(`SpendWorkoutTime`, time);

            FinsishButton.addEventListener('click', function(){
                clearInterval(IntervalTimer);
                localStorage.setItem("TimeForWork", -1);
                localStorage.setItem("TimeForRest", -1);
                time = 0;
                localStorage.setItem(`SpendWorkoutTime`, -1);
            });
        }


        
        function UpdateTime(){
            let minutes = Math.floor(time / 60);
            let seconds = time % 60;
            if (seconds < 10){
                seconds = '0' + seconds;
            }
            if (minutes < 10){
                minutes = '0' + minutes;
            }

            timer.innerHTML = `${minutes}:${seconds}`;

            time++;
            workoutSessionInputTime.value = time;
            localStorage.setItem(`SpendWorkoutTime`, time);

            if (time - (Math.floor(time / (TimeForWork + TimeForRest)) * (TimeForWork + TimeForRest)) == TimeForWork){
                timer.style.cssText = 'background-color: rgb(1, 221, 34); box-shadow: 0px 3px 0px rgba(0, 94, 13, 1);';
                localStorage.setItem(`Period`, 'Rest');
            }
            else if (time % (TimeForWork + TimeForRest) == 0){
                timer.style.cssText = 'color: #ffffff; background-color: rgba(0, 94, 13, 1); box-shadow: 0px 3px 0px rgb(1, 221, 34);';
                localStorage.setItem(`Period`, 'Work');
            }
            
        }

    </script>
</body>
</html>