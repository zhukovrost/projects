<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }
if (empty($_GET['id'])){
    header("Location: questions.php");
}else{
    $id = $_GET['id'];
    $question = new Question();
    $question->set_question_data($conn, $id);
}

$title = "EDIT QUESTION";

# ---------- submit actions ----------------

if (isset($_POST['edit'])){
    $new_question = $_POST["question"];
    if ($_POST['type'] == "radio" || $_POST['type'] == "checkbox"){
        $new_variants = $_POST['variants'];
        $new_right_answers = explode(" ", $_POST["right_answers"]);
        for ($i = 0; $i < count($new_right_answers); $i++){
            $new_right_answers[$i] = (int)$new_right_answers[$i] - 1;
        }
    } else if ($_POST['type'] == "definite" || $_POST['type'] == "missing_words"){
        $new_variants = array();
        $new_right_answers = $_POST['right_answers'];
    } else {
        $new_variants = '[]';
        $new_right_answers = '[]';
    }

    $question->update($conn, $new_question, $new_variants, $new_right_answers);
}

# -------------- add variant -----------------
if (isset($_POST['add']) || isset($_POST['delete'])){
    if (isset($_POST['add'])){
        $new_right_answers = $_POST['right_answers'];
        $new_right_answers[] = '';
    }else{
        $new_right_answers = $_POST['right_answers'];
        array_pop($new_right_answers);
    }
    $question->update($conn, $question->question, $question->variants, $new_right_answers);
}

?>
<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body class="edit_question_body">
<a class="question_back" href="questions.php">Go back</a>
<form method="post" action="edit_question.php?id=<?php echo $id; ?>">
    <label for="question">Question (type <?php echo $question->type; ?>):</label>
    <input type="text" id="question" name="question" value="<?php echo $question->question; ?>">
    <input type="hidden" name="type" value="<?php echo $question->type; ?>">
    <?php if ($question->type == "radio" || $question->type == "checkbox"){ ?>
        <label>Variants:</label>
    <?php
        for ($i = 0; $i < count($question->variants); $i++){ ?>
            <input type="text" name="variants[<?php echo $i; ?>]" value="<?php echo $question->variants[$i]; ?>" placeholder="Variant">
    <?php }
        $right_answers_string = '';
        foreach ($question->get_right_answers() as $right_answer){
            $right_answers_string .= (string)((int)$right_answer + 1).' ';
        }
        $right_answers_string = rtrim($right_answers_string);
        ?>
        <label for="right_answers">Answer(s):</label>
        <input type="text" id="right_answers" name="right_answers" value="<?php echo $right_answers_string; ?>" placeholder="Answer">
    <?php } else if ($question->type == "definite" || $question->type == "missing_words") { ?>
        <label>Answer(s):</label>
    <?php
        for ($i = 0; $i < count($question->get_right_answers()); $i++){ ?>
            <input type="text" name="right_answers[<?php echo $i; ?>]" value="<?php echo ($question->get_right_answers())[$i]; ?>" placeholder="Answer">
        <?php } ?>
    <button type="submit" name="add" value="1">Add variant/word</button>
        <button type="submit" name="delete" value="1">Delete last variant/word</button>
    <?php } ?>

    <button type="submit" name="edit" value="1">Edit</button>
</form>
</body>
</html>
<?php $conn->close(); ?>