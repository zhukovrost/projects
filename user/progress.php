<?php
include "../templates/func.php";
include "../templates/settings.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
	<?php include "../templates/header.html" ?>

	<main class="progress-block">
		<div class="container">
			<!-- First part of statistic(trainings diagram & lasr trainings) -->
			<section class="progress-block__trainings">
				<!-- Count of trainings chart -->
				<section class="progress-block__trainings-chart">
					<canvas id="trainingStatisticChart"></canvas>
				</section>
				<!-- Last trainings block -->
                <?php $user_data->print_workout_history($conn); ?>
			</section>

			<!-- Second part of statistic(training info and muscles & physical diagram) -->
			<section class="progress-block__info">
				<!-- Training info -->
				<section class="progress-block__workouts">
					<!-- Muscle groups diagram -->
					<div class="progress-block__workouts-info">
						<section class="progress-block__muscle-groups">
							<h2 class="progress-block__muscle-groups-title">Группы мышц</h2>
							<canvas class="progress-block__muscle-groups-chart" id="muscleGroupsChart"></canvas>
						</section>
						<!-- Statistic info -->
						<section class="progress-block__workouts-statistic">
							<p class="progress-block__workouts-statistic-item">Тренировки: <span>156</span></p>
							<p class="progress-block__workouts-statistic-item">Время: <span>1247 мин</span></p>
							<p class="progress-block__workouts-statistic-item">Программы: <span>3</span></p>
							<p class="progress-block__workouts-statistic-item">Упражнений: <span>129</span></p>
						</section>
					</div>
					<!-- Current info -->
					<section class="progress-block__physical-info">
						<p class="progress-block__physical-info-item">Вес: 80 кг</p>
						<p class="progress-block__physical-info-item">Рост: 180 см</p>
						<button class="button-text progress-block__physical-info-button">Добавить<img src="../img/add.svg" alt=""></button>
					</section>
				</section>
				<!-- Physical block -->
				<section class="progress-block__physical-data">
					<!-- Navigation -->
					<nav class="progress-block__physical-data-navigation">
						<!-- Year & month & week -->
						<select class="progress-block__physical-data-select" name="" id="">
							<option value="value1" selected>Год</option>
							<option value="value2">Месяц</option>
							<option value="value3">Неделя</option>
						</select>
						<!-- Button to other physic(weight or length) -->
						<button class="button-text progress-block__physical-data-button">РОСТ</button>
					</nav>
					
					<!-- Diagram swiper -->
					<swiper-container class="progress-block__physical-data-swiper" navigation="true">
						<swiper-slide class="progress-block__physical-data-slide">
							<div class="progress-block__physical-data-chart">
								<canvas id="weightDataChart_1"></canvas>
							</div>
						</swiper-slide>
						<swiper-slide class="progress-block__physical-data-slide">
							<div class="progress-block__physical-data-chart">
								<canvas id="weightDataChart_2"></canvas>
							</div>
						</swiper-slide>
					</swiper-container>
				</section>
			</section>
			<section class="progress-block__programm">
				<!-- Progress line and count of percent -->
				<div class="progress-block__programm-progress">
				  <h2 class="progress-block__programm-title">Моя программа</h2>
				  <div class="progress-block__programm-info">
					<div class="progress-block__programm-line">
						<p class="progress-block__programm-percents">40%</p>
						<div class="progress-block__programm-finish" class="finish"></div>
					</div>
					<a class="progress-block__programm-button" href="my_program.php"><img src="../img/my_programm_black.svg" alt=""></a>
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