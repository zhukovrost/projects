<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // head.php ?>
<body>
    <?php include BASE_PATH . "/templates/header.php"; //print header templat ?>

    <main class="user-news__cover">
        <div class="container">
			<section class="user-news">
				<!-- News item -->
				<?php
				if ($news = $user_data->get_news($conn)) { // Fetch news items for the user from the database using $user_data->get_news($conn)
					foreach ($news as $new){  // Iterate through each news item retrieved from the database
						$date = date("d.m.Y", $new['date']); // Format the date in 'd.m.Y' (day.month.year) format
						$replacements = array( // Define replacement values for placeholders in the HTML template
							"{{ user_name }}" => $new['name'].' '.$new['surname'],
							"{{ link }}" => '',
							"{{ date }}" => $date,
							"{{ message }}" => $new['message'],
							"{{ avatar }}" => $new['file']
						);
						echo render($replacements, BASE_PATH . "templates/news_item.html"); // Render the HTML template ('../templates/news_item.html') with the defined replacements
					}
				}?>
			</section>
		</div>
    </main>

    <?php include BASE_PATH . "/templates/footer.html"; // footer templat ?>

</body>
</html>