<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!$user_data->is_admin()){ header("Location: ../index.php"); }

$title = "FAST VERIFICATION";

$error_array = array(
  "success_verification" => false
);

if (isset($_POST['verified'])){
  $update_sql = "UPDATE tests_to_users SET verified_scores='".json_encode($_POST['ver_score_input'][$_POST['verified']])."' WHERE id=".$_POST['verified'];
  if ($conn->query($update_sql) && !in_array('', $_POST['ver_score_input'][$_POST['verified']])){
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
        $test = new Test();
        $test->set_test_data($conn, $item['test']);
        $test->get_questions($conn);
        $user = new User($conn, $item['user']);
        $user_answers = json_decode($item['answers']);
        $verified_scores = (array)json_decode($item['verified_scores']);
        $ver_num = 0;
        ?>

        <form action="" method="post" class="questions_list">
          <div class="container">
          <p class="ver_title"><?php echo $user->surname.' '.$user->name; ?> from test: <?php echo $test->name; ?></p>
        <?php
        for ($i = 0; $i < count($test->test); $i++){
          $question_data = $test->questions[$i];
          if ($question_data->type == "definite_mc"){
            $question_data->print_it($conn, $i, true, $id);
            ?>

            <label class="ver_points_title" for="">POINTS: </label>
            <input class="ver_points" type="number" name="<?php echo "ver_score_input[".$id."][".$i."]" ?>" <?php if ($verified_scores[$ver_num] != ''){ echo "value=".$verified_scores[$ver_num]; } ?>>

          <?php $ver_num++; } } ?>
          <br>
            <button class="ver_button" type="submit" name="verified" value="<?php echo $id; ?>">VERIFIED</button>
          </div>
        </form>
  <?php } }
    $select_result->free();
  if ($error_array['success_verification']){
    print_message("Verification is done!");
  }
  $conn->close();
  ?>

</main>
<script src="../templates/format.js"></script>
</body>
</html>
