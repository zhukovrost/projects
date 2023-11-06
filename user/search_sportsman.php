<?php
include "../templates/func.php";
include "../templates/settings.php";

if ($user_data->get_status() != "coach" && $user_data->get_status() != "doctor")
    header("Location: ../index.php");

$sportsmen = $user_data->get_sportsmen_advanced($conn);

?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>

	<nav class="users-search-nav">
		<div class="container">
			<!-- Buttons to (sub / unsub) users -->
            <a class="button-text users-search-nav__item" href="requests.php">Новые заявки (<?php echo count($user_data->get_requests()); ?>)<img src="../img/application.svg" alt=""></a>
			<!-- Exercise search -->
            <!--
			<form method="post" class="users-search-nav__search">
				<input class="users-search-nav__search-input" type="text" placeholder="Искать" name="search">
				<button class="users-search-nav__search-button"><img src="../img/search_black.svg" alt=""></button>
			</form>
			-->
		</div>
	</nav>

	<main class="users-list">
		<div class="container">
            <?php foreach ($sportsmen as $sportsman) print_sportsman_block($conn, $sportsman); ?>
		</div>
        <?php if (count($sportsmen) == 0){ ?>
            <p class="users-list__title-none">Пользователи не найдены</p>
        <?php } ?>

    <?php include "../templates/footer.html" ?>
</body>
</html>