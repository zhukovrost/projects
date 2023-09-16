<?php
include "../templates/func.php";
include "../templates/settings.php";

$user_data->set_program($conn);
$weekday = date("N") - 1;
$user_data->set_program($conn);
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body class="workout-session">
    <header class="workout-session__header">
        <a href="../index.php" class="header__item header__item--logo header__item--logo--workout">
            <img src="../img/logo.svg" alt="">
            <p>Training</p>
        </a>
        <section class="workout-session__navigation">
            <!-- Progress of test(in percents) -->
            <section class="workout-session__progress">
                <!-- Progress line and count of percent -->
                <div class="workout-session__progress-bar">
                    <h2 class="workout-session__progress-title">Progress</h2>
                    <div class="workout-session__progress-percents">
                        <p class="workout-session__percents-number">0%</p>
                        <div class="workout-session__finish-line"></div>
                    </div>
                </div>
                <!-- Navigation of test -->
                <nav class="workout-session__navigation">

                </nav>
            </section>
            <!-- Timer(rest time) -->
            <div class="workout-session__time">
                00:00
            </div>
        </section>
    </header>
    
    <main class="session-exercises">
        <swiper-container class="session-exercises__swiper" navigation="true">
            <swiper-slide class="session-exercises__slide">
                <section class="exercise-item exercise-item--list  exercise-item--session">
                    <!-- Exercise info -->
                    <button type="button" class="exercise-item__info-btn"><img src="../img/info.svg" alt=""></button>
                    <div class="exercise-item__info-content">
                        <button type="button" class="exercise-item__info-close"><img src="../img/close.svg" alt=""></button>
                        <p class="exercise-item__info-text">{{ description }}</p>
                    </div>
                    <!-- Exercise muscle groups -->
                    <div class="exercise-item__muscle-groups">{{ muscle }}</div>
                    <!-- Exercise image -->
                    <img class="exercise-item__img" src="{{ image }}" alt="">
                    <!-- Decoration line -->
                    <div class="exercise-item__line"></div>
                    <!-- Exercise title -->
                    <h1 class="exercise-item__title">{{ name }}</h1>
                    <div class="exercise-item__statistic">
                        <div class="exercise-item__rating">
                            <p class="exercise-item__score">{{ rating }}</p>
                            <img class="exercise-item__star" src="../img/Star.svg" alt="">
                        </div>
                        <div class="exercise-item__difficult">
                            <p class="exercise-item__difficult-number">{{ difficulty }}</p>
                            <div class="exercise-item__difficult-item"></div>
                        </div>
                    </div>
                    <div class="exercise-item__buttons">
                        {{ button }}
                        {{ button_featured }}
                    </div>
                </section>
            </swiper-slide>
            <swiper-slide class="session-exercises__slide">
                <section class="exercise-item exercise-item--list exercise-item--session">
                    <!-- Exercise info -->
                    <button type="button" class="exercise-item__info-btn"><img src="../img/info.svg" alt=""></button>
                    <div class="exercise-item__info-content">
                        <button type="button" class="exercise-item__info-close"><img src="../img/close.svg" alt=""></button>
                        <p class="exercise-item__info-text">{{ description }}</p>
                    </div>
                    <!-- Exercise muscle groups -->
                    <div class="exercise-item__muscle-groups">{{ muscle }}</div>
                    <!-- Exercise image -->
                    <img class="exercise-item__img" src="{{ image }}" alt="">
                    <!-- Decoration line -->
                    <div class="exercise-item__line"></div>
                    <!-- Exercise title -->
                    <h1 class="exercise-item__title">{{ name }}</h1>
                    <div class="exercise-item__statistic">
                        <div class="exercise-item__rating">
                            <p class="exercise-item__score">{{ rating }}</p>
                            <img class="exercise-item__star" src="../img/Star.svg" alt="">
                        </div>
                        <div class="exercise-item__difficult">
                            <p class="exercise-item__difficult-number">{{ difficulty }}</p>
                            <div class="exercise-item__difficult-item"></div>
                        </div>
                    </div>
                    <div class="exercise-item__buttons">
                        {{ button }}
                        {{ button_featured }}
                    </div>
                </section>
            </swiper-slide>
        </swiper-container>
    </main>

    <footer class="workout-session-footer">
        <h1 class="workout-session-footer__title">Осталось:</h1>
        <h2 class="workout-session-footer__item"><span>9</span> упражнений</h2>
        <h2 class="workout-session-footer__item"><span>9</span> подходов</h2>
        <button class="button-text workout-session-footer__button">Завершить</button>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
</body>
</html>