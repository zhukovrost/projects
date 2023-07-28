<?php
if (!isset($_SESSION)) { 
	session_start(); 
}
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }

$title = "CONSTRUCTOR";

$error_array = array(
	"success_add_test" => false,
	"image_error" => false,
	"image_success" => false,
	"success_add_question" => false,
  "theme_error" => false,
  "theme_success" => false,
  "random_error" => false
);

#start 
# ------------------------------ DEFAULT VALUES -------------------------------
#------------------- clear function -------------------------

if (empty($_POST['clear'])){
	if (empty($_SESSION['result_array'])){
		$_SESSION['result_array'] = array();
	}
}else{
	$_SESSION['result_array'] = array();
  $_POST = array();
}

#------------------ form type -----------------------

if (empty($_GET['form_type'])){
	if (empty($_SESSION['form_type'])){
    $_SESSION['form_type'] = "radio";
	}
}else{
	$_SESSION['form_type'] = $_GET['form_type'];
}

#--------------- amount of questions ------------------

if (empty($_GET['num_of_questions'])){
	if (empty($_SESSION['num_of_questions'])){
    $_SESSION['num_of_questions'] = 4;
	}
}else{
	$_SESSION['num_of_questions'] = $_GET['num_of_questions'];
}

# -------------------- FUNCTIONS -----------------------------

# ----------------- select amount of questions -----------------------

$select_num_of_all_questoins_sql = "SELECT id FROM questions";
if ($count_result = $conn->query($select_num_of_all_questoins_sql)){
  $num_of_all_questions = $count_result -> num_rows;
}
$count_result->free();

#--------------- delete question -------------------

if (isset($_POST['num_for_delete'])){
  if ($_POST['num_for_delete'] != "" || $_POST['num_for_delete'] != null){
    $num_del = $_POST['num_for_delete'] - 1;

    $nwra1 = array();
    for ($i = 0; $i < $num_del; $i++){ array_push($nwra1, $_SESSION['result_array'][$i]); }
    for ($i = $num_del + 1; $i < count($_SESSION['result_array']); $i++){ array_push($nwra1, $_SESSION['result_array'][$i]); }
    $_SESSION['result_array'] = $nwra1;

    unset($_POST['num_for_delete']);
  }
}

# ----------------------- add random questions -----------------------

if (isset($_POST['num_of_rand_questions'])){
  if ($_POST['theme_of_rand_questions'] == "all_themes"){
    $select_rand_sql = "SELECT id FROM questions ORDER BY RAND() LIMIT " . $_POST['num_of_rand_questions'];
  }else{
    echo $_POST['theme_of_rand_questions'];
    $select_rand_sql = "SELECT id FROM questions WHERE theme=".get_theme_id($conn, $_POST['theme_of_rand_questions'])." ORDER BY RAND() LIMIT " . $_POST['num_of_rand_questions'];
  }
  if ($result = $conn->query($select_rand_sql)){
    foreach ($result as $rand_question_query){
      $rand_question_id = $rand_question_query['id'];
      array_push($_SESSION['result_array'], $rand_question_id);
    }
  }else{
    $error_array["random_error"] = true;
  }
  $result->free();
}

# -------------------- add questions from db ----------------

if (isset($_POST['add_question_from_db_id'])){
  if ($_POST['add_question_from_db_id'] != "" || $_POST['add_question_from_db_id'] != null){
    array_push($_SESSION['result_array'], (int)$_POST['add_question_from_db_id']);
  }
}

#-------------- add new theme to db ----------------

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

# ----------------- select all themes ---------------------------

