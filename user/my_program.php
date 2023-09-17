<?php
include "../templates/func.php";
include "../templates/settings.php";
$user_data->check_the_login();
if (!$user_data->set_program($conn)){
    header("Location: c_program_info.php");
}
$user_data->program->set_workouts($conn);
$user_data->program->set_additional_data($conn, $user_data->get_id());

$muscles = array(
    "arms" => 0,
    "legs" => 0,
    "press" => 0,
    "back" => 0,
    "chest" => 0,
    "cardio" => 0,
    "cnt" => 0
);

$cnt = 0;
$cnt_workouts_done = 0;
$cnt_workouts_left = 0;
$flag = false;
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.html" ?>
    <main class="my-program">
        <div class="container">
            <section class="day-workouts">
                <section class="day-workouts__date">
                    <h1 class="day-workouts__date-title">Выберите дату:</h1>
                    <input class="day-workouts__date-input" type="week">
                </section>
                <swiper-container class="day-workouts__swiper" navigation="true">
                    <?php for ($j = time(); $j < $user_data->program->date_start + $user_data->program->weeks * 604800; $j += 604800){
                        $days = 7;
                        if ($j + 604800 >= $user_data->program->date_start + $user_data->program->weeks * 604800){
                            $flag = true;
                        }
                        if ($flag){
                            $days = $cnt;
                        }
                        ?>
                    <swiper-slide class="day-workouts__slide">
                        <?php
                        for($i = 0; $i < $days; $i++){
                            $date = $j + $i * 86400;
                            if ($user_data->program->date_start <= $date){
                                $workout = $user_data->program->workouts[$i];
                                foreach ($workout->set_muscles() as $key=>$value){
                                    $muscles[$key] += $value;
                                }
                                if ($workout->print_workout_info_block($i, 1, $user_data->get_id())){
                                    if ($workout->is_done($conn, $user_data->get_id(), $j + $i * 86400)){
                                        $cnt_workouts_done++;
                                    }else{
                                        $cnt_workouts_left++;
                                    }
                                }
                            }else{
                                echo render(array("{{ day }}" => get_day($i)), "../templates/out_of_workout.html");
                                $cnt++;
                            }
                        }
                        if ($flag){
                            for ($i = $cnt; $i < 7; $i++){
                                echo render(array("{{ day }}" => get_day($i)), "../templates/out_of_workout.html");
                            }
                        }
                        ?>
                    </swiper-slide>
                    <?php } ?>
                </swiper-container>
            </section>
            <?php $diagram_muscles = json_encode(array($muscles['arms'], $muscles['legs'], $muscles['chest'], $muscles['back'], $muscles['press'], $muscles['cardio'])); ?>
            <section class="my-program__info">
                <section class="my-program__statistic">
                    <section class="my-program__muscle-groups">
                        <h2 class="my-program__muscle-groups-title">Группы мышц</h2>
                        <canvas id="muscleGroupsChart"></canvas>
                    </section>
                    <section class="my-program__statistic-content">
                        <section class="my-program__statistic-all">
                            <p class="my-program__statistic-all-item">Всего тренировок: <span><?php echo $user_data->program->count_workouts(); ?></span></p>
                            <p class="my-program__statistic-all-item">Всего упражнений: <span><?php echo $user_data->program->count_exercises(); ?></span></p>
                        </section>
                        <section class="my-program__progress">
                            <div class="my-program__progress-item">
                                <div class="my-program__progress-percent">
                                    69 %
                                </div>
                                <h3 class="my-program__progress-item-title">Выполнен(но)</h3>
                                <p class="my-program__progress-item-text">Тренировок: <span>23</span></p>
                                <p class="my-program__progress-item-text">Упражнений: <span>23</span></p>
                            </div>
                            <div class="my-program__progress-item">
                                <div class="my-program__progress-percent">
                                    31 %
                                </div>
                                <h3 class="my-program__progress-item-title">Осталось(ся)</h3>
                                <p class="my-program__progress-item-text">Тренировок: <span>23</span></p>
                                <p class="my-program__progress-item-text">Упражнений: <span>23</span></p>
                            </div>
                        </section>
                    </section>
                </section>
                <section class="friends-block">
                    <!-- Title and button to search friends -->
                    <div class="friends-block__header">
                        <h1 class="friends-block__header-title">Тренировки друзей</h1>
                        <a class="friends-block__header-button" href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends' workout swiper -->
                    <section class="friends-block__cover" navigation="true">
                        <?php
                        $user_data->set_subscriptions($conn);
                        print_user_list($conn, $user_data->subscriptions);
                        ?>
                    </section>
                </section>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html" ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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