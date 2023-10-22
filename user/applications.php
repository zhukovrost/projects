<?php
include "../templates/func.php";
include "../templates/settings.php";

$users_array = array();
if (isset($_POST['search']) && trim($_POST['search']) != ""){
    $searches = explode(' ', $_POST['search']);
    foreach ($searches as $search){
        $sql = "SELECT users.name, users.surname, users.id, avatars.file FROM users INNER JOIN avatars ON users.avatar=avatars.id WHERE users.login LIKE '$search' OR users.name LIKE '$search' OR users.surname LIKE '$search'";
        $result = $conn->query($sql);
        foreach ($result as $item){
            array_push($users_array, $item);
        }
    }
}

$user_data->set_subscriptions($conn);
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
            if (count($users_array) != 0){
                foreach ($users_array as $user){
                    if ($user_data->get_auth())
                        print_user_block($user['name'], $user['surname'], $user['file'], $user['id'], array_search($user['id'], $user_data->subscriptions));
                    else
                        print_user_block($user['name'], $user['surname'], $user['file'], $user['id'], false);
                }
            }
            ?>
		</div>
	</main>

    <?php include "../templates/footer.html" ?>
</body>
</html>