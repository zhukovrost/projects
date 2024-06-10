<?php
require_once '../../config/settings.php'; // Подключение файла с настройками
require_once BASE_PATH . 'src/Helpers/func.php'; // Подключение файла с функциями
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head(2); // print head.php ?>
<body class="other-cover">
    <?php include BASE_PATH . "/templates/header.php"; // print header template ?>

    <main class="other-block">
        <div class="container">
			<section class="other-links">
				<section class="other-links__email">
					<p>Вопросы и пожелания:</p>
					<a class="footer__email-btn" href="mailto:ivanbarbash06@gmail.com?subject=Вопрос по сайту">ivanbarbash06@gmail.com</a>
				</section>
				<section class="other-links__about">
					<h1>Авторы проекта: Барбашин Иван и Жуков Ростислав</h1>
					<h2><p>Подробнее о проекте:</p> <a href="https://github.com/zhukovrost/projects/tree/new_sport">github</a></h2>
				</section>
			</section>
		</div>
    </main>

    <?php include BASE_PATH . "/templates/footer.html"; // print footer template ?>
</body>
</html>