<?php
include "../templates/func.php";
include "../templates/settings.php";
$request_flag = false;
if ($_GET["user"]){
    $user = new User($conn, $_GET["user"]);
    $user_data->set_staff($conn);
    $request_flag = $user_data->check_request($conn, $user->get_id());
}else{
    $user = $user_data;
}
$user->set_subscriptions($conn);
$user->set_subscribers($conn);
$user->set_staff($conn);

?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(); ?>
<body>
    <?php include "../templates/header.php" ?>

    <main class="user-news__cover">
        <div class="container">
			<section class="user-news">
				<!-- Button 'Новая запись' -->
				<!-- <?php if ($user->get_auth()){ ?>
					<button class="button-text user-news__add">Новая запись <img src="../img/add.svg" alt=""></button>
				<?php } ?> -->
				<!-- News item -->
				<?php
				if ($news = $user->get_my_news($conn)) {
					foreach ($news as $new){
						$date = date("d.m.Y", $new['date']);
						$replacements = array(
							"{{ user_name }}" => $new['name'].' '.$new['surname'],
							"{{ link }}" => '',
							"{{ date }}" => $date,
							"{{ message }}" => $new['message'],
							"{{ avatar }}" => $new['file']
						);
						echo render($replacements, "../templates/news_item.html");
					}
				}?>
			</section>
		</div>
    </main>

    <?php include "../templates/footer.html" ?>

</body>
</html>