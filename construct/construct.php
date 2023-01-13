<?php
if (!isset($_SESSION)) { 
	session_start(); 
}
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);

# if (!(check_if_admin($conn, $_COOKIE['login'], "../"))){ header("Location: ../index.php"); }

$error_array = array(
	"success_add_test" => false,
	"image_error" => false,
	"image_success" => false,
	"success_add_questions" => false,
  "theme_error" => false,
  "theme_success" => false,
  "random_error" => false
);

#start 

#------------------- clear function -------------------------

if (empty($_POST['clear'])){
	if (isset($_SESSION['result_array'])){
		$result_array = $_SESSION['result_array'];
	}else{
		$result_array = array();
	}
  if (isset($_SESSION['themes_array'])){
    $themes_array = $_SESSION['themes_array'];
  }else{
    $themes_array = array();
  }
  if (empty($_SESSION['to_send'])){
    $_SESSION['to_send'] = array();
  }
}else{
	$_SESSION['result_array'] = [];
	$result_array = [];
  $_SESSION['themes_array'] = [];
  $themes_array = [];
  $_SESSION['to_send'] = array();
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
  if ($_POST['num_for_delete'] != "" || $_POST['num_for_delete'] != null){
    $num_del = $_POST['num_for_delete'] - 1;

    $nwra1 = array();
    for ($i = 0; $i < $num_del; $i++){ array_push($nwra1, $result_array[$i]); }
    for ($i = $num_del + 1; $i < count($result_array); $i++){ array_push($nwra1, $result_array[$i]); }
    $result_array = $nwra1;
    $_SESSION['result_array'] = $nwra1;

    $nwra2 = array();
    for ($i = 0; $i < $num_del; $i++){ array_push($nwra2, $_SESSION['to_send'][$i]); }
    for ($i = $num_del + 1; $i < count($_SESSION['to_send']); $i++){ array_push($nwra2, $_SESSION['to_send'][$i]); }
    $_SESSION['to_send'] = $nwra2;

    unset($_POST['num_for_delete']);
  }
}

# ----------------------- add random questions -----------------------

if (isset($_POST['num_of_rand_questions'])){
  if ($_POST['theme_of_rand_questions'] != "" || $_POST['theme_of_rand_questions'] != null){
    if ($_POST['theme_of_rand_questions'] == "all_themes"){
      $select_rand_sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 1";
    }else{
      $select_rand_sql = "SELECT * FROM questions WHERE theme='".$_POST['theme_of_rand_questions']."' ORDER BY RAND() LIMIT 1";
    }
    for ($i = 0; $i < $_POST['num_of_rand_questions']; $i++){
      if ($result = $conn->query($select_rand_sql)){
        foreach ($result as $rand_question_query){
          $rand_question = json_decode($rand_question_query['question'], null, 512, JSON_UNESCAPED_UNICODE);
          array_push($result_array, $rand_question);
          array_push($_SESSION['to_send'], false);
          array_push($themes_array, $rand_question_query['theme']);
        }
      }else{
        $error_array["random_error"] = true;
      }
    }
    $result->free();
    $_SESSION['themes_array'] = $themes_array;
    $_SESSION['result_array'] = $result_array;
  }
}

#-------------- add to db ----------------

if (isset($_POST['add_theme'])){
  if ($_POST['add_theme'] != "" || $_POST['add_theme'] != null){
    $add_theme_sql = "INSERT INTO themes(theme) VALUES('".$_POST['add_theme']."')";
    if($conn->query($add_theme_sql)){
      $error_array['theme_success'] = true;
    }else{
      $error_array['theme_error'] = true;
    }
  }
}

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

	if ($error_array["image_success"]){
    if (conn_check($conn)){
      $get_img_id_sql = "SELECT id FROM test_images ORDER BY id DESC LIMIT 1";
      if($result = $conn->query($get_img_id_sql)){
        foreach($result as $note){
          $img_id = $note["id"];
        }
      } else{
        $img_id = null;
        $error_array["image_error"] = true;
      }
      $result -> free();
    }
	}
	
#------------------ add question ----------------------------

  if (isset($_POST['theme'])){
    if ($_POST['theme'] != ""){
      $theme_to_push = $_POST['theme'];
    }else{
      $theme_to_push = null;
    }
  }else{
    $theme_to_push = null;
  }
  array_push($themes_array, $theme_to_push);
  $_SESSION['themes_array'] = $themes_array;

	if ($form_type != "definite_mc"){
		if ($form_type != "checkbox"){
			$construct = [$_POST['question'], $num_of_questions, (int)$_POST['num_of_right_answer'] - 1, [], $form_type, $img_id];
		}else{
      $nums_of_right_answers = explode(" ", $_POST['num_of_right_answer']);
      for ($i = 0; $i < count($nums_of_right_answers); $i++){
        $nums_of_right_answers[$i] = (int)$nums_of_right_answers[$i] - 1;
      }
			$construct = [$_POST['question'], $num_of_questions, $nums_of_right_answers, [], $form_type, $img_id];
		}
		for ($i = 0; $i < $num_of_questions; $i++){
			$variant = $_POST[$i];
			array_push($construct[3], $variant);
			unset($_POST[$i]);
		}
	}else{
		$construct = [$_POST['question'], null, null, null, $form_type, $img_id];
	}
	unset($_POST['question'], $_POST['c_form_add_done']);
	array_push($result_array, $construct);
	$_SESSION['result_array'] = $result_array;
  array_push($_SESSION['to_send'], true);
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
		for ($i = 0; $i < count($result_array); $i++){
      if ($_SESSION['to_send'][$i]){
        $add_questions_sql = "INSERT INTO questions(question, theme) VALUES('".json_encode($result_array[$i], JSON_UNESCAPED_UNICODE)."', '".$themes_array[$i]."')";
        if (!$conn->query($add_questions_sql)) {
          echo "Ошибка: " . $conn->error;
        }
      }
		}
		unset($_POST['test_name']);
		$error_array["success_add_questions"] = true;
		$_SESSION['result_array'] = [];
    $_SESSION['to_send'] = [];
    $_SESSION['themes_array'] = [];
    $themes_array = array();
		$result_array = array();
	}
}

