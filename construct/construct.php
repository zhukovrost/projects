<?php
if (!isset($_SESSION)) { 
	session_start(); 
}
include '../func.php';
include '../settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);

$error_array = array(
	"success_add_test" => false,
	"image_error" => false,
	"image_success" => false,
	"success_add_questions" => false
);

#start 

#------------------- clear function -------------------------

if (empty($_POST['clear'])){
	if (isset($_SESSION['result_array'])){
		$result_array = $_SESSION['result_array'];
	}else{
		$result_array = array();
	}
}else{
	$_SESSION['result_array'] = [];
	$result_array = [];
	$_POST = [];
}

#------------------ change form type -----------------------

if (empty($_GET['form_type'])){
	if (empty($_SESSION['form_type'])){ 
		$form_type = "radio"; 
	}else{
		$form_type = $_SESSION['form_type'];
	}
}else{
	$form_type = $_GET['form_type'];
	$_SESSION['form_type'] = $_GET['form_type'];
}

#--------------- change amount of questions ------------------

if (empty($_GET['num_of_questions'])){
	if (empty($_SESSION['num_of_questions'])){ 
		$num_of_questions = 4;
	}else{
		$num_of_questions = $_SESSION['num_of_questions'];
	}
}else{
	$num_of_questions = $_GET['num_of_questions'];
	$_SESSION['num_of_questions'] = $_GET['num_of_questions'];
}

#--------------- delete question -------------------

if (isset($_POST['num_for_delete'])){	
	$nwra1 = array();
	$num_del = $_POST['num_for_delete'] - 1;
	unset($_POST['num_for_delete']);
	for ($i = 0; $i < $num_del; $i++){ array_push($nwra1, $result_array[$i]); }
	for ($i = $num_del + 1; $i < count($result_array); $i++){ array_push($nwra1, $result_array[$i]); }
	$result_array = $nwra1;
	$_SESSION['result_array'] = $nwra1;
}

#-------------- add to db ----------------

if (isset($_POST['form_add_done'])){

#------------------ add image ---------------------------

	if (!empty($_FILES["add_img"]["tmp_name"])){
		if ($_FILES["add_img"]["tmp_name"] != ""){
			if ($_FILES["add_img"]["error"] == 0){
				if (conn_check($conn)){
					$img = addslashes(file_get_contents($_FILES["add_img"]["tmp_name"]));
					$add_image_sql = "INSERT INTO test_images(image) VALUES('$img')";
					if($conn->query($add_image_sql)){
						$_FILES = array();
						$error_array["image_success"] = true;
					}
				} else{
					$error_array["image_error"] = true;
				}
			}else{
				$error_array["image_error"] = true;
			}
		}
	}

#------------------- add image to question --------------------------

	$img_id = null; 

	if ($error_array["image_success"] && conn_check($conn)){
		$get_img_id_sql = "SELECT id FROM test_images ORDER BY id DESC LIMIT 1";
		if($result = $conn->query($get_img_id_sql)){
			foreach($result as $note){
				$img_id = $note["id"];
			}
			$result -> free();
		} else{
			$img_id = null; 
			$error_array["image_error"] = true;
		}
	}
	
#------------------ add question ----------------------------

	if ($form_type != "definite_mc"){
		if ($form_type != "checkbox"){
			$construct = [$_POST['question'], $num_of_questions, $_POST['num_of_right_answer'], [], $form_type, $img_id];
		}else{
			$construct = [$_POST['question'], $num_of_questions, explode(" ", $_POST['num_of_right_answer']), [], $form_type, $img_id];
		}
		for ($i = 0; $i < $num_of_questions; $i++){
			$variant = $_POST[$i];
			array_push($construct[3], $variant);
			unset($_POST[$i]);
		}
	}else{
		$construct = [$_POST['question'], null, null, null, $form_type];
	}

	unset($_POST['question'], $_POST['c_form_add_done']);
	array_push($result_array, $construct);
	$_SESSION['result_array'] = $result_array;
}

#-------------- add test to db ------------------

if (isset($_POST['add_test_to_db'])){
	if (conn_check($conn)){
		$add_test_sql = "INSERT INTO tests(name, test) VALUES('".$_POST['test_name']."', '".json_encode($result_array, JSON_UNESCAPED_UNICODE)."')";
		if($conn->query($add_test_sql)){
			$error_array["success_add_test"] = true;
		} else{
			echo "Ошибка: " . $conn->error;
		}
	}
}

#----------------- add questions to db -------------------------

