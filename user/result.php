<?php
include '../templates/func.php';
include '../templates/settings.php';

$user_data->check_the_login();

if (isset($_GET['id'])){
  $id = $_GET['id'];
}else{
  header("Location: my_tests.php");
}

$data = get_tests_to_users_data($conn, $id);
if ($data['user'] != $user_data->get_id()){
  header("Location: my_tests.php");
}
$test_id = $data['test'];

$result_data = check_the_test($conn, $id, false, true);
$diagram_data = json_encode([$result_data['wrong'], $result_data['not_answered'], $result_data['correct'], $result_data['verifying']])
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
    <!-- Main header -->
    <?php include "../templates/header.html"; ?>

    <main>
        <div class="container">
            <!-- Title -->
            <h1 class="result_title">Your result</h1>
            <section class="result_content">
                <!-- the results block of the passed test -->
                <section class="result">
                    <!-- Pie diagram -->
                    <div class="diagram">
                        <canvas id="myPie"></canvas>
                    </div>
                    <div class="statistics">
                        <!-- smile image(happy, sad or medium) -->
                        <div class="image">
                            <img src="" alt="">
                            <p></p>
                        </div>
                        <!-- results -->
                        <div>
                            <div class="content">
                                <p>Time spent: <span class="time">0</span></p>
                                <p>Right answers: <span><?php echo $result_data['correct'].'/'.$result_data['all_questions'];  ?></span></p>
                                <p>Total score: <span class="score"><?php echo $result_data['user_scores'].'/'.$result_data['all_scores']; ?></span></p>
                                <p>Mark: <span><?php echo $result_data['mark'];  ?></span></p>
                            </div>
                            <a href="my_tests.php" class="new_attempt">GO BACK</a>
                        </div>
                    </div>
                </section>

              <?php $test = new Test();
              $test->set_test_data($conn, $test_id);
              $test->print_it($conn, true, $id); ?>

                <!-- Link to theory page -->
                <section class="theory">
                    <img src="../img/theory_icon.svg" alt="">
                    <!-- link -->
                    <div>
                        <p>Study the theory to improve your result!</p>
                        <a href="theory.php">Go to theory</a>
                    </div>
                </section>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html"; $conn->close(); ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Timer
        for(let i = 0; i < localStorage.getItem(`StartButtonsLength`); i++){
            if(localStorage.getItem(`test_time${i}`) > 0){
                let time = localStorage.getItem(`test_time${i}`);

                time--;
                let AllTime = localStorage.getItem(`AllTestTime${i}`);
                localStorage.setItem(`test_time${i}`, time);
                localStorage.setItem(`SpendTestTime${i}`, (AllTime - time));

                let IntervalTimer = setInterval(UpdateTime, 1000, i);
            }
            else{
                localStorage.setItem(`test_time${i}`, -1);
                localStorage.setItem(`SpendTestTime${i}`, (0));
            }
        }

        function UpdateTime(i){
            if(localStorage.getItem(`test_time${i}`) > 0){
                let time = localStorage.getItem(`test_time${i}`);

                time--;
                console.log(1)
                let AllTime = localStorage.getItem(`AllTestTime${i}`);
                localStorage.setItem(`SpendTestTime${i}`, (AllTime - time));
                localStorage.setItem(`test_time${i}`, time);
            }
        }

        // ===Pie diagramm===
        const ctx = document.getElementById('myPie');

        const data = {
            labels: [
                'Wrong',
                'Not answered',
                'Correct',
                'Verifying'
            ],
            datasets: [{
                label: 'Count of tests',
                data: <?php echo $diagram_data; ?>,
                backgroundColor: [
                'rgba(15, 38, 84, 1)',
                'rgba(10, 70, 224, 1)',
                'rgba(154, 208, 245, 1)',
                'rgba(10, 208, 224, 1)'
                ],
                hoverOffset: 4
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 21
                            }
                        }
                    },
                },
            },
        });


        // Result smile
        let resultImage = document.querySelector('.result_content .statistics .image img');
        let resultText = document.querySelector('.result_content .statistics .image p');
        let score = document.querySelector('.result_content .statistics .score');
        let time = document.querySelector('.result_content .statistics .time');

        time.innerHTML = localStorage.getItem(`SpendTestTime${localStorage.getItem('timer_id')}`) + ' sec';

        let currentScore = parseFloat(score.innerHTML.split('/')[0]);
        let totalScore = parseFloat(score.innerHTML.split('/')[1]);


        if(currentScore / totalScore >= 0.8){
            resultImage.src = "../img/5_smile.svg";
            resultText.innerHTML = "Great job!";
        }
        else if(currentScore / totalScore < 0.8 && currentScore / totalScore >= 0.6){
            resultImage.src = "../img/4_smile.svg";
            resultText.innerHTML = "Good job!";
        }
        else if(currentScore / totalScore < 0.6 && currentScore / totalScore >= 0.4){
            resultImage.src = "../img/3_smile.svg";
            resultText.innerHTML = "It's fine!";
        }
        else{
            resultImage.src = "../img/2_smile.svg";
            resultText.innerHTML = "It's bad!";
        }
    </script>
    <script src="../templates/format.js"></script>
</body>
</html>