<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

$user_data = get_user_data($conn, $_COOKIE['login']);

date_default_timezone_set("Europe/Moscow");

$error_array = array(
  "fill_all_the_fields" => false,
  "success_new_report" => false
);

if (isset($_REQUEST['page'])) {
  $page = $_REQUEST['page'];
} else {
  $page = 1;
}
$size_page = 3;
/* ^^^ кол-во отзывов на странице, можешь поменять ^^^ */
$offset = ($page-1) * $size_page;

$count_sql = "SELECT id FROM reports ORDER BY id DESC";

if($result2 = $conn -> query($count_sql)){
  $count_rows = $result2 -> num_rows;
}

$total_pages = ceil($count_rows / $size_page);
if ($total_pages == 0){
  $total_pages = 1;
}

$select_sql = "SELECT * FROM reports ORDER BY id DESC LIMIT $size_page OFFSET $offset";

if (isset($_POST['message']) && check_the_login($user_data, "../", false)){
  if ($_POST['message'] != "" && isset($_POST['rate'])){
    $new_date = time();
    $new_message = $_POST['message'];
    $new_rate = $_POST['rate'];


    $insert_sql = "INSERT INTO reports(user, message, rate, date) VALUES(".$user_data['id'].", '".$new_message."', ".$new_rate.", ".$new_date.")";
    echo $insert_sql;
    if ($conn -> query($insert_sql)){
      $error_array['success_new_report'] = true;
      echo "sus";
    }
  }else{
    $error_array['fill_all_the_fields'] = true;
  }
}

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
<header class="tests_header">
		<a class="back_button" href="../index.php">На главную</a>
</header>
<main>
  <div class="container reports_container">
    <?php if ($error_array['fill_all_the_fields']){ print_warning("Заполните все поля"); } ?>
    <h1 class="title">Отзывы</h1>
    <section>
      <div>
        <?php
        if ($select_result = $conn -> query($select_sql)){
          foreach($select_result as $note){
            $user = $note['user'];
            $message = $note['message'];
            $rate = $note['rate'];
            $date = $note['date'];
            $files = json_decode($note['files']);

            echo "<div class='report_item feedback_block'><p class='report_item_name'>".$user." (Оценка - ".$rate."):</p><p class='report_item_date'>".$date."</p><p>".$message."</p></div>";
          }
          $select_result->free();
        }else{
          echo "Ошибка: " . $conn->error;
        }
        ?>
      </div>
      <div class="pages">
        <?php
        if ($page == 1){
          echo "<div class='triangle-left'></div>";
        }else{
          echo "<a class='triangle-left' href='reports.php?page=". ($page - 1) ."'></a>";
        }
        echo "<p class='page_reports'>страница ".$page."/".$total_pages."</p>";
        if ($page == $total_pages){
          echo "<div class='triangle-right'></div>";
        }else{
          echo "<a class='triangle-right' href='reports.php?page=". ($page + 1) ."'></a>";
        }
        ?>
      </div>
    </section>
    <?php
    if (check_the_login($user_data, "../", false)){
      include "../templates/leave_report.html";
    }else{
      echo "<section><label>Хотите оставить отзыв? <a href='../log.php?please_log=true'>войдите</a> или <a href='../reg.php'>зарегистрируйтесь</a></label></section>";
    }
    ?>
  </div>
</main>
</body>
</html>