<?php
include "../templates/func.php";
include "../templates/settings.php";
$user_data->check_the_login();
$user_data->set_program($conn);
$user_data->program->set_workouts($conn);
$muscles = array(
    "arms" => 0,
    "legs" => 0,
    "press" => 0,
    "back" => 0,
    "chest" => 0,
    "cardio" => 0,
    "cnt" => 0
);
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>
    <main>
        <div class="container my_program">
            <section class="day_workouts">
                <section class="date">
                    <h1>Выберите дату:</h1>
                    <input type="week">
                </section>
                <swiper-container class="cover" navigation="true">
                    <?php for ($j = 0; $j < $user_data->program->reps; $j++){ # $j = number of week doing program ?>
                    <swiper-slide>
                        <?php for($i = 0; $i < 7; $i++){
                            $workout = $user_data->program->workouts[$i];
                            foreach ($workout->set_muscles() as $key=>$value){
                                $muscles[$key] += $value;
                            }
                        ?>
                        <section class="item">
                            <h2><?php echo get_day($i); ?></h2>
                            <div class="content">
                                <?php if ($workout->holiday){ ?>
                                    <div class="day_off">Выходной</div>
                                <?php }else{ ?>
                                <p>Руки: <span><?php echo $workout->muscles["arms"]; ?>%</span></p>
                                <p>Ноги: <span><?php echo $workout->muscles["legs"]; ?>%</span></p>
                                <p>Грудь: <span><?php echo $workout->muscles["chest"]; ?>%</span></p>
                                <p>Спина: <span><?php echo $workout->muscles["back"]; ?>%</span></p>
                                <p>Пресс: <span><?php echo $workout->muscles["press"]; ?>%</span></p>
                                <p>Кардио: <span><?php echo $workout->muscles["cardio"]; ?>%</span></p>
                                <div class="buttons">
                                    <button><img src="../img/more_white.svg" alt=""></button>
                                    <button><img src="../img/edit.svg" alt=""></button>
                                    <img src="../img/done.svg" alt="">
                                </div>
                                <?php } ?>
                            </div>
                        </section>
                        <?php } ?>
                    </swiper-slide>
                    <?php } ?>
                  </swiper-container>
            </section>
            <?php $diagram_muscles = json_encode(array($muscles['arms'], $muscles['legs'], $muscles['chest'], $muscles['back'], $muscles['press'], $muscles['cardio'])); ?>
            <section class="info">
                <section class="statistic">
                    <section class="muscle_groups">
                        <h2>Группы мышц</h2>
                        <canvas id="muscleGroupsChart"></canvas>
                    </section>
                    <section class="content">
                        <section class="all">
                            <p>Всего тренировок: <span><?php echo $user_data->program->count_workouts(); ?></span></p>
                            <p>Всего упражнений: <span><?php echo $user_data->program->count_exercises(); ?></span></p>
                        </section>
                        <section class="progress">
                            <div class="item">
                                <div>
                                    69 %
                                </div>
                                <h3>Выполнен(но)</h3>
                                <p>Тренировок: <span>23</span></p>
                                <p>Упражнений: <span>23</span></p>
                            </div>
                            <div class="item">
                                <div>
                                    31 %
                                </div>
                                <h3>Осталось(ся)</h3>
                                <p>Тренировок: <span>23</span></p>
                                <p>Упражнений: <span>23</span></p>
                            </div>
                        </section>
                    </section>
                </section>
                <section class="friends">
                    <h2>Программы друзей</h2>
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
                </section>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html" ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Workout items
        let workoutItemArr = document.querySelectorAll('.my_program .cover .item .content');

        let maxWorkoutItemHeight = 0;

        for(let i = 0; i < workoutItemArr.length; i++){
            if(workoutItemArr[i].clientHeight > maxWorkoutItemHeight){
                maxWorkoutItemHeight = workoutItemArr[i].clientHeight;
            }
        }

        for(let i = 0; i < workoutItemArr.length; i++){
            workoutItemArr[i].style.cssText = `height: ${maxWorkoutItemHeight}px;`;
        }



        // Muscle groups chart
        const ctx2 = document.getElementById('muscleGroupsChart');

        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Руки', 'Ноги', 'Грудь', 'Спина', 'Пресс', 'Кардио'],
                datasets: [{
                    label: 'Количество упражнений',
                    data: <?php echo $diagram_muscles; ?>,
                    borderWidth: 1,
                    color: '#000000',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        align: 'center',
                        labels: {
                            font: {
                                family: 'Open Sans',
                                size: 20,
                            },
                            color: '#000000000',
                        },
                    },
                title: {
                    display: false,
                }
                }
            },
        });

        // Height of friends block
        let friendsBlock = document.querySelector('.friends');
        friendsBlock.style.cssText = `height: ${document.querySelector('.my_program .info .statistic').clientHeight}px;`;
    </script>
</body>
</html>