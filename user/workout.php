<?php
include "../templates/func.php";
include "../templates/settings.php";

$user_data->set_program($conn);
$weekday = date("N") - 1;
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>

    <main>
        <div class="container workouts">
            <!-- Day's workout swiper -->
            <swiper-container navigation="true">
                <swiper-slide>
                <!-- Slide -->
                <?php
                    $workout = new Workout($conn, $user_data->program->program[$weekday], $weekday);
                    if ($workout->holiday){
                        include "../templates/holiday.html";
                    }else{ $workout->set_muscles(); ?>
                        <!-- slide(no arrows) -->
                        <section class="slide">
                            <!-- Title and button to add to favorite collection -->
                            <div class="title">
                                <h2><?php echo date("d.m.Y"); ?></h2>
                                <button><img src="../img/favorite.svg" alt=""></button>
                            </div>
                            <!-- Content of workout -->
                            <section class="content">
                                <!-- Exercises array -->
                                <section class="exercise_cover">
                                    <!-- Exercise items -->
                                    <?php $workout->print_exercises($conn); ?>
                                </section>
                                <!-- Info about day workout -->
                                <section class="workout_info">
                                    <!-- Muscle groups -->
                                    <div class="muscle_groups">
                                        <p>Руки: <span><?php echo $workout->muscles["arms"]; ?>%</span></p>
                                        <p>Ноги: <span><?php echo $workout->muscles["legs"]; ?>%</span></p>
                                        <p>Грудь: <span><?php echo $workout->muscles["chest"]; ?>%</span></p>
                                        <p>Спина: <span><?php echo $workout->muscles["back"]; ?>%</span></p>
                                        <p>Пресс: <span><?php echo $workout->muscles["press"]; ?>%</span></p>
                                        <p>Кардио: <span><?php echo $workout->muscles["cardio"]; ?>%</span></p>
                                    </div>
                                    <!-- Decorative line -->
                                    <div class="line"></div>
                                    <!-- Exercise info -->
                                    <div class="exercise">
                                        <p>Упражнений: <span><?php echo count($workout->exercises); ?></span></p>
                                        <p>Кругов: <span><?php echo $workout->loops; ?></span></p>
                                    </div>
                                    <!-- Decorative line -->
                                    <div class="line"></div>
                                    <!-- Buttons edit and start -->
                                    <div class="buttons">
                                        <button><img src="../img/edit.svg" alt=""></button>
                                        <button>Начать</button>
                                    </div>
                                </section>
                            </section>
                        </section>
                    <?php } ?>
                </swiper-slide>
                <swiper-slide>
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
                    <section class="slide">
                        <div class="title">
                            <h2><?php echo date("d.m.Y", time() + 86400); ?></h2>
                            <button><img src="../img/favorite.svg" alt=""></button>
                        </div>
                        <section class="content">
                            <section class="exercise_cover">
                                <?php $workout->print_exercises($conn); ?>
                            </section>
                            <div class="workout_info">
                                <div class="muscle_groups">
                                    <p>Руки: <span><?php echo $workout->muscles["arms"]; ?>%</span></p>
                                    <p>Ноги: <span><?php echo $workout->muscles["legs"]; ?>%</span></p>
                                    <p>Грудь: <span><?php echo $workout->muscles["chest"]; ?>%</span></p>
                                    <p>Спина: <span><?php echo $workout->muscles["back"]; ?>%</span></p>
                                    <p>Пресс: <span><?php echo $workout->muscles["press"]; ?>%</span></p>
                                    <p>Кардио: <span><?php echo $workout->muscles["cardio"]; ?>%</span></p>
                                </div>
                                <div class="line"></div>
                                <div class="exercise">
                                    <p>Упражнений: <span><?php echo count($workout->exercises); ?></span></p>
                                    <p>Кругов: <span><?php echo $workout->loops; ?></span></p>
                                </div>
                                <div class="line"></div>
                                <div class="buttons">
                                    <button><img src="../img/edit.svg" alt=""></button>
                                    <button>Начать</button>
                                </div>
                            </div>
                        </section>
                    </section>
                    <?php } ?>
                </swiper-slide>
            </swiper-container>
            <section class="other">
                <!-- Friends' workouts -->
                <section class="friends">
                    <!-- Title and button to search friends -->
                    <div class="title">
                        <h1>Тренировки друзей</h1>
                        <a href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends' workout swiper -->
                    <swiper-container class="content swiper_friends" navigation="true">
                        <!-- slide -->
                        <swiper-slide>
                            <!-- friend item -->
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                        </swiper-slide>
                      </swiper-container>
                </section>
                <!-- last trainings -->
                <section class="last_trainings">
                    <!-- Title -->
                    <h1>Последние тренировки</h1>
                    <!-- Trainings content -->
                    <div class="content">
                        <!-- Item -->
                        <section class="item">
                            <!-- Left part of last exercise item -->
                            <div class="left">
                                <!-- Time of training -->
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <!-- Exercise count of training -->
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <!-- Right part of last exercise item -->
                            <div class="right">
                                <!-- Muscle groups count of training -->
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <!-- Button 'Подробнее' for more info about exercise -->
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
                <!-- Buttons favorite workouts and my program -->
                <section class="buttons">
                    <a href="">Избранное <img src="../img/favorite_white.svg" alt=""></a>
                    <a href="my_program.php">Моя программа <img src="../img/my_programm.svg" alt=""></a>
                </section>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html" ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script>
        // Button to see exercise info
        let infoExerciseButton = document.querySelectorAll('.workouts .slide .exercise_item .info');
        let closeInfoExerciseButton = document.querySelectorAll('.workouts .slide .exercise_item .info_close');
        let infoBlock = document.querySelectorAll('.workouts .slide .exercise_item .info_block');
        console.log(infoExerciseButton)

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


    </script>
</body>
</html>