<?php
include "templates/settings.php";
include "templates/func.php";
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
  <link rel="stylesheet" href="css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com"> 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;600;700&family=Montserrat+Alternates:ital,wght@0,200;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,800&display=swap" rel="stylesheet">
</head>
<body>
<header>
  <?php
  if (empty($_COOKIE['login']) || $_COOKIE['login'] == '') {
    echo '
    <div class="regenlog_cover">
        <a class="log_button" href="log.php">Войти</a>
        <a class="reg_button" href="reg.php">Зарегистрироваться</a>
    </div>
    ';
  }else{
    $select_sql = "SELECT name, surname FROM users WHERE login='".$_COOKIE['login']."'";
    conn_check($conn);
    if ($select_result = $conn->query($select_sql)){
      foreach ($select_result as $item){
        $name = $item['name'];
        $surname = $item['surname'];
      }

      if (check_if_admin($conn, $_COOKIE['login'])){
        echo '
        <div class="user_cover">
          <p class="user_name" ><a href="construct/construct.php">'.$surname.' '.$name. '(админ)</a></p>
          <a class="header_logout_btn" href="clear.php">Выйти</a>
        </div>
        ';
      }else{
        echo '
        <div class="user_cover">
          <p class="user_name">'.$surname.' '.$name.'</p>
          <a class="header_logout_btn" href="clear.php">Выйти</a>
        </div>
        ';
      }
      $select_result->free();
    }
  }
  $conn->close();
  ?>
  <a href=""><img class="header_question" src="img/question.png" alt=""></a>
  <div class="container">
    <div class="header_title_block">
      <p class="header_subtitle">The platform</p>
      <h1 class="header_title">Roman/24</h1>
      <p class="header_subtitle">for education</p>
    </div>
    
    <nav>
      <ul>
        <li><a href="user/my_tests.php">Tests</a></li>
        <li><a href="#">Lorem</a>
          <ul>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
          </ul>
        </li>
        <li><a href="#">Lorem</a>
          <ul>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
          </ul>
        </li>
        <li><a href="#">Lorem</a>
          <ul>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
          </ul>
        </li>
        <li><a href="#">Lorem</a>
          <ul>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
            <li><a href="#">ipsum</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</header>

<main>
  <div class="info">
    <p>Lorem ipsum?</p>
  </div>
  <div class="card_cover card_cover_first">
    <div class="card">
      <div class="circle">
      </div>
      <div class="content">
        <h2>Lorem ipsum</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam ut perspiciatis quibusdam voluptates minima delectus necessitatibus, dolorum incidunt fugit officia corporis, numquam illum, ipsum aut quisquam ipsa dolore libero. Labore!</p>
      </div>
      <img class="logo" src="img/Books.png" alt="">
    </div>
  </div>
  <div class="card_cover card_cover_second">
    <div class="card">
      <div class="circle">
      </div>
      <div class="content">
        <h2>Lorem ipsum</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam ut perspiciatis quibusdam voluptates minima delectus necessitatibus, dolorum incidunt fugit officia corporis, numquam illum, ipsum aut quisquam ipsa dolore libero. Labore!</p>
      </div>
      <img class="logo" src="img/Books.png" alt="">
    </div>
  </div>
  <div class="card_cover card_cover_third">
    <div class="card">
      <div class="circle">
      </div>
      <div class="content">
        <h2>Lorem ipsum</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam ut perspiciatis quibusdam voluptates minima delectus necessitatibus, dolorum incidunt fugit officia corporis, numquam illum, ipsum aut quisquam ipsa dolore libero. Labore!</p>
      </div>
      <img class="logo" src="img/Books.png" alt="">
    </div>
  </div>
  <div class="card_cover card_cover_fourth">
    <div class="card">
      <div class="circle">
      </div>
      <div class="content">
        <h2>Lorem ipsum</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam ut perspiciatis quibusdam voluptates minima delectus necessitatibus, dolorum incidunt fugit officia corporis, numquam illum, ipsum aut quisquam ipsa dolore libero. Labore!</p>
      </div>
      <img class="logo" src="img/Books.png" alt="">
    </div>
  </div>

</main>
<?php include "templates/footer.html"; ?>
<script src="main.js"></script>
</body>
</html>