<?php
include "templates/func.php";
include "templates/settings.php";
$user_data->redirect_logged();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head("Welcome!", true);  ?>
<body>
    <!-- Welcome block first -->
    <section class="welcome_block_1">
        <!-- Welcome header -->
        <header class="welcome_header">
            <!-- Logo -->
            <a href="index.php" class="logo welcome_logo">
                <img src="img/logo.svg" alt="">
                <p>Training</p>
            </a>
            <a href="user/workout.php">
                <img src="img/workout.svg" alt="">
                <p>Тренировки</p>
            </a>
            <a href="">
                <img src="img/progress.svg" alt="">
                <p>Прогресс</p>
            </a>
            <a href="">
                <img src="img/exercises.svg" alt="">
                <p>Упражнения</p>
            </a>
            <a href="">
                <img class="other_img" src="img/other.svg" alt="">
                <p>Другое</p>
            </a>
            <a href="reg_log.php">Войти</a>

        </header>
        <!-- Title -->
        <div class="title">
            <h1>OpenDoor Training</h1>
            <h2>Твои цели - наши цели</h2>
        </div>
        <!-- Decoration images -->
        <button class="welcome_hand_img" ><img src="img/welcome_hand.svg" alt=""></button>
        <img class="welcome_biceps1" src="img/welcome_biceps_1.svg" alt="">
        <img class="welcome_biceps2" src="img/welcome_biceps_2.svg" alt="">
    </section>
    <!-- Subtitle -->
    <h1 class="welcome_subtitle">Удобно. Просто. Эффективно</h1>

    <!-- WELCOME block second -->
    <section class="welcome_block_2">
        <!-- Title -->
        <div class="title">
            <h1>ВЫБИРАЙ</h1>
            <h2>или</h2>
            <h1>СОЗДАВАЙ</h1>
        </div>
        <section class="content">
            <!-- Item -->
            <section class="exercise_item">
                <!-- Exercise info -->
                <button class="info"><img src="img/info.svg" alt=""></button>
                <div class="info_block">
                    <button class="info_close"><img src="img/close.svg" alt=""></button>
                    <p>Встаньте в упор лежа, ладони в 10 см от друг друга. Сделайте отжимание с касанием грудью пола и выпрямьте руки.</p>
                </div>
                <!-- Exercise muscle groups -->
                <div class="muscle_groups">Руки - плечи - грудь</div>
                <!-- Exercise image -->
                <img class="exercise_img" src="img/exercises/arms/triceps_2.jpg" alt="">
                <!-- Decoration line -->
                <div class="line"></div>
                <!-- Exercise title -->
                <h1>Алмазные отжимания</h1>
                <div class="statistic">
                    <div class="rating">
                        <p>4,5</p>
                        <img src="img/Star.svg" alt="">
                    </div>
                    <div class="difficult">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div class="disabled"></div>
                    </div>
                </div>
                <div class="buttons">
                    <button class="add">Добавить <img src="img/add.svg" alt=""></button>
                    <button class="favorite"><img src="img/favorite.svg" alt=""></button>
                </div>
            </section>
            <section class="exercise_item add_exercise_item">
                <!-- Add photo button -->
                <button class="add_photo">Добавить фото</button>
                <!-- Decoration -->
                <div class="line"></div>
                <div class="content">
                    <!-- Add muscle groups -->
                    <div class="add_muscle_groups">
                        <p>Группы мышц</p>
                        <button><img src="img/add_black.svg" alt=""></button>
                    </div>
                    <!-- Name of exercise -->
                    <div class="name">
                        <p>Название</p>
                        <input type="text" placeholder="Не более 250 символов">
                    </div>
                    <!-- Info of exercise -->
                    <div class="info_text">
                        <p>Описание</p>
                        <textarea name="add_exercise_info"></textarea>
                    </div>
                    <!-- Button 'Add' exercise -->
                    <button class="add">Создать <img src="img/add.svg" alt=""></button>
                </div>
            </section>
        </section>
    </section>

    <!-- WELCOME block third -->
    <section class="welcome_block_3">
        <div class="container">
            <!-- Title -->
            <h1 class="title">ПОДРОБНАЯ СТАТИСТИКА</h1>
            <!-- First part of statistic(trainings diagram & lasr trainings) -->
            <section class="statistic_block">
                <!-- Count of trainings chart -->
                <section class="trainingChart">
                    <canvas id="trainingStatisticChart"></canvas>
                </section>
                <!-- Last trainings block -->
                <section class="last_trainings">
                    <!-- Title -->
                    <h2>Последние тренировки</h2>
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
                                    <button>Подробнее <img src="img/more.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <!-- Decoration line -->
                        <div class="line"></div>
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
                                    <button>Подробнее <img src="img/more.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <div class="line"></div>
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
                                    <button>Подробнее <img src="img/more.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                        <div class="line"></div>
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
                                    <button>Подробнее <img src="img/more.svg" alt=""></button>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </section>

            <!-- Second part of statistic(training info and muscles & physical diagram) -->
            <section class="statistic_block second">
                <!-- Training info -->
                <section class="training">
                    <!-- Muscle groups diagram -->
                    <div class="info">
                        <section class="muscle_groups">
                            <h2>Группы мышц</h2>
                            <canvas id="muscleGroupsChart"></canvas>
                        </section>
                        <!-- Statistic info -->
                        <section class="statistic">
                            <p>Тренировки: <span>156</span></p>
                            <p>Время: <span>1247 мин</span></p>
                            <p>Программы: <span>3</span></p>
                            <p>Упражнений: <span>129</span></p>
                        </section>
                    </div>
                    <!-- Current info -->
                    <section class="physical_info">
                        <p>Вес: 80 кг</p>
                        <p>Рост: 180 см</p>
                        <button>Добавить<img src="img/add_black.svg" alt=""></button>
                    </section>
                </section>
                <!-- Physical block -->
                <section class="physical_data">
                    <!-- Navigation -->
                    <nav>
                        <!-- Year & month & week -->
                        <select name="" id="">
                            <option value="value1" selected>Год</option>
                            <option value="value2">Месяц</option>
                            <option value="value3">Неделя</option>
                        </select>
                        <!-- Button to other physic(weight or length) -->
                        <button>РОСТ</button>
                    </nav>
                    
                    <!-- Diagram swiper -->
                    <swiper-container class="mySwiper" navigation="true">
                        <swiper-slide>
                            <div class="chart">
                                <canvas id="weightDataChart"></canvas>
                            </div>
                        </swiper-slide>
                        <swiper-slide>
                            <div class="chart">
                                <canvas id="lengthDataChart"></canvas>
                            </div>
                        </swiper-slide>
                    </swiper-container>
                </section>
            </section>
            <section class="programm_progress">
                <!-- Progress line and count of percent -->
                <div class="progress_bar">
                  <h2>Моя программа</h2>
                  <div class="info">
                    <div class="percents">
                        <p>40%</p>
                        <div class="finish"></div>
                      </div>
                      <button><img src="img/my_programm.svg" alt=""></button>
                  </div>
                </div>
              </section>
        </div>
    </section>

    <!-- WELCOME block fourth -->
    <section class="welcome_block_4">
        <!-- Title -->
        <h1>Следи за друзьями</h1>
        <!-- user profile -->
        <div class="user_profile">
            <!-- Avatar block -->
            <section class="avatar">
                <!-- avatar image -->
                <img src="img/man_avatar.svg" alt="">
                <!-- name -->
                <h2>Иван Иванов</h2>
                <!-- last training time -->
                <p>Тренировался: 1 день назад</p>
                <!-- Button 'подписаться' -->
                <button>Подписаться <img src="img/add_black.svg" alt=""></button>
            </section>
            <!-- info block -->
            <section class="info">
                <!-- text about user -->
                <div class="about">
                    <h3>О себе</h3>
                    <p>Занимаюсь спортом, жму 40. Буду рад знакомству!</p>
                </div>
                <!-- Follow & followers -->
                <div class="followers">
                    <a href=""><span>25</span> подписчиков</a>
                    <a href=""><span>15</span> подписок</a>
                </div>
            </section>
        </div>
    </section>

    <!-- WELOCOME block fifth -->
    <section class="welcome_block_5">
        <!-- Decoration circles -->
        <div class="first"></div>
        <div class="second"></div>
        <div class="third"></div>
        <div class="fourth"></div>
        <!-- Titles -->
        <h1>ТРЕНИРУЙСЯ</h1>
        <h1>СОВЕРШЕНСТВУЙСЯ</h1>
        <h1>ДОСТИГАЙ ЦЕЛЕЙ</h1>
    </section>

    <!-- WELOCOME block sixth -->
    <section class="welcome_block_6">
        <!-- Title -->
        <h1>Кроссплатформенность</h1>
        <!-- Content -->
        <div class="content">
            <img src="img/telephone.svg" alt="">
            <h2>ДОСТУП</h2>
            <img src="img/computer.svg" alt="">
        </div>
        <h2>с любого устройства</h2>
    </section>

    <!-- WELOCOME block seventh -->
    <section class="welcome_block_7">
        <div class="container">
            <!-- Level of user -->
        <div class="level">
            <!-- Title -->
            <h1>Подойдёт любому</h1>
            <!-- Users level block -->
            <div class="users">
                <!-- Beginner -->
                <div class="item">
                    <img src="img/beginner.svg" alt="">
                    <h3>Новичок</h3>
                </div>
                 <!-- Professional -->
                <div class="item professional">
                    <img src="img/professional.svg" alt="">
                    <h3>Профессионал</h3>
                </div>
                <div class="item">
                    <img src="img/advanced.svg" alt="">
                    <h3>Продвинутый</h3>
                </div>
            </div>
            <h2>Уровень подготовки не важен</h2>
            <!-- Decoration -->
            <div class="line"></div>
        </div>
        <div class="chats">
            <div class="profiles">
                <div class="sportsman">
                    <img class="arrow_coach" src="img/arrow_coach.svg" alt="">
                    <img class="arrow_doctor" src="img/arrow_doctor.svg" alt="">
                    <img src="img/sportsman.svg" alt="">
                    <p>Спортсмен</p>
                </div>
                <div class="personal">
                    <div class="item">
                        <img src="img/coach.svg" alt="">
                        <p>Тренер</p>
                    </div>
                    <div class="item">
                        <img src="img/doctor.png" alt="">
                        <p>Врач</p>
                    </div>
                </div>
            </div>
            <div class="info">
                <h2>Платформа облегчает процесс взаимодействия с тренером и лечащим врачом</h2>
                <div class="content">
                    <ul>
                        <li>чат с тренером и врачом</li>
                        <li>просмотр тренировок, расписания и программ от тренера</li>
                        <li>просмотр назначенных лекарств от врача</li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- WELOCOME block eighth -->
    <section class="welcome_block_8">
        <h1>ИСПЫТАЙ</h1>
        <h1>ВСЕ ВОЗМОЖНОСТИ</h1>
        <h1>ПЛАТФОРМЫ</h1>
    </section>

    <!-- WELOCOME block nineth -->
    <section class="welcome_block_9">
        <!-- Title -->
        <div class="title">
            <img src="img/goal.svg" alt="">
            <h1>Начни совершенствоваться прямо сейчас</h1>
        </div>
        <!-- Button to login -->
        <div class="content">
            <h2>Повышаем <span>эффективность</span> и <span>удобство</span> ваших тренировок</h2>
            <a href="reg_log.php">Начать <img src="img/start_arrow.svg" alt=""></a>
        </div>
    </section>

    <footer>
        <!-- Contacts -->
        <div class="contacts">
            <div class="social_media">
                <p>Контакты:</p>
                <a href="https://t.me/Xcvbnmzd"><img src="img/tg.svg" alt=""></a>
                <a href="https://vk.com/id497007918"><img src="img/vk.svg" alt=""></a>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Decorative hand
        let decorativeHand = document.querySelector('.welcome_hand_img');

        decorativeHand.addEventListener('click', function(){
            document.querySelector('.welcome_block_2').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });


        // Buttons for exercise info
        let infoExerciseButton = document.querySelector('.welcome_block_2 .content .exercise_item .info');
        let closeInfoExerciseButton = document.querySelector('.welcome_block_2 .exercise_item .info_close');
        let infoBlock = document.querySelector('.welcome_block_2 .exercise_item .info_block');

        infoExerciseButton.addEventListener('click', function(){
            infoBlock.style.cssText = `top: -1%;`;
        });

        closeInfoExerciseButton.addEventListener('click', function(){
            infoBlock.style.cssText = `top: -101%;`;
        });

        // Count of training chart
        const ctx1 = document.getElementById('trainingStatisticChart');

        new Chart(ctx1, {
            type: 'line',
            data: {
            labels: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            datasets: [{
                label: 'Колво тренировок',
                data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
                borderWidth: 3,
                backgroundColor: '#ffffff',
                color: '#ffffff',
                borderColor: '#ffffff'
            }]
            },
            options: {
                responsive: true,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(255, 255, 255, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'Месяца',
                            font: {
                                size: 16,
                                family: 'Open Sans',
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: true,
                            color: 'rgba(255, 255, 255, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'Количество тренировок',
                            font: {
                                size: 16,
                                family: 'Open Sans',
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Количество тренировок - 2023',
                        font: {
                            size: 20,
                            family: 'Open Sans',
                        }               
                    }
                }
            },
        });


        // Muscle groups chart
        const ctx2 = document.getElementById('muscleGroupsChart');

        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Руки', 'Ноги', 'Спина'],
                datasets: [{
                    label: 'Количество упражнений',
                    data: [12, 19, 3],
                    borderWidth: 1,
                    color: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                        align: 'center',
                        labels: {
                            font: {
                                family: 'Open Sans',
                                size: 20,
                            },
                            color: '#dadada',
                        },
                    },
                title: {
                    display: false,
                }
                }
            },
        });


        // Physical data chart
        const ctx3 = document.getElementById('weightDataChart');

        new Chart(ctx3, {
            type: 'line',
            data: {
            labels: ['Я', 'Ф', 'М', 'А', 'М', 'И', 'И', 'А', 'С', 'О', 'Н', 'Д'],
            datasets: [{
                label: 'Вес за неделю',
                data: [80, 81, 83, 79],
                borderWidth: 3,
                backgroundColor: '#ffffff',
                color: '#00CD1E',
                borderColor: '#ffffff'
            }]
            },
            options: {
                responsive: true,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(255, 255, 255, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'Месяц',
                            font: {
                                size: 16,
                                family: 'Open Sans',
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: true,
                            color: 'rgba(255, 255, 255, 0.2)'
                        },
                        title: {
                            display: true,
                            text: 'Вес (кг)',
                            font: {
                                size: 16,
                                family: 'Open Sans',
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Вес - 2034',
                        font: {
                            size: 20,
                            family: 'Open Sans',
                        }               
                    }
                }
            },
        });
    </script>
</body>
</html>