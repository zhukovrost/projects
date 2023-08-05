<?php
include '../templates/func.php';
include '../templates/settings.php';

$title = "Feedback";
?>
<!DOCTYPE html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>

  <?php include "../templates/header.html"; ?>

    <!-- Popup window for user's feeadback -->
    <?php include "../templates/pop_up_report_window.php" ?>

    <main>
        <!-- Reports container -->
        <div class="container reports">
            <!-- Reports statistic block -->
            <section class="about_block">
                <img src="../img/team-meeting-startups.png" alt="">
                <!-- Statistic -->
                <div>
                    <ul>
                        <li>More than 80% positive reviews</li>
                        <li>More than 95% of satisfied students</li>
                        <li>We are recommended by more than <br> 90% of users</li>
                    </ul>
                    <h1>Overall rating: <span><?php echo get_overall_rating($conn); ?></span></h1>
                </div>
            </section>

            <!-- Reports block(swiper) -->
            <swiper-container class="mySwiper" pagination="true" effect="coverflow" grab-cursor="true" centered-slides="true"
            slides-per-view="auto" coverflow-effect-rotate="50" coverflow-effect-stretch="0" coverflow-effect-depth="100"
            coverflow-effect-modifier="1" coverflow-effect-slide-shadows="true">

              <?php
              $quantity = 5;
              $select_sql = "SELECT * FROM reports ORDER BY id DESC LIMIT $quantity";
              include "../templates/report_list.php";
              ?>

            </swiper-container>

            <div class="links">
                <!-- Link to all reviews page -->
                <a href="all_reports.php">View all reports</a>
                <!-- Button to leave feedback -->
              <?php if (check_the_login($user_data, '../', false)){ ?>
                <button class="popup_button">Leave your feedback</button>
              <?php }else{ ?>
                <a href="../log.php">Log in to leave the feedback</a>
              <?php } ?>
            </div>
        </div>

    </main>

    <script src="../templates/format.js"></script>

    <?php include "../templates/footer.html"; ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script src="../templates/pop_up_report_script.js"></script>
    <?php if ($error_array['success_new_report']){ ?>
    <script>alert("Your feedback has been published!")</script>
    <?php }
    if ($error_array['fill_all_the_fields']){ ?>
    <script>alert("Error: fill all fields!")</script>
    <?php } ?>
</body>
</html>