# ----------------- select all themes ---------------------------

$themes = array();
$get_themes_sql = "SELECT * FROM themes ORDER BY theme ASC";
if($result = $conn->query($get_themes_sql)){
  foreach($result as $option){
    $select_themed_questions_sql = "SELECT * FROM questions WHERE theme='".$option['theme']."'";
    if ($count_result = $conn->query($select_themed_questions_sql)){
      $num_of_themed_questions = $count_result->num_rows;
      $theme_array_to_themes = [$option["theme"], $num_of_themed_questions];
      array_push($themes, $theme_array_to_themes);
    }else{
      $error_array["theme_error"] = true;
    }
    $count_result->free();
  }
  $result -> free();
} else {
  $error_array["theme_error"] = true;
}

# ----------------- select amount of questions -----------------------

$select_num_of_all_questoins_sql = "SELECT id FROM questions";
if ($count_result = $conn->query($select_num_of_all_questoins_sql)){
  $num_of_all_questions = $count_result -> num_rows;
}
$count_result->free();

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
<header class="question_header">
  <a class="back_button" href="post_test.php">Выложить тест</a><a class="back_button" href="../index.php">На главную</a>
</header>
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

      <h3>Выберите тему вопроса</h3>
      <select style="width: 90%" name="theme">
        <option></option>
        <?php
        # ----------------- options ---------------------------

        foreach ($themes as $theme){
          echo "<option value='".$theme[0]."'>".$theme[0]."(".$theme[1].")</option>";
        }
        ?>
        </select>
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
    <div class="c_form_2">
      <form method="get">
        <h3>Количество ответов</h3>
        <input type="number" name="num_of_questions" value="<?php echo $num_of_questions; ?>">
        <input type="submit" value="изменить">
      </form>
      <form method="post">
        <h3>Удалить вопрос</h3>
        <input type="number" name="num_for_delete">
        <input type="submit" value="Удалить вопрос">
      </form>
      <form method="post">
        <h3>Добавить тему</h3>
        <input type="text" name="add_theme">
        <input type="submit" value="Добавить тему">
      </form>
    </div>
    <div class="c_form_3">
      <form method="post">
        <h3>Добавить рандомные вопросы</h3>
        <select name="theme_of_rand_questions" style="width: 90%; margin-bottom: 10px">
          <option value="all_themes">Any(<?php echo $num_of_all_questions; ?>)</option>
          <?php
          # ---------------- options -----------------

          foreach ($themes as $theme){
            echo "<option value='".$theme[0]."'>".$theme[0]."(".$theme[1].")</option>";
          }
          ?>
        </select>
        <input type="number" placeholder="Количество вопросов" name="num_of_rand_questions">
        <input type="submit" value="Добавить">
      </form>
      <form method="post">
        <h3>Задайте название тестирования</h3>
        <textarea name="test_name"></textarea>
        <input type="submit" name="add_test_to_db" value="Добавить тест в бд">
        <input type="submit" name="add_questions_to_db" value="Добавить вопросы в бд">
      </form>
    </div>
  </div>
		  <?php
#---------------- errors and successes ---------------------------

	if ($error_array['theme_error'] || $error_array['theme_success'] || $error_array["success_add_test"] || $error_array["image_error"] || $error_array["image_success"] || $error_array["success_add_questions"]){

		echo "<div class='c_error_array'>\n";

		if ($error_array["success_add_test"]){
			echo "<p class='success'>Тест был успешно добавлен в базу данныx</p>";
		}

		if ($error_array["image_error"]){
			echo "<p class='warning'>Ошибка загрузки картинки</p>";
		}

    if ($error_array["theme_error"]){
      echo "<p class='warning'>Ошибка загрузки тем</p>";
    }

    if ($error_array["image_success"]){
			echo "<p class='success'>Картинка была успешно добавлена (ID:".$img_id.")</p>";
		}

		if ($error_array["success_add_questions"]){
			echo "<p class='success'>Вопросы были успешно добавлены в базу данныx</p>";
		}

    if ($error_array["theme_success"]){
      echo "<p class='success'>Тема успешно добавлена в базу данныx</p>";
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

    if ($themes_array[$i] != null){
      echo "<p>Тема: ".$themes_array[$i]."</p>";
    }else{
      echo "<p>Тема не задана</p>";
    }
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
				echo "<p>Правильные ответы(".count($question[2])."):</p><ul style='margin-bottom: 5px; margin-top: 5px'>";
				for ($j = 0; $j < count($question[2]); $j++){
					echo "<li>".$question[3][$question[2][$j]]."</li>";
				}
        echo "</ul>";
				break;
			case "definite":
				echo "<p>Правильные ответы(".$question[1]."):</p><ul>";
				for ($j = 0; $j < $question[1]; $j++){
					echo "<li>".$question[3][$j]."</li>";
				}
        echo "</ul>";
				break;
		}

		echo '</div></div>';
	}

	$conn -> close();
			?>
		<form method="post">
      <input type="hidden" name="clear" value="true">
			<input type="submit" value="очистить всё">
		</form>
	
		
</body>
</html>