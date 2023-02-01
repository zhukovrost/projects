<?php
include '../templates/func.php';
include '../templates/settings.php';

$conn = new mysqli(HOSTNAME, HOSTUSER, HOSTPASSWORD, HOSTDB);
conn_check($conn);

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

if (isset($_POST['message']) && check_the_login("../", false)){
  if ($_POST['message'] != "" && isset($_POST['rate'])){
    $new_date = date("d.m.Y");
    $new_message = $_POST['message'];
    $new_rate = $_POST['rate'];


    $insert_sql = "INSERT INTO reports(user, message, rate, date) VALUES('".$_COOKIE['login']."', '".$new_message."', '".$new_rate."', '".$new_date."')";
    if ($conn -> query($insert_sql)){
      $error_array['success_new_report'] = true;
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

  <div class="container">
    <?php if ($error_array['fill_all_the_fields']){ print_warning("Заполните все поля"); } ?>
    <section style="width: 70%; border: black 2px solid; background-color: white; padding: 10px; margin: 5px auto">
      <h3 style="font-size: 40px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa cum maiores, nihil nulla quas repellendus! Accusamus architecto deleniti eveniet excepturi illo incidunt ipsa perspiciatis similique tempora vel veniam, voluptates voluptatum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias aliquid architecto cupiditate dignissimos earum est et impedit ipsa ipsam minus odit porro praesentium quae, quidem quis rerum saepe sit voluptas. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab animi, consectetur corporis dicta dolor expedita inventore labore nemo nesciunt nisi sunt, tempora temporibus tenetur? Animi illum nobis odit sed similique!</h3>
    </section>
    <section style="width: 70%; border: black 2px solid; background-color: white; padding: 10px; margin: 5px auto">
      <?php
      if ($page == 1){
        echo "<div class='triangle-left'><</div>";
      }else{
        echo "<a class='triangle-left' href='reports.php?page=". ($page - 1) ."'><</a>";
      }
      ?>
      <div>
        <?php
        if ($select_result = $conn -> query($select_sql)){
          foreach($select_result as $note){
            $user = $note['user'];
            $message = $note['message'];
            $rate = $note['rate'];
            $date = $note['date'];
            $files = json_decode($note['files']);

            echo "<div class='report_item feedback_block'><p class='report_item_name'>".$user." (Оценка - ".$rate."):</p><p>".$message."</p><p class='report_item_date'>".$date."</p></div>";
          }
          $select_result->free();
        }else{
          echo "Ошибка: " . $conn->error;
        }
        ?>
      </div>
      <?php
      if ($page == $total_pages){
        echo "<div class='triangle-right'>></div>";
      }else{
        echo "<a class='triangle-right' href='reports.php?page=". ($page + 1) ."'>></a>";
      }
      echo "<p class='page_reports'>страница ".$page."/".$total_pages."</p>";
      ?>
    </section>
    <section style="width: 70%; border: black 2px solid; background-color: white; padding: 10px; margin: 5px auto">
      <h3 style="font-size: 40px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa cum maiores, nihil nulla quas repellendus! Accusamus architecto deleniti eveniet excepturi illo incidunt ipsa perspiciatis similique tempora vel veniam, voluptates voluptatum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias aliquid architecto cupiditate dignissimos earum est et impedit ipsa ipsam minus odit porro praesentium quae, quidem quis rerum saepe sit voluptas. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab animi, consectetur corporis dicta dolor expedita inventore labore nemo nesciunt nisi sunt, tempora temporibus tenetur? Animi illum nobis odit sed similique!</h3>
    </section>
    <?php
    if (check_the_login("../", false)){
      include "../templates/leave_report.html";
    }else{
      echo "<section><label>Хотите оставить отзыв? <a href='../log.php?please_log=true'>войдите</a> или <a href='../reg.php'>зарегистрируйтесь</a></label></section>";
    }
    ?>
  </div>

</body>
</html>