if (isset($_POST['add_questions_to_db']) || isset($_POST['add_test_to_db'])){
	if (conn_check($conn)){
		foreach ($result_array as $question_to_db){
			$add_questions_sql = "INSERT INTO questions(question) VALUES('".json_encode($question_to_db, JSON_UNESCAPED_UNICODE)."')";
			if (!$conn->query($add_questions_sql)) {
              echo "Ошибка: " . $conn->error;
            }
		}
		unset($_POST['test_name']);
		$error_array["success_add_questions"] = true;
		$_SESSION['result_array'] = [];
		$result_array = array();
	}
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../css/style.css">
	<title>Constructor</title>
</head>
<body>
	<div class="c_all_forms_block">
		<form enctype="multipart/form-data" method="post" class="c_form_1">
			<h3>Добавить вопрос</h3>
			<textarea name="question"></textarea>
			<br>
			<?php 
			if ($form_type != "definite_mc"){
				for ($i = 0; $i < $num_of_questions; $i++){
					echo "<input style='width: 90%' name='".$i."' type='text' placeholder='Вариант ответа №".(intval($i) + 1)."'>\n<br>";
				}
			} 
			?>
			<h4>Добавить картинку</h4>
			<input type="file" name="add_img">
			<?php
			if ($form_type == "radio"){
				echo '<h4>Номер правильного ответа</h4><input type="number" name="num_of_right_answer" style="width: 30%">';
			}	
			if ($form_type == "checkbox"){
				echo '<h4>Номера правильных ответов</h4><input type="text" name="num_of_right_answer" style="width: 30%"><p>Записывать через пробел</p>';
			}	
			?>
			<input type="submit" name="form_add_done" value="Создать">
		</form>
		<form method="get" class="c_form_3">
			<h3>Тип вопроса</h3>
			<p><input type="radio" name="form_type" value="radio"<?php if ($form_type == "radio"){ echo " checked"; } ?>>С одним вариантом овета</p>
			<p><input type="radio" name="form_type" value="checkbox"<?php if ($form_type == "checkbox"){ echo " checked"; } ?>>С несколькими вариантами ответа</p>
			<p><input type="radio" name="form_type" value="definite"<?php if ($form_type == "definite"){ echo " checked"; } ?>>С определённым ответом</p>
			<p><input type="radio" name="form_type" value="definite_mc"<?php if ($form_type == "definite_mc"){ echo " checked"; } ?>>С определённым ответом (Ручная проверка)</p>
			<input type="submit" value="изменить" style="width: 90%">
		</form>
		<form method="get" class="c_form_2">
			<h3>Количество ответов</h3>
			<input type="number" name="num_of_questions" value="<?php echo $num_of_questions; ?>">
			<input type="submit" value="изменить">
		</form>
		<form method="post" class="c_form_2">
			<h3>Удалить вопрос</h3>
			<input type="number" name="num_for_delete">
			<input type="submit" value="Удалить вопрос">
		</form>
		<form method="post" class="c_form_2">
			<h3>Задайте название тестирования</h3>
			<textarea name="test_name"></textarea>
			<input type="submit" name="add_test_to_db" value="Добавить тест в бд">
			<input type="submit" name="add_questions_to_db" value="Добавить вопросы в бд">
		</form>
	</div>
		  <?php
#---------------- errors and successes ---------------------------

	if ($error_array["success_add"] || $error_array["image_error"] || $error_array["image_success"] || $error_array["success_add_questions"]){

		echo "<div class='c_error_array'>\n";

		if ($error_array["success_add_test"]){
			echo "<p class='success'>Тест был успешно добавлен в базу данныx</p>";
		}
		if ($error_array["image_error"]){
			echo "<p class='warning'>Ошибка загрузки картинки</p>";
		}

		if ($error_array["image_success"]){
			echo "<p class='success'>Картинка была успешно добавлена (ID:".$img_id.")</p>";
		}

		if ($error_array["success_add_questions"]){
			echo "<p class='success'>Вопросы были успешно добавлены в базу данныx</p>";
		}

		echo "</div>\n";

	}	
#-------------------- preview -------------------

 
	for ($i = 0; $i < count($result_array); $i++){
		$question = $result_array[$i];
		$preview_type = $question[4];
		echo '<div class="c_preview_all"><form class="c_preview '.$preview_type.'">';
		echo '<p>Вопрос №'.($i + 1).': '.$question[0].'</p>';
		if ($preview_type == "radio" || $preview_type == "checkbox"){
			foreach ($question[3] as $answer){
				echo '<p><input type="'.$preview_type.'">'.$answer.'</p>';
			}
		}else if ($preview_type == "definite"){
			echo '<input type="text" style="width: 70%">';
		}else if ($preview_type == "definite_mc"){
			echo "<textarea style='width: 70%; height: 100px'></textarea>";
		}
		echo '</form><div class="c_preview_info '.$preview_type.'">';
		echo ' <p>Тип вопроса: ';
		switch($preview_type){
			case "radio":
				echo "c одним вариантом ответа. ".$question[1]." всего.";
				break;
			case "checkbox":
				echo "c несколькими вариантами ответов. ".$question[1]." всего.";
				break;
			case "definite":
				echo "c определённым ответом.";
				break;
			case "definite_mc":
				echo "c определённым ответом (ручная проверка).";
				break;
		}
		echo "</p>";

		if ($question[5] != null){
			echo "<p>ID картинки: ".$question[5]."</p>";
		}else{
			echo "<p>Картинки нет</p>";
		}

		switch($preview_type){
			case "radio":
				echo "<p>Правильный ответ: ".$question[3][$question[2]]."</p>";
				break;
			case "checkbox":
				echo "<p>Правильные ответы(".count($question[2])."):</p>";
				for ($j = 0; $j < count($question[2]); $j++){
					echo "<p>".($j + 1).". ".$question[3][$question[2][$j]]."</p>";
				}
				break;
			case "definite":
				echo "<p>Правильные ответы(".$question[1]."):</p>";
				for ($j = 0; $j < $question[1]; $j++){
					echo "<p>".($j + 1).". ".$question[3][$j]."</p>";
				}
				break;
		}

		echo '</div></div>';
	}

	$conn -> close();
	
			?>
		<form method="post">
			<input type="submit" name="clear" value="очистить всё">
		</form>
	
		
</body>
</html>