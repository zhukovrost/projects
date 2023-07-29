<?php
include '../templates/func.php';
include '../templates/settings.php';

check_the_login($user_data, "../");

$next_month = mktime(0, 0, 0, date('m') + 1, 1, date('Y'));
$current_month = mktime(0, 0, 0, date('m') , 1, date('Y'));
$previous_month = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));

?>

<!DOCTYPE html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body class="icons_body">
    <!-- Main header -->
    <?php include "../templates/header.html"; ?>

    <main>
        <!-- Marks block -->
        <div class="container marks_block">
            <!-- Previous month -->
            <div class="top">
                <div class="previous_cover">
                    <div class="title">
                        <h1>Previous month</h1>
                    </div>
                    <button class="previous">
                        <!-- Marks -->
                        <div class="marks">
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                            <div>4</div>
                            <div>4</div>
                            <div>5</div>
                            <div>4</div>
                            <div>3</div>
                            <div>2</div>
                        </div>
                        <!-- Marks' statistic -->
                        <div class="statistic">
                            <div class="content">
                                <h2>Grades five: <span>3</span></h2>
                                <h2>Grades four: <span>3</span></h2>
                                <h2>Grades three: <span>3</span></h2>
                                <h2>Grades two: <span>3</span></h2>
                                <h1>Total grade: <span>4.09</span></h1>
                            </div>
                        </div>
                    </button>
                </div>
                
                <!-- Date(current month) -->
                <div class="date">
                    <p>Choose date: </p>
                    <input type="month">
                </div>
            </div>
            <!-- Current month -->
            <section class="current">
                <!-- Marks -->
                <div class="marks">
                  <?php
                  $select_sql = "SELECT mark FROM tests_to_users WHERE user=".$user_data['id']." AND date >= $current_month AND date < $next_month ORDER BY date DESC";
                  if ($select_result = $conn -> query($select_sql)){
                    if ($select_result->num_rows > 0){
                      foreach ($select_result as $mark_data){
                        $mark = $mark_data['mark'];
                        echo "<div>$mark</div>";
                      }
                    }else{ ?>
                      <p>No marks this month.</p>
                    <?php }
                  }
                  ?>
                </div>
                <!-- Marks' statistic -->
                <div class="statistic">
                    <div class="content">
                        <h2>Grades five: <span>3</span></h2>
                        <h2>Grades four: <span>3</span></h2>
                        <h2>Grades three: <span>3</span></h2>
                        <h2>Grades two: <span>3</span></h2>
                        <h1>Total grade: <span>4.09</span></h1>
                    </div>
                    <!-- Button to next month -->
                    <button>Next month <img src="../img/Arrow 6.svg" alt=""></button>
                </div>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html"; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
</body>
</html>