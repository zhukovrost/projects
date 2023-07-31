<?php
include '../templates/func.php';
include '../templates/settings.php';

check_the_login($user_data, "../");
if (isset($_GET['test_id'])){
  $test_id = $_GET['test_id'];
}else{
  header("Location: my_tests.php");
}

$check_select_sql = "SELECT id, mark, duration FROM tests_to_users WHERE test=$test_id AND user=".$user_data['id'];
if ($check_select_result = $conn->query($check_select_sql)){
  if ($check_select_result->num_rows >= 1){
    foreach ($check_select_result as $item){
      $mark = $item['mark'];
      $duration = $item['duration'];
      $id = $item['id'];
    }
  }else{
    header("Location: my_tests.php?access_error=1");
  }
}else{
  header("Location: my_tests.php");
}
$check_select_result->free();

if ($mark == -2){
  header("Location: my_tests.php?verification_error=1");
}


if (isset($_POST['finish'])){
  $test_data = get_test_data($conn, $test_id);
  $test = json_decode($test_data['test']);
  $flag = false;
  foreach ($test as $question_id){
    if (get_question_data($conn, $question_id)['type'] == 'definite_mc'){
      $flag = true;
      break;
    }
  }

  if ($flag){
    $insert_sql = "UPDATE tests_to_users SET mark=-2, date=".time().", answers='".json_encode($_POST['test_input'], 256)."', verified_scores='{}' WHERE id=$id";
  }else{
    $insert_sql = "UPDATE tests_to_users SET date=".time().", answers='".json_encode($_POST['test_input'], 256)."' WHERE id=$id";
  }
  if ($conn->query($insert_sql)){
    if ($flag){
      header("Location: my_tests.php?verifying=1");
    }else{
      check_the_test($conn, $id);
    }
  }else{
    echo $conn->error;
  }
}

?>
<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
<!-- Header only for current test page -->
<header class="curtest_header">
  <a class="logo curtest_logo" href="../index.php"><p>24</p><p>Roman</p></a>
  <!-- right side of header -->
  <div class="right">
    <!-- Progress of test(in percents) -->
    <section class="test_progress">
      <!-- Progress line and count of percent -->
      <div class="progress_bar">
        <h2>Progress</h2>
        <div class="percents">
          <p>0%</p>
          <div class="finish"></div>
        </div>
      </div>
      <!-- Navigation of test -->
      <nav>

      </nav>
    </section>
    <!-- Timer(rest time) -->
    <div class="time">
      00:00
    </div>
  </div>
</header>

<?php print_test_by_id($conn, $test_id); ?>

<?php include "../templates/footer.html"; ?>
<script>
    let questionsArr = document.querySelectorAll('.questions_list .question');
    const testsHeader = document.querySelector('.curtest_header');

    // Navigation
    window.addEventListener('scroll', function() {
        if(pageYOffset > 0){
            testsHeader.style.cssText = `background-color: #ffffffc0;`;
        }
        if(pageYOffset == 0){
            testsHeader.style.cssText = `background-color:none;`;
        }
    });

    const testNavigation = document.querySelector('.curtest_header nav');
    const progressBar = document.querySelector('.curtest_header .progress_bar .finish');
    let percents = document.querySelector('.curtest_header .progress_bar .percents p');

    for(let i = 0; i < questionsArr.length; i++){
        let newElem = document.createElement('button');
        newElem.innerHTML = `${i+1}`;
        testNavigation.appendChild(newElem);
    }

    const navigationButtons = document.querySelectorAll('.curtest_header nav button');

    for(let i = 0; i < navigationButtons.length; i++){
        navigationButtons[i].addEventListener('click', function(){
            questionsArr[i].scrollIntoView({
                behavior: "smooth", block: "center", inline: "start"
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

        for(let j = 0; j < inputBlocksArr.length; j++){
            let inputItem = inputBlocksArr[j].children[0];

            inputItem.addEventListener('click', function(){
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

            });
        }
    }
    // =================================

    // =====Timer=====
    let time = 0;
            if(localStorage.getItem("test_time")){
                time = localStorage.getItem("test_time");
            }
            else{
                // сюда подставить время
                time = <?php echo $duration; ?>;
                localStorage.setItem("test_time", time);
            }

            const timer = document.querySelector('.curtest_header .time');

            if(time <= 0){
                timer.innerHTML = `00:00`;
            }
            else{
                timer.innerHTML = `${Math.floor(time / 60)}:${time % 60}`;
            }

            const FinsishButton = document.querySelector('.questions_list .finish');

            FinsishButton.addEventListener('click', function(){
                clearInterval(IntervalTimer);
                time = 0;
            });

            //Если пользователь начал тестирование, то запускается таймер
            if(time > 0){
                let minutes = Math.floor(time / 60);
                let seconds = time % 60;
                if (seconds < 10){
                    seconds = '0' + seconds;
                }
                if (minutes < 10){
                    minutes = '0' + minutes;
                }

                timer.innerHTML = `${minutes}:${seconds}`;
                time--;
                localStorage.setItem("test_time", time);

                let IntervalTimer = setInterval(UpdateTime, 1000);
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
                

                if(time == 0){
                    localStorage.removeItem("test_time");
                    clearInterval(IntervalTimer);
                    FinsishButton.click();
                }

                time--;
                localStorage.setItem("test_time", time);
            }
</script>
</body>
</html>
