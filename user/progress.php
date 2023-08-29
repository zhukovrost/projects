<?php
include "../templates/func.php";
include "../templates/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html" ?>

	<main>
		<div class="container progress_block">
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
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<!-- Exercise count of training -->
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<!-- Right part of last exercise item -->
							<div class="right">
								<!-- Muscle groups count of training -->
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<!-- Button 'Подробнее' for more info about exercise -->
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
								</div>
							</div>
						</section>
						<!-- Decoration line -->
						<div class="line"></div>
						<section class="item">
							<div class="left">
								<div>
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<div class="right">
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
								</div>
							</div>
						</section>
						<div class="line"></div>
						<section class="item">
							<div class="left">
								<div>
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<div class="right">
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
								</div>
							</div>
						</section>
						<div class="line"></div>
						<section class="item">
							<div class="left">
								<div>
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<div class="right">
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
								</div>
							</div>
						</section>
						<div class="line"></div>
                        <section class="item">
							<div class="left">
								<div>
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<div class="right">
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
								</div>
							</div>
						</section>
						<div class="line"></div>
                        <section class="item">
							<div class="left">
								<div>
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<div class="right">
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
								</div>
							</div>
						</section>
						<div class="line"></div>
                        <section class="item">
							<div class="left">
								<div>
									<img src="../img/time_black.svg" alt="">
									<p><span>65</span> мин</p>
								</div>
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>10</span> упражнений</p>
								</div>
							</div>
							<div class="right">
								<div>
									<img src="../img/cards_black.svg" alt="">
									<p><span>3</span> группы мышц</p>
								</div>
								<div>
									<button>Подробнее <img src="../img/more_white.svg" alt=""></button>
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
						<button>Добавить<img src="../img/add.svg" alt=""></button>
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
					  <button><img src="../img/my_programm_black.svg" alt=""></button>
				  </div>
				</div>
			  </section>
		</div>
	</main>

	<?php include "../templates/footer.html" ?>

	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-element-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
                backgroundColor: '#00C91D',
                color: '#000000',
                borderColor: '#000000'
            }]
            },
            options: {
                responsive: true,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(231, 231, 231, 1)'
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
                            color: 'rgba(231, 231, 231, 1)'
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
                    color: '#000000',
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
                            color: '#000000',
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
                backgroundColor: '#00C91D',
                color: '#000000',
                borderColor: '#000000'
            }]
            },
            options: {
                responsive: true,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: 'rgba(231, 231, 231, 1)'
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
                            color: 'rgba(231, 231, 231, 1)'
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
                        text: 'Вес - 2034',
                        font: {
                            size: 20,
                            family: 'Open Sans',
                        }               
                    }
                }
            },
        });

		// Height of friends block
        let friendsBlock = document.querySelector('.last_trainings');
        friendsBlock.style.cssText = `height: ${document.querySelector('.trainingChart').clientHeight}px;`;
    </script>
</body>
</html>