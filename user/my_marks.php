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
                        <?php print_marks($conn, $user_data['id'], $previous_month, $current_month, true); ?>
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
                <?php print_marks($conn, $user_data['id'], $current_month, $next_month); ?>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html"; ?>

    <script src="../templates/format.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
</body>
</html>