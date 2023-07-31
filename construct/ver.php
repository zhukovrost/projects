<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }

$title = "FAST VERIFICATION";

$error_array = array(
  "success_verification" => false
);

if (isset($_POST['verified'])){
  $flag = true;
  foreach ($_POST['ver_score_input'][$_POST['verified']] as $key=>$value){
    if ($value == ''){
      $flag = false;
      break;
    }
  }
  $update_sql = "UPDATE tests_to_users SET verified_scores='".json_encode($_POST['ver_score_input'][$_POST['verified']])."' WHERE id=".$_POST['verified'];
  $conn->query($update_sql);
  if ($flag){
    if (check_the_test($conn, $_POST['verified'], false)){
      $error_array['success_verification'] = true;
    }
  }
}


?>
<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
<?php include "../templates/admin_header.html"; ?>
<main class="construct_main">

  <?php
    $select_sql = "SELECT * FROM tests_to_users WHERE mark=-2 ORDER BY user";
    if ($select_result = $conn->query($select_sql)){
      $num_rows = $select_result->num_rows;

      if ($num_rows == 0){ ?>
        <p>NO TESTS TO VERIFY!</p>
      <?php }

      foreach ($select_result as $item){
        $id = $item['id'];
        $test_data = get_test_data($conn, $item['test']);
        $test = json_decode($test_data['test']);
        $test_answers = json_decode($test_data['right_answers']);
        $user_id = $item['user'];
        $user_answers = json_decode($item['answers']);
        $verified_scores = (array)json_decode($item['verified_scores']);
        $user = get_user_data($conn, $user_id, true);
        ?>

        <form action="" method="post" class="questions_list">
          <div class="container">
          <p class="ver_title"><?php echo $user['surname'].' '.$user['name']; ?> from test: <?php echo $test_data['name']; ?></p>
        <?php
        for ($i = 0; $i < count($test); $i++){
          $question_id = $test[$i];
          $question_data = get_question_data($conn, $question_id);
          if ($question_data['type'] == "definite_mc"){
            print_question($conn, $question_id, $i, true, $id);
            $qid = 'q'.$i;
            $verified_score = $verified_scores[$qid];
            ?>

            <label class="ver_points_title" for="">POINTS: </label>
            <input class="ver_points" type="number" name="<?php echo "ver_score_input[".$id."][".$qid."]" ?>" <?php if ($verified_score != ''){ echo " value='$verified_score'"; } ?>>

          <?php } } ?>
          <br>
            <button class="ver_button" type="submit" name="verified" value="<?php echo $id; ?>">VERIFIED</button>
          </div>
        </form>
  <?php } }
    $select_result->free();
    $conn->close();
  if ($error_array['success_verification']){
    print_message("Verification is done!");
  }
  ?>

</main>
</body>
</html>
