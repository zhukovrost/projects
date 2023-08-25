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

if (isset($_POST['edit'])){
    # ------- coming soon ---------
    $sql = "UPDATE questions SET question=''";
}

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
    <input type="text" name="question" value="<?php echo $question_data['question']; ?>">
    <input type="hidden" name="type" value="<?php echo $type; ?>">
    <?php if ($type == "radio" || $type == "checkbox"){
        $variants = json_decode($question_data['variants']);
        for ($i = 0; $i < count($variants); $i++){ ?>
            <input type="text" name="variants[<?php echo $i; ?>]" value="<?php echo $variants[$i]; ?>" placeholder="Variant">
    <?php }
        $right_answers_string = '';
        foreach ($right_answers as $right_answer){
            $right_answers_string .= (string)$right_answer;
        } ?>
        <input type="text" name="right_answers" value="<?php echo $right_answers_string; ?>" placeholder="Answer">
    <?php } else if ($type == "definite" || $type == "missing_words") {
        for ($i = 0; $i < count($right_answers); $i++){ ?>
            <input type="text" name="right_answers[<?php echo $i; ?>]" value="<?php echo $right_answers[$i]; ?>" placeholder="Answer">
        <?php } } ?>

    <button type="submit" name="edit" value="1">Edit</button>
</form>
</body>
</html>