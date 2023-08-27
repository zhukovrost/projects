<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="../img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/adaptation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Logo -->
        <a href="../welcome.html" class="logo">
            <img src="../img/logo.svg" alt="">
            <p>Training</p>
        </a>
        <a href="user/workout.html">
            <img src="../img/workout_black.svg" alt="">
            <p>Тренировки</p>
        </a>
        <a href="">
            <img src="../img/progress_black.svg" alt="">
            <p>Прогресс</p>
        </a>
        <a href="">
            <img src="../img/exercises_black.svg" alt="">
            <p>Упражнения</p>
        </a>
        <a href="">
            <img src="../img/news_black.svg" alt="">
            <p>Новости</p>
        </a>
        <a href="">
            <img src="../img/profile_black.svg" alt="">
            <p>Профиль</p>
        </a>
        <a href="">
            <img class="other_img" src="../img/other_black.svg" alt="">
            <p>Другое</p>
        </a>
    </header>

    <main>
        <div class="container workouts">
            <!-- Day's workout swiper -->
            <swiper-container navigation="true">
                <!-- Slide -->
                <swiper-slide>
                    <!-- slide(no arrows) -->
                    <section class="slide">
                        <!-- Title and button to add to favorite collection -->
                        <div class="title">
                            <h2>25.08.2023</h2>
                            <button><img src="../img/favorite.svg" alt=""></button>
                        </div>
                        <!-- Content of workout -->
                        <section class="content">
                            <!-- Exercises array -->
                            <section class="exercise_cover">
                                <!-- Exercise item -->
                                <section class="exercise_item">
                                    <!-- Exercise info button -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <!-- Info text -->
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <!-- Rating and difficult -->
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <!-- Count of repetitions -->
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                            </section>
                            <!-- Info about day workout -->
                            <section class="workout_info">
                                <!-- Muscle groups -->
                                <div class="muscle_groups">
                                    <p>Ноги: <span>7%</span></p>
                                    <p>Спина: <span>70%</span></p>
                                    <p>Руки: <span>3%</span></p>
                                    <p>Пресс: <span>20%</span></p>
                                </div>
                                <!-- Decorative line -->
                                <div class="line"></div>
                                <!-- Exercise info -->
                                <div class="exercise">
                                    <p>Упражнений: <span>5</span></p>
                                    <p>Подходов: <span>3</span></p>
                                    <p>Повторений: <span>20</span></p>
                                </div>
                                <!-- Decorative line -->
                                <div class="line"></div>
                                <!-- Time for workout -->
                                <p class="time">Время: <span>90 мин</span></p>
                                <!-- Buttons edit and start -->
                                <div class="buttons">
                                    <button><img src="../img/edit.svg" alt=""></button>
                                    <button>Начать</button>
                                </div>
                            </section>
                        </section>
                    </section>
                </swiper-slide>
                <swiper-slide>
                    <section class="slide">
                        <div class="title">
                            <h2>26.08.2023</h2>
                            <button><img src="../img/favorite.svg" alt=""></button>
                        </div>
                        <section class="content">
                            <section class="exercise_cover">   
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                                <section class="exercise_item">
                                    <!-- Exercise info -->
                                    <button class="info"><img src="../img/info.svg" alt=""></button>
                                    <div class="info_block">
                                        <button class="info_close"><img src="../img/close.svg" alt=""></button>
                                        <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                                    </div>
                                    <!-- Exercise muscle groups -->
                                    <div class="muscle_groups">Руки - плечи - грудь</div>
                                    <!-- Exercise image -->
                                    <img class="exercise_img" src="../img/exercises/arms/triceps_2.jpg" alt="">
                                    <!-- Decoration line -->
                                    <div class="line"></div>
                                    <!-- Exercise title -->
                                    <h1>Алмазные отжимания</h1>
                                    <div class="statistic">
                                        <div class="rating">
                                            <p>4,5</p>
                                            <img src="../img/Star.svg" alt="">
                                        </div>
                                        <div class="difficult">
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div class="disabled"></div>
                                        </div>
                                    </div>
                                    <div class="caption">
                                        <p>24 x 5</p>
                                    </div>
                                </section>
                            </section>
                            <div class="workout_info">
                                <div class="muscle_groups">
                                    <p>Ноги <span>7%</span></p>
                                    <p>Спина <span>70%</span></p>
                                    <p>Руки <span>3%</span></p>
                                    <p>Пресс <span>20%</span></p>
                                </div>
                                <div class="line"></div>
                                <div class="exercise">
                                    <p>Упражнений <span>5</span></p>
                                    <p>Подходов <span>3</span></p>
                                    <p>Повторений <span>20</span></p>
                                </div>
                                <div class="line"></div>
                                <p class="time">Время <span>90 мин</span></p>
                                <div class="buttons">
                                    <button><img src="../img/edit.svg" alt=""></button>
                                    <button>Начать</button>
                                </div>
                            </div>
                        </section>
                    </section>
                </swiper-slide>
            </swiper-container>
            <section class="other">
                <!-- Friends' workouts -->
                <section class="friends">
                    <!-- Title and button to search friends -->
                    <div class="title">
                        <h1>Тренировки друзей</h1>
                        <a href=""><img src="../img/search.svg" alt=""></a>
                    </div>
                    <!-- Friends' workout swiper -->
                    <swiper-container class="content swiper_friends" navigation="true">
                        <!-- slide -->
                        <swiper-slide>
                            <!-- friend item -->
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                        </swiper-slide>
                        <swiper-slide>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                            <a href="" class="item">
                                <img src="../img/man_avatar.svg" alt="">
                                <p>Иван Иванов</p>
                            </a>
                        </swiper-slide>
                      </swiper-container>
                </section>
                <!-- last trainings -->
                <section class="last_trainings">
                    <!-- Title -->
                    <h1>Последние тренировки</h1>
                    <!-- Trainings content -->
                    <div class="content">
                        <!-- Item -->
                        <section class="item">
                            <!-- Left part of last exercise item -->
                            <div class="left">
                                <!-- Time of training -->
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <!-- Exercise count of training -->
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <!-- Right part of last exercise item -->
                            <div class="right">
                                <!-- Muscle groups count of training -->
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <!-- Button 'Подробнее' for more info about exercise -->
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <section class="item">
                            <div class="left">
                                <div>
                                    <img src="img/time.svg" alt="">
                                    <p><span>65</span> мин</p>
                                </div>
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>10</span> упражнений</p>
                                </div>
                            </div>
                            <div class="right">
                                <div>
                                    <img src="img/cards.svg" alt="">
                                    <p><span>3</span> группы мышц</p>
                                </div>
                                <div>
                                    <button>Подробнее <img src="../img/other.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
                <!-- Buttons favorite workouts and my program -->
                <section class="buttons">
                    <a href="">Избранное <img src="../img/favorite_white.svg" alt=""></a>
                    <a href="my_program.html">Моя программа <img src="../img/my_programm.svg" alt=""></a>
                </section>
            </section>
        </div>
    </main>

    <footer>
        <!-- Contacts -->
        <div class="contacts">
            <div class="social_media">
                <p>Контакты:</p>
                <a href="https://t.me/Xcvbnmzd"><img src="../img/tg.svg" alt=""></a>
                <a href="https://vk.com/id497007918"><img src="../img/vk.svg" alt=""></a>
            </div>
            <div class="email">
                <p>Email:</p>
                <a href="mailto:ivanbarbash06@gmail.com?subject=Вопрос по сайту">ivanbarbash06@gmail.com</a>
            </div>
        </div>
        <!-- About -->
        <div class="about">
            <p>Все права защищены &#169;</p>
            <p>Иван Барбашин</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script>
        // Button to see exercise info
        let infoExerciseButton = document.querySelectorAll('.workouts .slide .exercise_item .info');
        let closeInfoExerciseButton = document.querySelectorAll('.workouts .slide .exercise_item .info_close');
        let infoBlock = document.querySelectorAll('.workouts .slide .exercise_item .info_block');
        console.log(infoExerciseButton)

        for(let i = 0; i < infoExerciseButton.length; i++){
            infoExerciseButton[i].addEventListener('click', function(){
                infoBlock[i].style.cssText = `top: -1%;`;
            });
        }
        for(let i = 0; i < closeInfoExerciseButton.length; i++){
            closeInfoExerciseButton[i].addEventListener('click', function(){
                infoBlock[i].style.cssText = `top: -101%;`;
            });
        }

        
    </script>
</body>
</html>