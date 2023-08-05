<?php
include "templates/func.php";
include "templates/settings.php";

$auth = $user_data['auth'];
if ($auth){
  header('Location: user/profile.php');
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/format.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
  <title>24 / Roman</title>
</head>
<body class="icons_body">
<!-- Decoratin -->
<img class="Background_object_1" src="img/Background_object_1.svg" alt="">
<img class="Background_object_2" src="img/Background_object_2.svg" alt="">

<!-- Header only for welcome page -->
<header class="welcome_header">
  <a href="" class="help">Help</a>

  <div>
    <a href="log.php">Log in</a>
    <a href="reg.php">Registration</a>
  </div>

</header>

<main>
    <div class="container">
        <!-- Daily words block -->
        <section class="daily_words">
            <h3 class="title">
                Words of the day
            </h3>
            <swiper-container class="mySwiper" pagination="true" pagination-clickable="true" navigation="true" space-between="0"
            centered-slides="true" autoplay-delay="3000" autoplay-disable-on-interaction="false" speed="1000">
                <swiper-slide>
                    <h2>
                    Light weight - легкий вес
                    </h2>
                    <p class="example_title">
                        Example:
                    </p>
                    <p class="example_text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </swiper-slide>
                <swiper-slide>
                    <h2>
                    Light weight - легкий вес
                    </h2>
                    <p class="example_title">
                        Example:
                    </p>
                    <p class="example_text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </swiper-slide>
                <swiper-slide>
                    <h2>
                    Light weight - легкий вес
                    </h2>
                    <p class="example_title">
                        Example:
                    </p>
                    <p class="example_text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </swiper-slide>
            </swiper-container>
        </section>

        <!-- Welcome words and button to login page -->
        <section class="welcome_content">
            <div class="text">
                <h1>24 / Roman</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. <br> Accusamus voluptatem porro neque, eius, <br> tempora laudantium animi.</p>
                <a href="log.php">GET STARTED <img src="img/Arrow_get_started_button.svg" alt=""></a>
            </div>
            <img class="hats" src="img/Hats.svg" alt="">  
        </section>
    </div>
</main>

<?php include "templates/footer.html"; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
</body>
</html>