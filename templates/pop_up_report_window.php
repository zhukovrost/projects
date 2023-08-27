<?php
if (isset($_POST['message']) && $user_data->check_the_login(false)){
    $report = new Report($user_data->get_id(), $_POST['message'], $_POST['rate']);
    $error_array = $report->insert($conn);
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
            <input id="item_5" class="star_item" type="radio" name="rate" value="5">
            <label for="item_5" class="star_label"></label>
            <input id="item_4" class="star_item" type="radio" name="rate" value="4">
            <label for="item_4" class="star_label"></label>
            <input id="item_3" class="star_item" type="radio" name="rate" value="3">
            <label for="item_3" class="star_label"></label>
            <input id="item_2" class="star_item" type="radio" name="rate" value="2">
            <label for="item_2" class="star_label"></label>
            <input id="item_1" class="star_item" type="radio" name="rate" value="1">
            <label for="item_1" class="star_label"></label>
          </div>
        </div>
      </div>
      <div class="text">
        <h1>Your comment</h1>
        <textarea name="message" placeholder="Type something..."></textarea>
      </div>
    </div>
    <button class="send" type="submit">Send</button>
  </form>
  <?php if ($error_array['fill_all_the_fields']){ print_message("Заполните все поля", 2); } ?>
</div>