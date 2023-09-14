<?php
include "../templates/func.php";
include "../templates/settings.php";

$user_data->set_program($conn);
$weekday = date("N") - 1;
$user_data->set_program($conn);
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>

    <main class="workouts-block">
        <div class="container">
            <?php if ($user_data->program->get_id() > 0){ ?>
            <!-- Day's workout swiper -->
            <swiper-container class="workouts-swiper" navigation="true">
                <swiper-slide class="workouts-slide">
                <!-- Slide -->
                <?php
                    $workout = new Workout($conn, $user_data->program->program[$weekday], $weekday);
                    if ($workout->holiday){
                        include "../templates/holiday.html";
                    }else{ $workout->set_muscles(); ?>
                        <!-- slide(no arrows) -->
                        <section class="workouts-card">
                            <!-- Title and button to add to favorite collection -->
                            <div class="workouts-card__header">
                                <h2 class="workouts-card__date"><?php echo date("d.m.Y"); ?></h2>
                                <button class="workouts-card__favorite-btn"><img src="../img/favorite.svg" alt=""></button>
                            </div>
                            <!-- Content of workout -->
                            <section class="workouts-card__content">
                                <!-- Exercises array -->
                                <section class="workouts-card__exercises-cover">
                                    <!-- Exercise items -->
                                    <?php $workout->print_exercises($conn); ?>
                                </section>
                                <!-- Info about day workout -->
                                <section class="workouts-card__info">
                                    <!-- Muscle groups -->
                                    <div class="workouts-card__muscle-groups">
                                        <div class="workouts-card__info-item">
                                            <p>Руки:</p>
                                            <span><?php echo $workout->muscles["arms"]; ?>%</span>
                                        </div>
                                        <div class="workouts-card__info-item">
                                            <p>Ноги:</p>
                                            <span><?php echo $workout->muscles["legs"]; ?>%</span>
                                        </div>
                                        <div class="workouts-card__info-item">
                                            <p>Грудь:</p>
                                            <span><?php echo $workout->muscles["chest"]; ?>%</span>
                                        </div>
                                        <div class="workouts-card__info-item">
                                            <p>Спина:</p>
                                            <span><?php echo $workout->muscles["back"]; ?>%</span>
                                        </div>
                                        <div class="workouts-card__info-item">
                                            <p>Пресс:</p>
                                            <span><?php echo $workout->muscles["press"]; ?>%</span>
                                        </div>
                                        <div class="workouts-card__info-item">
                                            <p>Кардио:</p>
                                            <span><?php echo $workout->muscles["cardio"]; ?>%</span>
                                        </div>
                                    </div>
                                    <!-- Decorative line -->
                                    <div class="workouts-card__info-line"></div>
                                    <!-- Exercise info -->
                                    <div class="workouts-card__repeats-info">
                                        <div class="workouts-card__info-item">
                                            <p>Упражнений:</p>
                                            <span><?php echo count($workout->exercises); ?></span>
                                        </div>
                                        <div class="workouts-card__info-item">
                                            <p>Кругов:</p>
                                            <span><?php echo $workout->loops; ?></span>
                                        </div>
                                    </div>
                                    <!-- Decorative line -->
                                    <div class="workouts-card__info-line"></div>
                                    <!-- Buttons edit and start -->
                                    <div class="workouts-card__buttons">
                                        <button class="button-img workouts-card__edit-button"><img src="../img/edit.svg" alt=""></button>
                                        <button class="button-text workouts-card__start-button">Начать</button>
                                    </div>
                                </section>
                            </section>
                        </section>
                    <?php } ?>
                </swiper-slide>
                <swiper-slide class="workouts-slide">
                    <?php
                    if ($weekday == 6){
                        $weekday = 0;
                    }else{
                        $weekday++;
                    }
                    $workout = new Workout($conn, $user_data->program->program[$weekday], $weekday);
                    if ($workout->holiday){
                        include "../templates/holiday.html";
                    }else{ $workout->set_muscles(); ?>
                    <section class="workouts-card">
                        <div class="workouts-card__header">
                            <h2 class="workouts-card__date"><?php echo date("d.m.Y", time() + 86400); ?></h2>
                            <button class="workouts-card__favorite-btn"><img src="../img/favorite.svg" alt=""></button>
                        </div>
                        <section class="workouts-card__content">
                            <section class="workouts-card__exercises-cover">
                                <?php $workout->print_exercises($conn); ?>
                            </section>
                            <?php $workout->print_workout_info(date("N"), 2); ?>
                        </section>
                    </section>
                    <?php } ?>
                </swiper-slide>
            </swiper-container>
            <?php } else { ?>
                <p>Нет тренировки</p>
            <?php } ?>
            <section class="workout-other">
                <!-- Friends' workouts -->
                <section class="friends-block">
                    <!-- Title and button to search friends -->
                    <div class="friends-block__header">
                        <h1 class="friends-block__header-title">Тренировки друзей</h1>
                        <a class="friends-block__header-button" href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends' workout swiper -->
                    <swiper-container class="friends-block__swiper" navigation="true">
                        <?php
                        $user_data->set_subscriptions($conn);
                        print_user_list($conn, $user_data->subscriptions);
                        ?>
                      </swiper-container>
                </section>
                <?php $user_data->print_workout_history($conn); ?>
                <!-- Buttons favorite workouts and my program -->
                <section class="workout-other__buttons">
                    <a class="button-text workout-other__button" href=""><p>Избранное</p> <img src="../img/favorite_white.svg" alt=""></a>
                    <a class="button-text workout-other__button" href="my_program.php"><p>Моя программа</p> <img src="../img/my_programm.svg" alt=""></a>
                </section>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html" ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
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


        // Info slide items' spans width
        let infoItemsSpans = document.querySelectorAll('.workouts-card__info-item span');
        let maxSpanWidth = 0;

        for(let i = 0; i < infoItemsSpans.length; i++){
            maxSpanWidth = Math.max(maxSpanWidth, infoItemsSpans[i].clientWidth);
        }

        for(let i = 0; i < infoItemsSpans.length; i++){
            infoItemsSpans[i].style.cssText = `width: ${maxSpanWidth}px;`;
            console.log(infoItemsSpans[i].clientWidth)
        }
    </script>
</body>
</html>