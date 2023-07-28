<?php
include '../templates/func.php';
include '../templates/settings.php';

check_the_login($user_data, "../");
$title = "My tests";

$tests = array();
$select_all_tests_sql = "SELECT test, duration, mark FROM tests_to_users WHERE user=".$user_data['id'];
if ($select_all_tests_result = $conn->query($select_all_tests_sql)){
  foreach ($select_all_tests_result as $item){
    array_push($tests, $item);
  }
}
$select_all_tests_result->free();

$stats = get_stats($conn, $user_data['id']);
$diagram_data = json_encode([$stats['wrong'], $stats['not_answered'], $stats['correct']])

?>

<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
<main>
  <div class="container">
  <?php include "../templates/header.html"; ?>

    <!-- Block of progress for all tests -->
    <section class="tests_progress">
      <h1 class="title">Progress on tests</h1>
      <div class="content">
        <div class="diagram">
          <canvas id="myPie"></canvas>
        </div>
        <div class="statistic">
          <p>Total tests passed: <span><?php echo $stats['tests']; ?></span></p>
          <p>Time spent on tests: <span><?php echo round($stats['time'] / 60);?> min</span></p>
          <p>Average mark for the test: <span><?php if ($stats['tests'] != 0){ echo round($stats['mark'] / $stats['tests'], 2); }else{ echo 0; } ?></span></p>
        </div>
      </div>
    </section>

    <!-- Sort of All tests -->
    <div class="tests_list_sort">
      <img src="img/search.svg" alt="">
      <input type="text" placeholder="Search theme">
      <div class="ready_sort">
        <button class="all sortItem_active">All</button>
        <button class="passed">Passed</button>
        <button class="not_passed">Not passed</button>
        <button class="time"><p>Time</p> <img src="../img/Arrow_down.svg" alt=""></button>
        <button class="questions_number"><p>Number of questions</p> <img src="../img/Arrow_down.svg" alt=""></button>
      </div>
    </div>
    <section class="tests_list">
      <div class="column left">
        <?php
        for ($i = 0; $i < count($tests); $i += 2){
          print_test_info($conn, $tests[$i]);
        }
        ?>
      </div>
      <div class="column right">
        <?php
        for ($i = 1; $i < count($tests); $i += 2){
          print_test_info($conn, $tests[$i]);
        }
        ?>
      </div>
    </section>
  </div>
