<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }
if (empty($_GET['id'])){
    header("Location: questions.php");
}else{
    $id = $_GET['id'];
}

$title = "EDIT QUESTION";

# ---------- submit actions ----------------

if (isset($_POST['edit'])){
    $new_question = $_POST["question"];
    if ($_POST['type'] == "radio" || $_POST['type'] == "checkbox"){
        $new_variants = json_encode($_POST['variants'], 256);
        $new_right_answers = explode(" ", $_POST["right_answers"]);
        for ($i = 0; $i < count($new_right_answers); $i++){
            $new_right_answers[$i] = (int)$new_right_answers[$i] - 1;
        }
        $new_right_answers = json_encode($new_right_answers, 256);
    } else if ($_POST['type'] == "definite" || $_POST['type'] == "missing_words"){
        $new_variants = '[]';
        $new_right_answers = json_encode($_POST['right_answers'], 256);
    } else {
        $new_variants = '[]';
        $new_right_answers = '[]';
    }

    $sql = "UPDATE questions SET question='$new_question', variants='$new_variants', right_answers='$new_right_answers' WHERE id=$id";
    $conn->query($sql);
}

# -------------- add variant -----------------
if (isset($_POST['add']) || isset($_POST['delete'])){
    if (isset($_POST['add'])){
        $new_right_answers = $_POST['right_answers'];
        $new_right_answers[] = '';
        $new_right_answers = json_encode($new_right_answers, 256);
    }else{
        $new_right_answers = $_POST['right_answers'];
        array_pop($new_right_answers);
        $new_right_answers = json_encode($new_right_answers, 256);
    }
    $sql = "UPDATE questions SET right_answers='$new_right_answers' WHERE id=$id";
    $conn->query($sql);
}

# -------------- getting question data ---------------
$question_data = get_question_data($conn, $id);
$type = $question_data['type'];
$right_answers = json_decode($question_data['right_answers']);
$score = $question_data['score'];
?>
<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body class="edit_question_body">
<a class="question_back" href="questions.php">Go back</a>
<form method="post" action="edit_question.php?id=<?php echo $id; ?>">
    <label for="question">Question (type <?php echo $type; ?>):</label>
    <input type="text" id="question" name="question" value="<?php echo $question_data['question']; ?>">
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <?php if ($type == "radio" || $type == "checkbox"){ ?>
        <label>Variants:</label>
    <?php
        $variants = json_decode($question_data['variants']);
        for ($i = 0; $i < count($variants); $i++){ ?>
            <input type="text" name="variants[<?php echo $i; ?>]" value="<?php echo $variants[$i]; ?>" placeholder="Variant">
    <?php }
        $right_answers_string = '';
        foreach ($right_answers as $right_answer){
            $right_answers_string .= (string)((int)$right_answer + 1).' ';
        }
        $right_answers_string = rtrim($right_answers_string);
        ?>
        <label for="right_answers">Answer(s):</label>
        <input type="text" id="right_answers" name="right_answers" value="<?php echo $right_answers_string; ?>" placeholder="Answer">
    <?php } else if ($type == "definite" || $type == "missing_words") { ?>
        <label>Answer(s):</label>
    <?php
        for ($i = 0; $i < count($right_answers); $i++){ ?>
            <input type="text" name="right_answers[<?php echo $i; ?>]" value="<?php echo $right_answers[$i]; ?>" placeholder="Answer">
        <?php } ?>
    <button type="submit" name="add" value="1">Add variant/word</button>
        <button type="submit" name="delete" value="1">Delete last variant/word</button>
    <?php } ?>

    <button type="submit" name="edit" value="1">Edit</button>
</form>
</body>
</html>
<?php $conn->close(); ?>