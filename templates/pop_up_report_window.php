<?php
$error_array = array(
  "fill_all_the_fields" => false,
  "success_new_report" => false
);

if (isset($_POST['report']) && check_the_login($user_data, "../", false)){
  if ($_POST['report'] != "" && isset($_POST['stars'])){
    $new_date = time();
    $new_message = $_POST['report'];
    $new_rate = $_POST['stars'];


    $insert_sql = "INSERT INTO reports(user, message, rate, date) VALUES(".$user_data['id'].", '".$new_message."', ".$new_rate.", ".$new_date.")";
    if ($conn -> query($insert_sql)){
      $error_array['success_new_report'] = true;
    }
  }else{
    $error_array['fill_all_the_fields'] = true;
  }
}
?>

<div class="popup_feedback">
  <form class="cover" method="post">
    <button type="button" class="close">
      <img src="../img/cross.svg" alt="">
    </button>
    <div method="post" class="content">
      <div class="rating">
        <h1>Ваша оценка</h1>
        <div class="stars_wrapper">
          <div class="stars">
            <input id="item_5" class="star_item" type="radio" name="stars" value="5">
            <label for="item_5" class="star_label"></label>
            <input id="item_4" class="star_item" type="radio" name="stars" value="4">
            <label for="item_4" class="star_label"></label>
            <input id="item_3" class="star_item" type="radio" name="stars" value="3">
            <label for="item_3" class="star_label"></label>
            <input id="item_2" class="star_item" type="radio" name="stars" value="2">
            <label for="item_2" class="star_label"></label>
            <input id="item_1" class="star_item" type="radio" name="stars" value="1">
            <label for="item_1" class="star_label"></label>
          </div>
        </div>
      </div>
      <div class="text">
        <h1>Your comment</h1>
        <textarea name="report" placeholder="Type something..."></textarea>
      </div>
    </div>
    <?php if ($error_array['fill_all_the_fields']){ print_warning("Заполните все поля"); } ?>
    <button class="send" type="submit">Send</button>
  </form>
</div>