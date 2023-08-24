<?php
include '../templates/func.php';
include '../templates/settings.php';

check_the_login($user_data, '../');
$avatar = get_avatar($conn, $user_data);
$title = $user_data['login'];

# ------------ getting diagram data -------------------
$select_sql = "SELECT date FROM tests_to_users WHERE user=".$user_data['id']." AND mark >= 0 ORDER BY date DESC";
$completed_tests = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
if ($select_result = $conn->query($select_sql)){
  foreach ($select_result as $test_date){
    if ($test_date['date'] < mktime(0, 0, 0, 1, 1, date("Y"))){
      break;
    }else{
      for ($i = 1; $i <= 12; $i++){
        if ($test_date['date'] > mktime(0, 0, 0, $i - 1, 0, date("Y")) && $test_date['date'] < mktime(0, 0, 0, $i + 1, 1, date("Y"))){
          $completed_tests[$i - 1]++;
          break;
        }
      }
    }
  }
}
$select_result->free();
$completed_tests = json_encode($completed_tests);

# ---------------- avatar upload ------------------------

if(isset($_POST['image_to_php'])) {
  $data = $_POST['image_to_php'];
  $avatar_id = $user_data['avatar'];
  if ($avatar_id == 1){
    $sql = "INSERT INTO avatars (file) VALUES ('$data')";
  }else{
    $sql = "UPDATE avatars SET file='$data' WHERE id=$avatar_id";
  }
  if ($conn->query($sql)){
    if ($avatar_id == 1){
      $new_avatar_id = mysqli_insert_id($conn);
      $update_sql = "UPDATE users SET avatar=$new_avatar_id WHERE id=".$user_data['id'];
      if ($conn->query($update_sql)){
        header("Refresh: 0");
      }else{
        echo $conn->error;
      }
    }else{
      header("Refresh: 0");
    }
  }else{
    echo $conn->error;
  }
}


# -------------------- getting num of uncompleted tests ----------------------

$uncompleted_tests = 0;
$select_sql_2 = "SELECT id FROM tests_to_users WHERE user=".$user_data['id']." AND (mark=-1 OR mark=-3) AND (deadline > ".time()." OR deadline=-1) ORDER BY deadline DESC";
if ($select_result_2 = $conn->query($select_sql_2)){
  $uncompleted_tests = $select_result_2->num_rows;
}


# --------------- getting nearest test ---------------------------

$flag = false;