$themes = array();
$get_themes_sql = "SELECT id, theme FROM themes ORDER BY theme ASC";
if($result = $conn->query($get_themes_sql)){
  foreach($result as $option){
    $select_themed_questions_sql = "SELECT id FROM questions WHERE theme='".$option['id']."'";
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



# ----------------------------- ADD QUESTION ------------------------------------

if (isset($_POST['add_question_to_db'])){


  # ------------------- get question and type ---------------------

  $question = $_POST['question'];
  $type = $_SESSION['form_type'];

  #------------------ get theme ----------------------------

  $theme = 0;
  if (isset($_POST['theme'])){
    if ($_POST['theme'] != ""){
      $theme = get_theme_id($conn, $_POST['theme']);
    }
  }

  # --------------------- get variants / answers ------------------------

  $variants = array();
  for ($i = 0; $i < $_SESSION['num_of_questions']; $i++){
    $variant = $_POST[$i];
    array_push($variants, $variant);
    unset($_POST[$i]);
  }

  # ------------------- get right answers -------------------

  if (isset($_POST['num_of_right_answer'])){
    $right_answers = explode(" ", $_POST['num_of_right_answer']);
    for ($i = 0; $i < count($right_answers); $i++){
      $right_answers[$i] = (int)$right_answers[$i] - 1;
    }
  }else if ($type == "definite" or $type == "missing_words"){
    $right_answers = $variants;
    $variants = array();
    for ($i = 0; $i < count($right_answers); $i++){
      $right_answers[$i] = mb_strtolower(str_replace(' ', '', $right_answers[$i]));
    }
  }else{
    $right_answers = array();
  }

  # ------------------ add image ---------------------------

	if (!empty($_FILES["add_img"]["tmp_name"])){
		if ($_FILES["add_img"]["tmp_name"] != "" && $_FILES["add_img"]["error"] == 0){
      $img = addslashes(file_get_contents($_FILES["add_img"]["tmp_name"]));
      $add_image_sql = "INSERT INTO test_images(image) VALUES('$img')";
      if($conn->query($add_image_sql)){
        $_FILES = array();
        $error_array["image_success"] = true;
      }
    }else{
      $error_array["image_error"] = true;
    }
	}

  # ------------------- get image id --------------------------

	$img_id = 0;
	if ($error_array["image_success"]){
    $img_id = mysqli_insert_id($conn);
	}


  # ----------------- insert question to db --------------------
  $add_question_sql = "INSERT INTO questions (question, theme, type, variants, right_answers, image) VALUES ('$question', $theme, '$type', '".json_encode($variants, 256)."', '".json_encode($right_answers, 256)."', $img_id)";
  if ($conn->query($add_question_sql)) {
    $error_array['success_add_question'] = true;
    $id = mysqli_insert_id($conn);
    array_push($_SESSION['result_array'], $id);
  }else{
    echo "Ошибка: " . $conn->error;
  }

}

# -------------------- ADD TEST TO DB ----------------------

if (isset($_POST['test_name'])){

  # -------------- search for all themes in the test -----------------

  $test_themes = array();
  foreach ($_SESSION['result_array'] as $question_id){
    $select_test_themes_sql = "SELECT theme FROM questions WHERE id=$question_id ORDER BY theme";
    if ($select_test_themes_result = $conn->query($select_test_themes_sql)){
      foreach ($select_test_themes_result as $item){
        $test_theme = $item['theme'];
      }
      if (!in_array($test_theme, $test_themes)){
        array_push($test_themes, $test_theme);
      }
    } else {
      echo "Ошибка: " . $conn->error;
    }
    $select_test_themes_result->free();
  }

  # ------------------ insert test --------------------------

  $add_test_sql = "INSERT INTO tests(name, test, themes) VALUES('".$_POST['test_name']."', '".json_encode($_SESSION['result_array'])."', '".json_encode($test_themes)."')";
  if($conn->query($add_test_sql)){
    $error_array["success_add_test"] = true;
  } else {
    echo "Ошибка: " . $conn->error;
  }

  # ------------ clear -------------

  unset($_POST['test_name']);
  $error_array["success_add_questions"] = true;
  $_SESSION['result_array'] = array();
}

?>
<!DOCTYPE html>
<html lang="ru">
<?php include "../templates/head.php"; ?>
<body id="construct_body">
<?php include "../templates/admin_header.html"; ?>
	<div class="c_all_forms_block">
		<form enctype="multipart/form-data" method="post" class="c_form_1">
			<h3>Добавить вопрос</h3>
			<textarea id="add_question_input" name="question"></textarea>
			<br>
			<?php 
			if ($_SESSION['form_type'] != "definite_mc"){
        if ($_SESSION['form_type'] != "missing_words"){
          for ($i = 0; $i < $_SESSION['num_of_questions']; $i++){
            echo "<input id='answer_input' style='width: 90%' name='".$i."' type='text' placeholder='Вариант ответа №".(intval($i) + 1)."'>\n<br>";
          }
        }else{
          for ($i = 0; $i < $_SESSION['num_of_questions']; $i++){
            echo "<input id='answer_input' style='width: 90%' name='".$i."' type='text' placeholder='Пропущенное слово №".(intval($i) + 1)."'>\n<br>";
          }
        }
			} 
			?>
			<h4>Добавить картинку</h4>
			<input type="file" name="add_img">
			<?php
			if ($_SESSION['form_type'] == "radio"){ ?>
				<h4>Номер правильного ответа</h4><input id="num_of_right_answer_number" type="number" name="num_of_right_answer" style="width: 30%">
			<?php }
			if ($_SESSION['form_type'] == "checkbox"){ ?>
				<h4>Номера правильных ответов</h4>
        <input id="num_of_right_answer_text" type="text" name="num_of_right_answer" style="width: 30%">
        <p>Записывать через пробел</p>
			<?php } ?>

      <h3>Выберите тему вопроса</h3>
      <select id="theme_select" style="width: 90%" name="theme">
        <option></option>
        <?php
        # ----------------- options ---------------------------

        foreach ($themes as $theme){
          echo "<option value='".$theme[0]."'>".$theme[0]."(".$theme[1].")</option>";
        }
        ?>
        </select>
			<input type="submit" name="add_question_to_db" value="Создать">
		</form>
		<form method="get" class="c_form_3">
			<h3>Тип вопроса</h3>
			<p><input type="radio" name="form_type" value="radio"<?php if ($_SESSION['form_type'] == "radio"){ echo " checked"; } ?>>С одним вариантом овета</p>
			<p><input type="radio" name="form_type" value="checkbox"<?php if ($_SESSION['form_type'] == "checkbox"){ echo " checked"; } ?>>С несколькими вариантами ответа</p>
			<p><input type="radio" name="form_type" value="definite"<?php if ($_SESSION['form_type'] == "definite"){ echo " checked"; } ?>>С определённым ответом</p>
			<p><input type="radio" name="form_type" value="definite_mc"<?php if ($_SESSION['form_type'] == "definite_mc"){ echo " checked"; } ?>>С определённым ответом (Ручная проверка)</p>
      <p><input type="radio" name="form_type" value="missing_words"<?php if ($_SESSION['form_type'] == "missing_words"){ echo " checked"; } ?>>Пропущенные слова</p>
			<input type="submit" value="изменить" style="width: 90%">
		</form>
    <div class="c_form_2">
      <form method="get">
        <h3>Количество ответов</h3>
        <input type="number" name="num_of_questions" value="<?php echo $_SESSION['num_of_questions']; ?>">
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
        <select id="theme_of_rand_questions" name="theme_of_rand_questions" style="width: 90%; margin-bottom: 10px">
          <option value="all_themes">Any(<?php echo $num_of_all_questions; ?>)</option>
          <?php
          # ---------------- options -----------------

          foreach ($themes as $theme){
            echo "<option value='".$theme[0]."'>".$theme[0]."(".$theme[1].")</option>";
          }
          ?>
        </select>
        <input id="number_of_random_questions" type="number" placeholder="Количество вопросов" name="num_of_rand_questions">
        <input type="submit" value="Добавить">
      </form>
      <form method="post">
        <h3>Задайте название тестирования</h3>
        <textarea id="name_of_test" name="test_name"></textarea>
        <input type="submit" value="Добавить тест в бд">
        <a href="questions.php">Добавить из бд</a>
      </form>
    </div>
  </div>
		  <?php
#---------------- errors and successes ---------------------------

	if ($error_array['theme_error'] || $error_array['theme_success'] || $error_array["success_add_test"] || $error_array["image_error"] || $error_array["image_success"] || $error_array["success_add_question"]){ ?>

		<div class='c_error_array'>
		<?php if ($error_array["success_add_test"]){ ?>
      <p class='success'>The test has been added to the database</p>
		<?php } if ($error_array["image_error"]){ ?>
			<p class='warning'>Ошибка загрузки картинки</p>
		<?php } if ($error_array["theme_error"]){ ?>
      <p class='warning'>Ошибка загрузки тем</p>
    <?php } if ($error_array["image_success"]){ ?>
			<p class='success'>Картинка была успешно добавлена (ID: <?php echo $img_id; ?>)</p>
		<?php } if ($error_array["success_add_question"]){ ?>
			<p class='success'>Вопросы были успешно добавлены в базу данныx</p>
		<?php } if ($error_array["theme_success"]){ ?>
      <p class='success'>Тема успешно добавлена в базу данныx</p>
    <?php } ?>
		</div>

	<?php } ?>

<?php
#-------------------- preview -------------------

  print_test($conn, $_SESSION['result_array'], true);


?>
		<form method="post">
      <input type="hidden" name="clear" value="true">
			<input class="c_clear_all_btn" type="submit" value="очистить всё">
		</form>
	
		<script>
			document.addEventListener("DOMContentLoaded", function() {

				// ============Save content in forms============
				// First Column
				let AddQuestionInput = document.getElementById('add_question_input');

				AddQuestionInput.addEventListener('keyup', function(){
						let id = 'add_question_input';
						let value = AddQuestionInput.value;
						localStorage.setItem(id, value);
				});

				if (localStorage.getItem('add_question_input') != null) {
					AddQuestionInput.value = localStorage.getItem('add_question_input');
				}


				let AnswersVariantsArray = document.querySelectorAll('#answer_input');

				if(AnswersVariantsArray != null){
					for(let i = 0; i < AnswersVariantsArray.length; i++){
						AnswersVariantsArray[i].addEventListener('keyup', function(){
							let id = 'answer_input' + i.toString();
							let value = AnswersVariantsArray[i].value;
							localStorage.setItem(id, value);
						});
					}

					for(let i = 0; i < AnswersVariantsArray.length; i++){
						if (localStorage.getItem(AnswersVariantsArray[i].id + i.toString()) != null) {
							AnswersVariantsArray[i].value = localStorage.getItem(AnswersVariantsArray[i].id + i.toString());
						}
					}
				}


				let NumOfRightAnswerNumber = document.getElementById('num_of_right_answer_number');

				if(NumOfRightAnswerNumber != null){
					NumOfRightAnswerNumber.addEventListener('keyup', function(){
						let id = 'num_of_right_answer_number';
						let value = NumOfRightAnswerNumber.value;
						localStorage.setItem(id, value);
					});

					if (localStorage.getItem('num_of_right_answer_number') != null) {
						NumOfRightAnswerNumber.value = localStorage.getItem('num_of_right_answer_number');
					}
				}


				let NumOfRightAnswerText = document.getElementById('num_of_right_answer_text');

				if(NumOfRightAnswerText != null){
					NumOfRightAnswerText.addEventListener('keyup', function(){
						let id = 'num_of_right_answer_text';
						let value = NumOfRightAnswerText.value;
						localStorage.setItem(id, value);
					});

					if (localStorage.getItem('num_of_right_answer_text') != null) {
						NumOfRightAnswerText.value = localStorage.getItem('num_of_right_answer_text');
					}
				}


				let ThemeSelect = document.getElementById('theme_select');

				ThemeSelect.addEventListener('change', function(){
						let id = 'theme_select';
						let value = ThemeSelect.value;
						localStorage.setItem(id, value);
				});

				if (localStorage.getItem('theme_select') != null) {
					ThemeSelect.value = localStorage.getItem('theme_select');
				}



				// Second column
				let ThemeOfRandQuestions = document.getElementById('theme_of_rand_questions');

				ThemeOfRandQuestions.addEventListener('change', function(){
						let id = 'theme_of_rand_questions';
						let value = ThemeOfRandQuestions.value;
						localStorage.setItem(id, value);
				});

				if (localStorage.getItem('theme_of_rand_questions') != null) {
					ThemeOfRandQuestions.value = localStorage.getItem('theme_of_rand_questions');
				}


				let NumberOfRandQuestions = document.getElementById('number_of_random_questions');

				NumberOfRandQuestions.addEventListener('keyup', function(){
						let id = 'number_of_random_questions';
						let value = NumberOfRandQuestions.value;
						localStorage.setItem(id, value);
				});

				if (localStorage.getItem('number_of_random_questions') != null) {
					NumberOfRandQuestions.value = localStorage.getItem('number_of_random_questions');
				}


				let NameOfTest = document.getElementById('name_of_test');

				NameOfTest.addEventListener('keyup', function(){
						let id = 'name_of_test';
						let value = NameOfTest.value;
						localStorage.setItem(id, value);
				});

				if (localStorage.getItem('name_of_test') != null) {
					NameOfTest.value = localStorage.getItem('name_of_test');
				}
			});
		</script>
</body>
</html>