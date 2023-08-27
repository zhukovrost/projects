<?php
if ($select_result = $conn -> query($select_sql)){
  foreach($select_result as $report){
    $user = new User($conn, $report['user']);
    $message = $report['message'];
    $rate = $report['rate'];
    $date = date('j F, Y', $report['date']);
    ?>
    <!-- Report's item -->
    <swiper-slide class="reports_item">
      <!-- User's avatar -->
      <div class="avatar">
        <img src="<?php echo $user->get_avatar($conn); ?>">
        <p><?php echo $user->name.' '.$user->surname; ?></p>
      </div>
      <!-- User's report(rating and text) -->
      <div class="content">
        <div class="rating">
          <p><?php echo $rate; ?> / 5</p>
          <?php for ($i = 1; $i <= 5; $i++){?>
            <img src="../img/Star <?php if ($i <= $rate){ echo 1; }else{ echo 5; } ?>.svg" alt="">
          <?php } ?>
        </div>
        <p class="text"><?php echo $message; ?></p>
      </div>
      <!-- Date of report -->
      <p class="caption"><?php echo $date; ?></p>
    </swiper-slide>

    <?php
  } # end loop for
  $select_result->free();
}else{
  echo "Ошибка: " . $conn->error;
}
?>