$select_sql_3 = "SELECT id, test, deadline, duration FROM tests_to_users WHERE user=".$user_data['id']." AND (mark=-1 OR mark=-3) AND deadline > ".time()." ORDER BY deadline LIMIT 1";
if ($select_result_3 = $conn->query($select_sql_3)){
  if ($select_result_3->num_rows > 0){
    foreach ($select_result_3 as $item){
      $flag = true;
      $test = get_test_data($conn, $item['test']);
      $test_deadline = $item['deadline'];
      $tests_to_users_id = $item['id'];
      $test_duration = $item['duration'];
    }
  }else{
    $select_sql_4 = "SELECT id, test, deadline, duration FROM tests_to_users WHERE user=".$user_data['id']." AND (mark=-1 OR mark=-3) AND (deadline > ".time()." OR deadline=-1) ORDER BY date LIMIT 1";
    if ($select_result_4 = $conn->query($select_sql_4)){
      foreach ($select_result_4 as $item){
        $flag = true;
        $test = get_test_data($conn, $item['test']);
        $test_deadline = $item['deadline'];
        $tests_to_users_id = $item['id'];
        $test_duration = $item['duration'];
      }
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
    <!-- Main header -->

    <?php include "../templates/header.html"; ?>

    <main class="profile_main">
        <div class="preview_cover">
            <div class="preview_block">
                <img id="preview" src="#" alt="Preview"/>
            </div>
            <button id="saveAvatar">Сохранить</button>
        </div>

        <div class="container">
            <!-- Welcome 'user name' -->
            <h1 class="profile_title">Welcome, <?php echo $user_data['name']; ?></h1>
            <!-- profile block -->
            <section class="profile_block">
                
                <!-- User avatar photo -->
                <form id="avatar_form" class="avatar" method="post">
                  <img id="profileImage" src="<?php echo $avatar; ?>">
                  <input type="file" id="avatar_file" accept="image/*" />
                  <label for="avatar_file" class="uppload_button">Choose photo</label>
                  <input type="hidden" id="image_to_php" name="image_to_php" value="">
                </form>
                
                <!-- nearest theory and homework -->
                <section class="materials">
                    <section class="theory">
                        <h2>My tests</h2>
                        <p>It's time to do your homework!</p>
                        <div>
                            <p>You have</p>
                            <p class="number"> <?php echo $uncompleted_tests; ?> </p>
                            <p>uncompleted tests</p>
                          <?php if ($uncompleted_tests > 0){?>
                            <a href="my_tests.php"> GO </a>
                          <?php } ?>
                        </div>
                    </section>
                    <section class="homework">
                        <div class="content">
                            <img src="../img/homework.svg" alt="">
                          <div>
                          <?php if ($flag){ ?>
                              <h2>Nearest homework</h2>
                              <p>Name: <span><?php echo $test['name']; ?></span></p>
                              <p>Deadline: <span><?php if ($test_deadline != -1) { echo date("d.m.Y", (int)$test_deadline); } else { echo "None"; } ?></span></p>
                              <p class="allowed_time">Allowed time: <span><?php echo date("i:s", (int)$test_duration); ?></span></p>
                            <a href="my_tests.php">GO</a>
                          <?php } else { ?>
                              <p>You have done all your homework</p>
                          <?php } ?>
                          </div>
                        </div>
                    </section>
                </section>
                <!-- Activity diagram and links to all materials -->
                <section class="activity">
                    <div class="diagram">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="links">
                        <a class="item" href="my_marks.php?month=0">Marks</a>
                        <a class="item" href="theory.php">Theory</a>
                        <a class="item" href="my_tests.php">All tests</a>
                      <?php
                      if (check_if_admin($user_data, '../')){
                        ?>
                        <a href="../construct/construct.php" class="item">Admin Page</a>
                      <?php } ?>
                        <!-- Logout button for all users -->
                        <a href="../clear.php" class="logout_btn">Logout <img src="../img/exit_account.svg" alt=""></a>

                        <!-- Logout button only for admin -->
                        <!-- <div class="logout_cover">
                            <p class="construct_title" ><a href="construct/construct.php">Constructor</a></p>
                            <a class="admin_logout_btn" href="clear.php">Выйти</a>
                        </div> -->
                    </div>
                </section>
            </section>
        </div>
    </main>

    <?php include "../templates/footer.html"; ?>

    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
         const ctx = document.getElementById('myChart');

         new Chart(ctx, {
            type: 'bar',
            data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'number of completed tests per month (<?php echo date("Y"); ?>)',
                data: <?php echo $completed_tests; ?>,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
    </script>
    <!-- Подключение стилей для Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <!-- Подключение скриптов для Cropper.js и jQuery (если требуется) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        avatar_file.addEventListener('click', function(){
            document.querySelector('.preview_cover').style.cssText = `display: flex;`;
        });

        $(document).ready(function () {
          var croppedImageDataURL; // Переменная для хранения Data URL обрезанного изображения
      
          // При выборе файла для загрузки
          $("#avatar_file").on("change", function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();
      
            // Чтение файла и отображение его в элементе img#preview
            reader.onload = function (event) {
              $("#preview").attr("src", event.target.result);
              initCropper();
            };
      
            reader.readAsDataURL(file);
          });
      
          // Инициализация Cropper.js
          function initCropper() {
            var image = document.getElementById("preview");
            var cropper = new Cropper(image, {
                aspectRatio: 1, // Соотношение сторон 1:1 для круглой области обрезки
                viewMode: 2,    // Позволяет обрезать изображение внутри области обрезки


                // Позиционируем область обрезки в центре
                autoCropArea: 0.6,


                // Обработка обрезки изображения
                crop: function (event) {
                    // Получение координат и размеров обрезанной области
                    var x = event.detail.x;
                    var y = event.detail.y;
                    var width = event.detail.width;
                    var height = event.detail.height;

                    // Создание canvas с обрезанным изображением
                    var canvas = cropper.getCroppedCanvas({
                        width: width,
                        height: height,
                        left: x,
                        top: y,
                    });

                    // Преобразование canvas в Data URL изображения
                    croppedImageDataURL = canvas.toDataURL("png");
                },
            });
        }
      
          // Обработка сохранения обновленной аватарки
          $("#saveAvatar").on("click", function () {
            if (croppedImageDataURL) {
                location.reload()
                image_to_php.value = croppedImageDataURL
                document.getElementById("avatar_form").submit();
            } 
            else {
              alert("Сначала выберите и обрежьте изображение");
            }
          });
        });

        saveAvatar.addEventListener('click', function(){
            document.querySelector('.preview_cover').style.cssText = `display: none;`;
        });



        // Buttons GO
        let uncompletedTestsButton = document.querySelector('.profile_block .theory div a');
        let nearestTestButton = document.querySelector('.profile_block .homework a');

        uncompletedTestsButton.addEventListener('click', function(){
          localStorage.setItem(`uncompletedTestsClick`, 1);
        });

        nearestTestButton.addEventListener('click', function(){
          localStorage.setItem(`nearestTestClick`, 1);
          console.log(1)
        });
      </script>
      <script src="../templates/format.js"></script>
      
</body>
</html>