</main>
<?php
include "../templates/footer.html";
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ===Pie diagramm===
    const ctx = document.getElementById('myPie');

    const data = {
        labels: [
            'Wrong',
            'Not answered',
            'Correct'
        ],
        datasets: [{
            label: 'Count of tests',
            data: <?php echo $diagram_data; ?>,
            backgroundColor: [
                'rgba(15, 38, 84, 1)',
                'rgba(10, 70, 224, 1)',
                'rgba(154, 208, 245, 1)'
            ],
            hoverOffset: 4
        }]
    };

    new Chart(ctx, {
        type: 'pie',
        data: data,
    });
    // ===============


    // ===SEARCH===
    let testThemes = document.querySelectorAll('.tests_list .theme span');

    function ThemeSearch(val){
        val = val.trim().replaceAll(' ', '').toUpperCase();
        if(val != ''){
            testThemes.forEach(function(elem){
                if(elem.innerText.trim().replaceAll(' ', '').toUpperCase().search(val) == -1){
                    let cur_test = elem.parentNode;
                    cur_test = cur_test.parentNode;
                    cur_test = cur_test.parentNode;
                    cur_test.classList.add('hide');
                }
                else{
                    let cur_test = elem.parentNode;
                    cur_test = cur_test.parentNode;
                    cur_test = cur_test.parentNode;
                    cur_test.classList.remove('hide');
                }
            });
        }
        //
        else{
            testThemes.forEach(function(elem){
                let cur_test = elem.parentNode;
                cur_test = cur_test.parentNode;
                cur_test = cur_test.parentNode;
                cur_test.classList.remove('hide');
            });
        }
    }


    // Theme search
    const search_input = document.querySelector('.tests_list_sort input');
    search_input.addEventListener('input', function(){
        ThemeSearch(search_input.value);
    });

    // Filter buttons
    const filterButtons = document.querySelectorAll('.tests_list_sort .ready_sort button');
    let deadlineSpans = document.querySelectorAll('.tests_list .deadline span');
    let timeSpans = document.querySelectorAll('.tests_list .time span');
    let questionsNumberSpans = document.querySelectorAll('.tests_list .questions_number span');
    let statusSpans = document.querySelectorAll('.tests_list .status span');

    let deadlineArr = [];
    let timeArr = [];
    let questionsArr = [];
    let statusArr = [];

    for(let i = 0; i < deadlineSpans.length; i++){
        let current = (deadlineSpans[i].innerHTML).split(' ');
        let minutes = parseInt(current[0].split(':')[0]) * 60 + parseInt(current[0].split(':')[1]);
        let day = parseInt(current[2]) - 1;
        let month = 0;
        let year = new Date().getFullYear();
        if(current[1] == "January"){
            month = 1;
        }
        if(current[1] == "February"){
            month = 2;
        }
        if(current[1] == "March"){
            month = 3;
        }
        if(current[1] == "April"){
            month = 4;
        }
        if(current[1] == "May"){
            month = 5;
        }
        if(current[1] == "June"){
            month = 6;
        }
        if(current[1] == "July"){
            month = 7;
        }
        if(current[1] == "August"){
            month = 8;
        }
        if(current[1] == "September"){
            month = 9;
        }
        if(current[1] == "October"){
            month = 10;
        }
        if(current[1] == "November"){
            month = 11;
        }
        if(current[1] == "December"){
            month = 12;
        }

        let time = minutes + (day * 24 * 60)  + (month * 30 * 24 * 60)  + (year * 365 * 30 * 24 * 60);
        let item = {
            number: i,
            time: time
        };

        deadlineArr.push(item);
    }

    for(let i = 0; i < timeSpans.length; i++){
        let time = parseInt(timeSpans[i].innerHTML.split(' ')[0]);
        let item = {
            number: i,
            time: time
        };
        timeArr.push(item);
    }

    for(let i = 0; i < questionsNumberSpans.length; i++){
        let count = parseInt(questionsNumberSpans[i].innerHTML);
        let item = {
            number: i,
            count: count
        };
        questionsArr.push(item);
    }

    for(let i = 0; i < statusSpans.length; i++){
        let status = statusSpans[i].innerHTML;
        let item = {
            number: i,
            status: status
        };
        statusArr.push(item);
    }

    deadlineArr.sort((a, b) => a.time > b.time ? 1 : -1);
    timeArr.sort((a, b) => a.time > b.time ? 1 : -1);
    questionsArr.sort((a, b) => a.count > b.count ? 1 : -1);


    let rightColum = document.querySelector('.tests_list .column.right');
    let leftColum = document.querySelector('.tests_list .column.left');
    let testsArr = document.querySelectorAll('.tests_list .test');
    console.log(testsArr);


    for(let i = 0; i < filterButtons.length; i++){
        filterButtons[i].addEventListener('click', function(){
            rightColum.innerHTML = '';
            leftColum.innerHTML = '';

            if(filterButtons[i].children.length > 0){
                if(filterButtons[i].children[0].innerHTML == 'Time'){
                    if(filterButtons[i].classList.contains('sortItem_active')){
                        if(filterButtons[i].children[1].classList.contains('up')){
                            filterButtons[i].children[1].classList.remove('up');
                            for(let j = timeArr.length - 1; j >= 0; j--){
                                if(timeArr.length % 2 == 0){
                                    if(j % 2 == 0){
                                        rightColum.appendChild(testsArr[timeArr[j].number]);
                                    }
                                    if(j % 2 == 1){
                                        leftColum.appendChild(testsArr[timeArr[j].number]);
                                    }
                                }
                                else{
                                    if(j % 2 == 1){
                                        rightColum.appendChild(testsArr[timeArr[j].number]);
                                    }
                                    if(j % 2 == 0){
                                        leftColum.appendChild(testsArr[timeArr[j].number]);
                                    }
                                }
                            }
                        }
                        else{
                            filterButtons[i].children[1].classList.add('up');
                            for(let j = 0; j < timeArr.length; j++){
                                if(j % 2 == 1){
                                    rightColum.appendChild(testsArr[timeArr[j].number]);
                                }
                                if(j % 2 == 0){
                                    leftColum.appendChild(testsArr[timeArr[j].number]);
                                }
                            }
                        }
                    }
                    else{
                        filterButtons[i].classList.add('sortItem_active');
                        filterButtons[i].children[1].classList.remove('up');

                        for(let j = timeArr.length - 1; j >= 0; j--){
                            if(timeArr.length % 2 == 0){
                                if(j % 2 == 0){
                                    rightColum.appendChild(testsArr[timeArr[j].number]);
                                }
                                if(j % 2 == 1){
                                    leftColum.appendChild(testsArr[timeArr[j].number]);
                                }
                            }
                            else{
                                if(j % 2 == 1){
                                    rightColum.appendChild(testsArr[timeArr[j].number]);
                                }
                                if(j % 2 == 0){
                                    leftColum.appendChild(testsArr[timeArr[j].number]);
                                }
                            }
                        }
                    }
                }

                if(filterButtons[i].children[0].innerHTML == 'Deadline'){
                    if(filterButtons[i].classList.contains('sortItem_active')){
                        if(filterButtons[i].children[1].classList.contains('up')){
                            filterButtons[i].children[1].classList.remove('up');
                            for(let j = deadlineArr.length - 1; j >= 0; j--){
                                if(deadlineArr.length % 2 == 0){
                                    if(j % 2 == 0){
                                        rightColum.appendChild(testsArr[deadlineArr[j].number]);
                                    }
                                    if(j % 2 == 1){
                                        leftColum.appendChild(testsArr[deadlineArr[j].number]);
                                    }
                                }
                                else{
                                    if(j % 2 == 1){
                                        rightColum.appendChild(testsArr[deadlineArr[j].number]);
                                    }
                                    if(j % 2 == 0){
                                        leftColum.appendChild(testsArr[deadlineArr[j].number]);
                                    }
                                }
                            }
                        }
                        else{
                            filterButtons[i].children[1].classList.add('up');
                            for(let j = 0; j < deadlineArr.length; j++){
                                if(j % 2 == 1){
                                    rightColum.appendChild(testsArr[deadlineArr[j].number]);
                                }
                                if(j % 2 == 0){
                                    leftColum.appendChild(testsArr[deadlineArr[j].number]);
                                }
                            }
                        }
                    }
                    else{
                        filterButtons[i].classList.add('sortItem_active');
                        filterButtons[i].children[1].classList.remove('up');

                        for(let j = timeArr.length - 1; j >= 0; j--){
                            if(deadlineArr.length % 2 == 0){
                                if(j % 2 == 0){
                                    rightColum.appendChild(testsArr[deadlineArr[j].number]);
                                }
                                if(j % 2 == 1){
                                    leftColum.appendChild(testsArr[deadlineArr[j].number]);
                                }
                            }
                            else{
                                if(j % 2 == 1){
                                    rightColum.appendChild(testsArr[deadlineArr[j].number]);
                                }
                                if(j % 2 == 0){
                                    leftColum.appendChild(testsArr[deadlineArr[j].number]);
                                }
                            }
                        }
                    }
                }

                if(filterButtons[i].children[0].innerHTML == 'Number of questions'){
                    if(filterButtons[i].classList.contains('sortItem_active')){
                        if(filterButtons[i].children[1].classList.contains('up')){
                            filterButtons[i].children[1].classList.remove('up');
                            for(let j = questionsArr.length - 1; j >= 0; j--){
                                if(questionsArr.length % 2 == 0){
                                    if(j % 2 == 0){
                                        rightColum.appendChild(testsArr[questionsArr[j].number]);
                                    }
                                    if(j % 2 == 1){
                                        leftColum.appendChild(testsArr[questionsArr[j].number]);
                                    }
                                }
                                else{
                                    if(j % 2 == 1){
                                        rightColum.appendChild(testsArr[questionsArr[j].number]);
                                    }
                                    if(j % 2 == 0){
                                        leftColum.appendChild(testsArr[questionsArr[j].number]);
                                    }
                                }
                            }
                        }
                        else{
                            filterButtons[i].children[1].classList.add('up');
                            for(let j = 0; j < questionsArr.length; j++){
                                if(j % 2 == 1){
                                    rightColum.appendChild(testsArr[questionsArr[j].number]);
                                }
                                if(j % 2 == 0){
                                    leftColum.appendChild(testsArr[questionsArr[j].number]);
                                }
                            }
                        }
                    }
                    else{
                        filterButtons[i].classList.add('sortItem_active');
                        filterButtons[i].children[1].classList.remove('up');
                        for(let j = questionsArr.length - 1; j >= 0; j--){
                            if(questionsArr.length % 2 == 0){
                                if(j % 2 == 0){
                                    rightColum.appendChild(testsArr[questionsArr[j].number]);
                                }
                                if(j % 2 == 1){
                                    leftColum.appendChild(testsArr[questionsArr[j].number]);
                                }
                            }
                            else{
                                if(j % 2 == 1){
                                    rightColum.appendChild(testsArr[questionsArr[j].number]);
                                }
                                if(j % 2 == 0){
                                    leftColum.appendChild(testsArr[questionsArr[j].number]);
                                }
                            }
                        }
                    }
                }
            }
            else{
                if(filterButtons[i].innerHTML == 'All'){
                    filterButtons[i].classList.add('sortItem_active');
                    rightColum.innerHTML = '';
                    leftColum.innerHTML = '';
                    for(let i = 0; i < testsArr.length; i++){
                        if(i % 2 == 1){
                            rightColum.appendChild(testsArr[i]);
                        }
                        if(i % 2 == 0){
                            leftColum.appendChild(testsArr[i]);
                        }
                    }
                }
                if(filterButtons[i].innerHTML == 'Passed'){
                    filterButtons[i].classList.add('sortItem_active');
                    rightColum.innerHTML = '';
                    leftColum.innerHTML = '';
                    for(let i = 0; i < statusArr.length; i++){
                        if(statusArr[i].status == 'passed'){
                            if(i % 2 == 1){
                                rightColum.appendChild(testsArr[statusArr[i].number]);
                            }
                            if(i % 2 == 0){
                                leftColum.appendChild(testsArr[statusArr[i].number]);
                            }
                        }

                    }
                }
                if(filterButtons[i].innerHTML == 'Not passed'){
                    filterButtons[i].classList.add('sortItem_active');
                    rightColum.innerHTML = '';
                    leftColum.innerHTML = '';
                    for(let i = 0; i < statusArr.length; i++){
                        if(statusArr[i].status == 'not passed'){
                            if(i % 2 == 1){
                                rightColum.appendChild(testsArr[statusArr[i].number]);
                            }
                            if(i % 2 == 0){
                                leftColum.appendChild(testsArr[statusArr[i].number]);
                            }
                        }

                    }
                }
            }

            for(let j = 0; j < filterButtons.length; j++){
                if(j != i){
                    filterButtons[j].classList.remove('sortItem_active');
                }
            }
        });
    }

</script>
</body>
</html>
