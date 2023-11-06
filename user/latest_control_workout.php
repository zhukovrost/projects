<?php
include "../templates/func.php";
include "../templates/settings.php";
$user1 = NULL;
$user2 = NULL;
$sportsmen = $user_data->get_sportsmen();
$is_valid1 = isset($_GET["user1"]) && is_numeric($_GET["user1"]) && in_array($_GET["user1"], $sportsmen);
$is_valid2 = isset($_GET["user2"]) && is_numeric($_GET["user2"]) && in_array($_GET["user2"], $sportsmen);
if ($is_valid1)
    $user1 = new User($conn, $_GET["user1"]);

if ($is_valid2)
    $user2 = new User($conn, $_GET["user2"]);

$sportsmen_advanced = $user_data->get_sportsmen_advanced($conn);
$flag_main = false;
if ($is_valid1 && $is_valid2){
    $user1_workouts = $user1->get_control_workouts($conn, NULL, 1);
    $user2_workouts = $user2->get_control_workouts($conn, NULL, 1);
    if (count($user1_workouts) > 0 && count($user2_workouts) > 0){
        $last_1 = $user1_workouts[0]->exercises;
        $last_2 = $user2_workouts[0]->exercises;
        $flag_main = true;
        foreach ($last_1 as $item1){
            $flag = false;
            foreach ($last_2 as $item2){
                if ($item1->get_id() == $item2->get_id()){
                    $flag = true;
                    break;
                }
            }
            if (!$flag){
                $flag_main = false;
                break;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>
	<main class="user-comparison">
		<div class="container">
			<section class="comparison-block">
				<p class="staff-block__title">Спортсмен</p>
                    <?php if ($user1 == NULL){ ?>
                        <section class="staff-block__header">
                            <button class="button-text comparison-block__add-button comparison-block__add-button--first"><p>Тренировки спортсмена</p></button>
                        </section>
                    <?php } else {
                        if ($is_valid2)
                            $reps = get_reps_for_comparison($user1, $conn, 1, $_GET["user2"]);
                        else
                            $reps = get_reps_for_comparison($user1, $conn, 1, NULL);
                        $reps["{{ exercises }}"] = '';
                        if ($flag_main){
                            foreach ($last_1 as $item){
                                $replaces = array(
                                    "{{ image }}" => $item->get_image($conn),
                                    "{{ name }}" => $item->name,
                                    "{{ rating }}" => $item->get_rating(),
                                    "{{ difficulty }}" => $item->difficulty,
                                    "{{ muscle }}" => '',
                                    "{{ description }}" => '',
                                    "{{ input }}" => $item->reps
                                );
                                $reps["{{ exercises }}"] .= render($replaces, "../templates/control_exercise.html");
                            }
                        }
                        echo render($reps, "../templates/latest_workout_block.html");
                    } ?>
			</section>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>

    <script>
        // Button to see exercise info
        let infoExerciseButton = document.querySelectorAll('.exercise-item__info-btn');
        let closeInfoExerciseButton = document.querySelectorAll('.exercise-item__info-close');
        let infoBlock = document.querySelectorAll('.exercise-item__info-content');

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


		//Difficult
		let difficultCountArr = document.querySelectorAll('.exercise-item__difficult-number');
		let difficultBlockArr = document.querySelectorAll('.exercise-item__difficult');

		for(let i = 0; i < difficultCountArr.length; i++){
			difficultBlockArr[i].innerHTML = '';
            for(let j = 0; j < 5; j++){
				let newElem = document.createElement('div');
				newElem.classList.add('exercise-item__difficult-item');
				if(j > Number(difficultCountArr[i].innerHTML) - 1){
					newElem.classList.add('exercise-item__difficult-item--disabled');
				}
				difficultBlockArr[i].appendChild(newElem);
			}
        }
    </script>
</body>
</html>