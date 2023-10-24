<?php
include "../templates/func.php";
include "../templates/settings.php";

if ($user_data->get_status() != "coach" && $user_data->get_status() != "doctor"){
    header("Location: search_users.php");
}
$requests = $user_data->get_requests();
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>

	<main class="users-list">
		<h1 class="users-list__title">Входящие заявки</h1>
		<div class="container">
            <?php
            if (count($requests) != 0){
                foreach ($requests as $user_block){
                    $user = new User($conn, $user_block['user']);
                    print_user_block_request($user->name, $user->surname, $user->get_avatar($conn), $user->get_id(), $user_block["id"]);
                }
            }else{ ?>
                <p>Нет заявок</p>
            <?php }
            ?>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>