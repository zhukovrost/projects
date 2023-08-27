<?php
if ($select_result = $conn -> query($select_sql)){
  foreach($select_result as $report_data){
    $user = new User($conn, $report_data['user']);
    $report = new Report($report_data['user'], $report_data['message'], $report_data['rate']);
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
          <p><?php echo $report->rate; ?> / 5</p>
          <?php for ($i = 1; $i <= 5; $i++){?>
            <img src="../img/Star <?php if ($i <= $report->rate){ echo 1; }else{ echo 5; } ?>.svg" alt="">
          <?php } ?>
        </div>
        <p class="text"><?php echo $report->message; ?></p>
      </div>
      <!-- Date of report -->
      <p class="caption"><?php echo $report->dates(); ?></p>
    </swiper-slide>

    <?php
  } # end loop for
  $select_result->free();
}else{
  echo "Ошибка: " . $conn->error;
}
?>
