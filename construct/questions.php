<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }

$title = "Add question from db"
?>
<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
<a href="construct.php">Назад</a>
<?php
$select_sql = "SELECT * FROM questions ORDER BY id DESC";
if ($select_result = $conn->query($select_sql)){
  foreach ($select_result as $item){ ?>
    <form action='construct.php' method='post'>
      <h2>ID: <?php echo $item["id"]; ?></h2>
      <h3><?php echo $item["question"]; ?></h3>
      <input type='hidden' value='<?php echo $item["id"]; ?>' name='add_question_from_db_id'>
      <input type='submit' value='Добавить'>
    </form>
  <?php
  }
}
$select_result->free();
?>
<script src="../templates/format.js"></script>
</body>
</html>
