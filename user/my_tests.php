<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$user_data = get_user_data($conn, $_COOKIE['login']);

check_the_login($user_data, "../");

$login = $_COOKIE['login'];
session_start();

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
<main class="my_tests_main">
<header class="tests_header">
		<a class="back_button" href="../index.php">На главную</a>
</header>
  <h1>Мои тесты</h1>
  <?php
  # -------- errors -------------
  if (isset($_GET['error_completed'])){
    print_warning("Ошибка: Тест уже пройден.");
  }

  # ---------- tests output -------------
  $select_sql = "SELECT user_tests_ids, user_tests_marks, user_tests_durations FROM users WHERE login='".$login."'";
  if ($select_result = $conn->query($select_sql)){
    foreach ($select_result as $item){
      $ids = json_decode($item['user_tests_ids']);
      $completed = json_decode($item['user_tests_marks']);
      $time_to_do = json_decode($item['user_tests_durations']);
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

        echo "<div>";
        echo "
        <label>".$name."</label>
        <label>Отведённое время: ".$mins." мин</label>
        ";
        if ($mark == 0){
          echo "
          <label>Статус: не пройден</label>
          <form method='post' action='test.php'>
            <input type='hidden' name='id' value='".$id."'>
            <input type='hidden' name='position' value='".$i."'>
            <input type='hidden' name='duration' value='".$time."'>
            <button class='my_test_btn' type='submit'>Начать тестирование</button>
          </form>
          ";
        }else{
          if ($mark != -1){
            echo "<label>Статус: пройден</label>";
            echo "<label>Ваша оценка: ".$mark."</label>";
          }else{
            echo "<label>Статус: на проверке</label>";
          }
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
