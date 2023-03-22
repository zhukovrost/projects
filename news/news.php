<?php
include "../templates/func.php";
include "../templates/settings.php";

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$news_content = json_decode(file_get_contents("news.json"));
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
    <a href="../users/search.php">Пользователи</a>
    <a href="../news/news.php">Новости</a>
  </nav>
</header>
<main>
  <div class="container">
    <?php
    if (check_the_login("../", false)){
      $news_array = array();
      $login = $_COOKIE['login'];

      $select_sql = "SELECT * FROM news WHERE user='".$login."' AND personal=1";
      if ($select_result = $conn->query($select_sql)){
        foreach ($select_result as $news){
          array_push($news_array, $news);
        }
      }
      $select_result->free();

      $select_subscriptions_sql = "SELECT subscriptions FROM users where login='".$login."'";
      if ($select_subscriptions_result = $conn->query($select_subscriptions_sql)){
        foreach ($select_subscriptions_result as $item){
          $subscriptions = json_decode($item['subscriptions']);
        }

        foreach ($subscriptions as $sub){
          $select_news_sql = "SELECT * FROM news WHERE user='".$sub."' AND personal=0";
          if ($select_news_result = $conn->query($select_news_sql)){
            foreach ($select_news_result as $news){
              array_push($news_array, $news);
            }
          }
          $select_news_result->free();
        }
      }
      $select_subscriptions_result->free();

      $news_array = sort_news_array($news_array);
      foreach ($news_array as $news){
        $date = date("m.d.Y H:i", $news['date']);
        echo "
      <div class='news_block'> 
        <p class='new_label'>".$news_content[$news["new_id"]]."
      ";
        if ($news['additional_info'] != null){
          echo ": ".$news['additional_info'];
        }else{
          echo ": ".$news['user'];
        }
        echo "
        </p>
        <p class='news_date'>".$date."</p>
      </div>
      ";
      }
    }else{
      header('Location: ../log.php');
    }
    ?>
  </div>
</main>
</body>
</html>