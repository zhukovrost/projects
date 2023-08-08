<?php
include '../templates/func.php';
include '../templates/settings.php';

check_the_login($user_data, "../");

if (isset($_GET['month'])){
  $month = $_GET['month'];
}else{
  $month = 0;
}

$next_month = mktime(0, 0, 0, date('m') + 1 + $month, 1, date('Y'));
$current_month = mktime(0, 0, 0, date('m') + $month , 1, date('Y'));
$previous_month = mktime(0, 0, 0, date('m') - 1 + $month, 1, date('Y'));

if (isset($_POST['month_input'])) {
  $d1 = date_create(date('Y-m', $current_month));
  $d2 = date_create($_POST['month_input']);
  $interval = date_diff($d2, $d1, false);
  if ($interval->invert){
    $month += $interval->m;
  }else{
    $month -= $interval->m;
  }
  header("Location: my_marks.php?month=".$month);
}

$month_input = date('Y-m', $current_month)

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
                    <a class="previous" href="my_marks.php?month=<?php echo $month - 1; ?>">
                        <!-- Marks -->
                        <?php print_marks($conn, $user_data['id'], $previous_month, $current_month, true, $month); ?>
                    </a>
                </div>
                
                <!-- Date(current month) -->
                <div class="date">
                    <p>Choose date: </p>
                    <form method="post">
                        <input type="month" name="month_input" value="<?php echo $month_input; ?>">
                    </form>
                </div>
            </div>
            <!-- Current month -->
            <section class="current">
                <!-- Marks -->
                <?php print_marks($conn, $user_data['id'], $current_month, $next_month, false, $month); ?>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html"; ?>

    <script src="../templates/format.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script>
        let marksArr = document.querySelectorAll('.marks_block .marks div');
        for(let i = 0; i < marksArr.length; i++){
            if(parseInt(marksArr[i].innerHTML) >= 80){
                marksArr[i].style.cssText = `background-color: rgba(74, 168, 1, 1)`;
            }
            if(parseInt(marksArr[i].innerHTML) < 80 && parseInt(marksArr[i].innerHTML) >= 60){
                marksArr[i].style.cssText = `background-color: rgba(144, 180, 0, 1)`;
            }
            if(parseInt(marksArr[i].innerHTML) < 60 && parseInt(marksArr[i].innerHTML) >= 40){
                marksArr[i].style.cssText = `background-color: rgba(223, 134, 0, 1)`;
            }
            if(parseInt(marksArr[i].innerHTML) < 40){
                marksArr[i].style.cssText = `background-color: rgba(199, 0, 0, 1)`;
            }
        }
    </script>
    <script>
        let monthInput = document.querySelector('.marks_block .date input');

        monthInput.addEventListener('change', function(){
            document.querySelector('.marks_block .date form').submit();
        });
    </script>
</body>
</html>