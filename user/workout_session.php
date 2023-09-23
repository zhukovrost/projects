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
        <section class="workout-session__navigation">
            <!-- Progress of test(in percents) -->
            <section class="workout-session__progress">
                <!-- Progress line and count of percent -->
                <div class="workout-session__progress-bar">
                    <h2 class="workout-session__progress-title">Progress</h2>
                    <div class="workout-session__progress-percents">
                        <p class="workout-session__percents-number">0%</p>
                        <div class="workout-session__finish-line"></div>
                    </div>
                </div>
                <!-- Navigation of test -->
                <nav class="workout-session__navigation">

                </nav>
            </section>
            <!-- Timer(rest time) -->
            <div class="workout-session__time">
                00:00
            </div>
        </section>
    </header>
    
    <main class="session-exercises">
        <swiper-container class="session-exercises__swiper" navigation="true">
            <!-- for loop -->
            <?php for ($i = 0; $i < $workout->loops; $i++) { foreach ($workout->exercises as $exercise){?>
            <swiper-slide class="session-exercises__slide">
                <?php
                $cnt_apps += $exercise->approaches;
                $exercise->print_it($conn);
                # echo render($replaces, "../templates/user_exercise.html");
                ?>
            </swiper-slide>
            <?php } } ?>
        </swiper-container>
    </main>

    <footer class="workout-session-footer">
        <h1 class="workout-session-footer__title">Осталось:</h1>
        <h2 class="workout-session-footer__item"><span><?php echo count($workout->exercises); ?></span> упражнений</h2>
        <h2 class="workout-session-footer__item"><span><?php echo $cnt_apps; ?></span> подходов</h2>
        <a href="end_workout.php" class="button-text workout-session-footer__button">Завершить</a>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script>
        // Navigation
        let questionsArr = document.querySelectorAll('.exercise-item');

        
        const testNavigation = document.querySelector('.workout-session__navigation');
        const progressBar = document.querySelector('.workout-session__finish-line');
        let percents = document.querySelector('.workout-session__percents-number');

        for(let i = 0; i < questionsArr.length; i++){
            let newElem = document.createElement('button');
            newElem.classList.add('button-text');
            newElem.classList.add('workout-session__navigation-button');
            newElem.innerHTML = `${i+1}`;
            testNavigation.appendChild(newElem);
        }

        const navigationButtons = document.querySelectorAll('.workout-session__navigation-button');

        for(let i = 0; i < navigationButtons.length; i++){
            navigationButtons[i].addEventListener('click', function(){
                questionsArr[i].scrollIntoView({
                    behavior: "smooth", block: "center", inline: "start";
                });
            });
        }

        // ===============================================

        // Progress
        let finishQuestions = [];
        for(let i = 0; i < questionsArr.length; i++){
            finishQuestions[i] = {
                number: i,
                flag: false
            }
        }

        for(let i = 0; i < questionsArr.length; i++){
            let inputBlocksArr = questionsArr[i].children[1].children[2].children;
            let CountFinishInputs = new Array(questionsArr.length).fill(0);

            for(let j = 0; j < inputBlocksArr.length; j++){
                if(inputBlocksArr[j].tagName != "IMG"){
                let inputItem = inputBlocksArr[j].children[0];
                let previousValue = ""

                if(inputItem.type == 'checkbox'){
                    inputItem.addEventListener('change', function(){
                    if (inputItem.checked){
                        CountFinishInputs[i] += 1;

                        finishQuestions[i].flag = true;

                        let finishCount = 0
                        for(let q = 0; q < finishQuestions.length; q++){
                            if(finishQuestions[q].flag == true){
                                finishCount++;

                                navigationButtons[q].style.cssText = `background-color: #004fe3; color: #ffffff;`;
                            }
                        }
                        currentWidth = Math.trunc((finishCount / finishQuestions.length) * 100);
                        progressBar.style.cssText = `width: ${currentWidth}%`;
                        percents.innerHTML = `${currentWidth} %`;

                        if(currentWidth == 100){
                            document.querySelector('.curtest_header .progress_bar p').style.color = "#ffffff";
                        }
                    }
                    else if(inputItem.checked == false){
                        CountFinishInputs[i] -= 1;
                        if (CountFinishInputs[i] == 0){

                        finishQuestions[i].flag = false;

                        let finishCount = 0
                        for(let q = 0; q < finishQuestions.length; q++){
                            if(finishQuestions[q].flag == true){
                                finishCount++;

                                navigationButtons[q].style.cssText = `background-color: #004fe3; color: #ffffff;`;
                            }
                            else{
                                navigationButtons[q].style.cssText = `background-color: #54d7ff; color: #000000;`;
                            }
                        }
                        currentWidth = Math.trunc((finishCount / finishQuestions.length) * 100);
                        progressBar.style.cssText = `width: ${currentWidth}%`;
                        percents.innerHTML = `${currentWidth} %`;

                        if(currentWidth == 100){
                            document.querySelector('.curtest_header .progress_bar p').style.color = "#ffffff";
                        }
                        }
                    }
                    })
                }

                inputItem.oninput =  function(){

                    if (inputItem.value != ''){
                    if (inputItem.value.length == 1 && previousValue.length != 2){
                        CountFinishInputs[i] += 1;
                    }
                    
                    
                    finishQuestions[i].flag = true;

                    let finishCount = 0
                    for(let q = 0; q < finishQuestions.length; q++){
                        if(finishQuestions[q].flag == true){
                            finishCount++;

                            navigationButtons[q].style.cssText = `background-color: #004fe3; color: #ffffff;`;
                        }
                    }
                    currentWidth = Math.trunc((finishCount / finishQuestions.length) * 100);
                    progressBar.style.cssText = `width: ${currentWidth}%`;
                    percents.innerHTML = `${currentWidth} %`;

                    if(currentWidth == 100){
                        document.querySelector('.curtest_header .progress_bar p').style.color = "#ffffff";
                    }

                    previousValue = inputItem.value;
                    }
                    else{
                    CountFinishInputs[i] -= 1;
                    if (CountFinishInputs[i] == 0){

                        finishQuestions[i].flag = false;

                        let finishCount = 0
                        for(let q = 0; q < finishQuestions.length; q++){
                            if(finishQuestions[q].flag == true){
                                finishCount++;

                                navigationButtons[q].style.cssText = `background-color: #004fe3; color: #ffffff;`;
                            }
                            else{
                                navigationButtons[q].style.cssText = `background-color: #54d7ff; color: #000000;`;
                            }
                        }
                        currentWidth = Math.trunc((finishCount / finishQuestions.length) * 100);
                        progressBar.style.cssText = `width: ${currentWidth}%`;
                        percents.innerHTML = `${currentWidth} %`;

                        if(currentWidth == 100){
                            document.querySelector('.curtest_header .progress_bar p').style.color = "#ffffff";
                        }
                    }
                    }

                };
                }
                
            }
        }
    </script>
</body>
</html>