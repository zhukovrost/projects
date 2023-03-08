<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);
if (!(check_if_admin($conn, $_COOKIE['login'], "../"))){ header("Location: ../index.php"); }

$select_sql = "SELECT * FROM questions ORDER BY id DESC";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<style>
  form{
      border: black 2px solid;
      padding: 5px;
      margin: 10px;
  }
</style>
<body>
<a href="construct.php">Назад</a>
<?php
if ($select_result = $conn->query($select_sql)){
  foreach ($select_result as $item){
    $q_array = json_decode($item['question']);

    echo "<form action='construct.php' method='post'>";
    echo "<h2>ID: ".$item['id']."</h2>";
    echo "<h3>".$q_array[0]."</h3>";
    echo "<input type='hidden' value='".$item['question']."' name='add_question_from_db'>";
    echo "<input type='hidden' value='".$item['theme']."' name='add_question_from_theme'>";
    echo "<input type='hidden' value='".$item['id']."' name='add_question_from_db_id'>";
    echo "<input type='submit' value='Добавить'></form>";
  }
}
$select_result->free();
?>

</body>
</html>
