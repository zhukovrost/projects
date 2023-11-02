<?php
include "../templates/func.php";
include "../templates/settings.php";
$user1 = NULL;
$user2 = NULL;
$sportsmen = $user_data->get_sportsmen();
if (isset($_GET["user1"]) && is_numeric($_GET["user1"]) && in_array($_GET["user1"], $sportsmen))
    $user1 = new User($conn, $_GET["user1"]);

if (isset($_GET["user2"]) && is_numeric($_GET["user2"]) && in_array($_GET["user1"], $sportsmen))
    $user2 = new User($_GET["user2"]);
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>

	<main class="user-comparison">
		<div class="container">
			<section class="comparison-block">
				<p class="staff-block__title">Первый спортсмен</p>
                    <?php if ($user1 == NULL){ ?>
                        <section class="staff-block__header">
                            <button class="button-text comparison-block__add-button"><p>Добавить спортсмена</p> <img src="../img/add.svg" alt=""></button>
                        </section>
                    <?php } else {
                        $reps = get_reps_for_comparison($user1, $conn);
                    } ?>
			</section>
			<section class="comparison-block">
				<p class="staff-block__title">Второй спортсмен</p>
                    <?php if ($user1 == NULL){ ?>
                        <section class="staff-block__header">
                            <button class="button-text comparison-block__add-button"><p>Добавить спортсмена</p> <img src="../img/add.svg" alt=""></button>
                        </section>
                    <?php } else {
                        $reps = get_reps_for_comparison($user2, $conn);
                    } ?>
			</section>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>