<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }

$title = "POST TESTS";

$error_array = array(
  "post_test_success" => false
);

# --------------------  pushing tests to db  -------------------------

$test_id = $_GET['test_id'];

if (isset($_POST['push_id'])){
  $push_id = $_POST['push_id'];

  $test_data = get_test_data($conn, $push_id);
  $test = json_decode($test_data['test']);
  $flag = false;
  foreach ($test as $question_id){
    if (get_question_data($conn, $question_id)['type'] == 'definite_mc'){
      $flag = true;
      break;
    }
  }

  $duration = $_POST['duration'] * 60;
  foreach ($_POST['users_array'] as $user_id){
    if ($flag){
      $insert_sql = "INSERT INTO tests_to_users (test, user, duration, mark, date) VALUES ($push_id, $user_id, $duration, -3, ".time().")";
    }else{
      $insert_sql = "INSERT INTO tests_to_users (test, user, duration, date) VALUES ($push_id, $user_id, $duration, ".time().")";
    }
    if ($conn->query($insert_sql)){
      $error_array['post_test_success'] = true;
    }else{
      echo $conn->error;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="ru">
<?php include "../templates/head.php"; ?>
<body>
<?php
if (empty($_GET['test_id']) || $_GET['test_id'] == ''){
  include "../templates/admin_header.html";
}else{ ?>
  <a class="back_button" href="post_test.php?test_id">GO BACK</a>
<?php } ?>
    <?php
    # -------------------- LIST OF TESTS -------------------------

    if (empty($test_id) || $test_id == ''){
      $select_tests_sql = "SELECT id, name FROM tests ORDER BY id DESC";
      if ($select_tests_result = $conn->query($select_tests_sql)){
        echo "<div class='test_output_form'>";
        foreach ($select_tests_result as $item) {
          $id = $item['id'];
          $name = $item['name'];

          echo "<a class='construct_btn' href='post_test.php?test_id=".$id."'>".$id.": ".$name."</a>";
        }
        echo "</div>";
      }
      $select_tests_result->free();
    }else{
      # ------------------ IF THE TEST ID SELECTED ---------------------
      # ------------------- THE LIST OF USERS -----------------------

      $select_users_sql = "SELECT id, name, surname FROM users WHERE status='user' ORDER BY surname ASC";

      if ($select_users_result = $conn->query($select_users_sql)){ ?>
        <form method='post' class='test_output_form' action='post_test.php?test_id'>
          <?php
        foreach ($select_users_result as $user){
          echo "<p><input type='checkbox' name='users_array[]' value='".$user['id']."'>".$user['surname']." ".$user['name']."</p>";
        }
        ?>
        <input name="duration" type="number" value="15">
        <label for="time_to_do">Test time</label>
        <div class="test_finish_button">
          <input type="submit" class="construct_btn" value="Post the test">
          <input type="hidden" name="push_id" value="<?php echo $test_id; ?>">
        </div>
        </form>
      <?php
      }
      $select_users_result->free();

      # ------------------- TEST PREVIEW -------------------------

      print_test_by_id($conn, $test_id, true);
    }
    if ($error_array['post_test_success']){
      print_message("The test has been published!", 1);
    }
    ?>
</body>
</html>
