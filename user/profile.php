<?php
include '../templates/func.php';
include '../templates/settings.php';

check_the_login($user_data, '../');
$avatar = get_avatar($conn, $user_data);
$title = $user_data['login'];

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
                  <img id="profileImage" src="data:image/jpeg;base64, <?php echo $avatar; ?>">
                  <input type="file" id="avatar_file" accept="image/*" />
                  <label for="avatar_file" class="uppload_button">Choose photo</label>
                  <input type="hidden" id="image_to_php" name="image_to_php" value="">
                </form>
                
                <!-- nearest theory and homework -->
                <section class="materials">
                    <section class="theory">
                        <h2>My theory</h2>
                        <p>Learn the theory before doing your homework!</p>
                        <div>
                            <p>You have</p>
                            <p class="number"> 3 </p>
                            <p>unread theories</p>
                            <button> GO </button>
                        </div>
                    </section>
                    <section class="homework">
                        <div class="content">
                            <img src="../img/homework.svg" alt="">
                            <div>
                                <h2>Nearest homework</h2>
                                <p>Deadline: <span>23 June 15:00</span></p>
                                <p>Theme: <span>Past Perfect Tense</span></p>
                                <p>Alloted time: <span>120 min</span></p>
                            </div>
                        </div>
                        <button>START</button>
                    </section>
                </section>
                <!-- Activity diagram and links to all materials -->
                <section class="activity">
                    <div class="diagram">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="links">
                        <a class="item" href="my_marks.php">Marks</a>
                        <a class="item" href="my_tests.php">All tests</a>
                        <a class="item" href="my_materials.php">All materials</a>
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
      </script>
      <script src="../templates/format.js"></script>
      
</body>
</html>