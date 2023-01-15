<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

check_the_login("../");

$login = $_COOKIE['login'];
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Montserrat+Alternates:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,800&display=swap" rel="stylesheet">
</head>
<body>
<main>
  <h1>Мои тесты</h1>
  <?php
  $select_sql = "SELECT user_tests_ids, user_tests_completed, time_to_do FROM users WHERE login='".$login."'";
  if ($select_result = $conn->query($select_sql)){
    foreach ($select_result as $item){
      $ids = json_decode($item['user_tests_ids']);
      $completed = json_decode($item['user_tests_completed']);
      $time_to_do = json_decode($item['time_to_do']);
    }

    for ($i = count($ids) - 1; $i >= 0; $i--){
      $id = $ids[$i];
      $select_sql2 = "SELECT name FROM tests WHERE id='".$id."'";
      if ($select_result2 = $conn->query($select_sql2)){
        foreach ($select_result2 as $item2){
          $name = $item2['name'];
        }
        $mark = $completed[$i];
        $time = $time_to_do[$i];
        $mins = $time / 60;

        echo "<div style='border: black solid 2px'>";
        echo "
        <label>".$name."</label>
        <label>Отведённое время: ".$mins." мин</label>
        ";
        if ($mark == 0){
          echo "
          <label>Вы ещё не прошли этот тест</label>
          <form method='post' action='test.php'>
            <input type='hidden' name='id' value='".$id."'>
            <input type='hidden' name='duration' value='".$time."'>
            <input type='submit' value='Начать тестирование'>
          </form>
          ";
        }else{
          echo "
          <label>Этот тест пройден</label>
          <label>Ваша оценка: ".$mark."</label>
          ";
        }
        echo "</div>";
      }
      $select_result2->free();
    }

  }
  $select_result->free();
  ?>
</main>
<?php include "../templates/footer.html"; ?>
</body>
</html>
