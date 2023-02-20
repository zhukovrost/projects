<?php
include "../templates/func.php";
include "../templates/settings.php";

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/format.css">
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,700&display=swap" rel="stylesheet">
</head>
<body>
<header>
  <div class="header_info">
    <h1 class="company_name">sport_is_life &#174;</h1>
    <div class="title">
      <h1>
        Спорт
      </h1>
      <h2>
        это жизнь
      </h2>
    </div>
    <div class="reglog_buttons">
      <?php
      if (check_the_login("../", false)){
        echo '<a href="../account.php">'.$_COOKIE["login"].'</a>';
      }else{
        echo '
        <a href="../log.php">Вход</a>
        <a href="../reg.php">Регистрация</a>
        ';
      }
      ?>
    </div>
  </div>
  <nav>
    <a href="../index.php">Главная</a>
    <a href="../exercises/workout.php">Мои тренировки</a>
    <a href="">Пользователи</a>
    <a href="../news/news.php">Новости</a>
  </nav>
</header>
<main>
  <form class="container">
    <input name="user" style="width: 100%" type="text" placeholder="Введите имя и фамилию пользователя либо логин начиная с @">
    <button type="submit">Искать</button>
    <?php
    if (isset($_GET['user'])){
      # -------------- searching algorithm (creating a query depending on an input) ---------------------
      $search_user = $_GET['user'];
      if ($search_user[0] == '@'){
        $search_sql = "SELECT login, name, surname, thirdname, avatar_id FROM users WHERE login LIKE '%".substr($search_user, 1)."%' ORDER BY id DESC";
      }else if (strpos($search_user, ' ')){
          $search_user_array = explode(' ', $search_user);
        $search_sql = "SELECT login, name, surname, thirdname, avatar_id FROM users WHERE (name LIKE '%".$search_user_array[0]."%' AND surname LIKE '%".$search_user_array[1]."%') OR (name LIKE '%".$search_user_array[1]."%' AND surname LIKE '%".$search_user_array[2]."%') ORDER BY id DESC";
      }else{
        $search_sql = "SELECT login, name, surname, thirdname, avatar_id FROM users WHERE name LIKE '%".$search_user."%' OR surname LIKE '%".$search_user."%' ORDER BY id DESC";
      }

      if ($search_result = $conn->query($search_sql)){
        if (mysqli_num_rows($search_result) != 0){
          foreach ($search_result as $user){
            # ------------- searched user boxes --------------
            echo "<div>";
            echo "<label>".$user['login']."</label>";
            echo ""; # ------ тут должна быть аватарка в виде квадратного блока наверное но пока мне лень это делать, тк нет нормальной загрузки аватарки, этим я займусь
            echo "<label>".$user['name']." ".$user['surname']." ".$user['thirdname']."</label>";
            echo "<a href='../account.php?back=users/search.php&user=".$user['login']."'>Перейти к профилю</a>";
            echo "</div>";
          }
        }else{
          echo "<label>Пользователь не найден.</label>";
        }
      }
    }
    ?>
  </form>

</main>
</body>
</html>
