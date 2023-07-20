<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>24 / Roman</title>
</head>
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
            <h1 class="profile_title">Welcome, Lorem!</h1>
            <!-- profile block -->
            <section class="profile_block">
                
                <!-- User avatar photo -->
                <div class="avatar">
                    <img id="profileImage" src="../img/Volk.webp" alt="">
                    <input type="file" id="avatar_file" accept="image/*" />
                    <label for="avatar_file" class="uppload_button">Choose photo</label>
                </div>
                
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
                        <a class="item" href="">Marks</a>
                        <a class="item" href="">All tests</a>
                        <a class="item" href="">All materials</a>
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
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'number of completed tests per week',
                data: [2, 5, 3, 7, 2, 1, 3],
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
        var savedCroppedImageDataURL = localStorage.getItem("profileAvatar");
        if (savedCroppedImageDataURL) {
            $("#profileImage").attr("src", savedCroppedImageDataURL);
        }


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
            
                $("#profileImage").attr("src", croppedImageDataURL);
                console.log(profileImage.src);
                localStorage.setItem('profileAvatar', croppedImageDataURL);
                location.reload();
                
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
      
      
</body>
</html>