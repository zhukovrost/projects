<?php
include '../templates/func.php';
include '../templates/settings.php';

if (!(check_if_admin($user_data, "../"))){ header("Location: ../index.php"); }

$title = "ADD QUESTIONS";
?>
<!doctype html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body class="question_body">
<a class="question_back" href="construct.php">Назад</a>
<div class="c_questions_search">
  <img src="../img/search.svg" alt="">
  <input class="question" type="text" placeholder="Search question">
  <img src="../img/search.svg" alt="">
  <input class="id" type="text" placeholder="Search id">
</div>
<?php
$select_sql = "SELECT * FROM questions ORDER BY id DESC";
if ($select_result = $conn->query($select_sql)){
  foreach ($select_result as $item){ ?>
    <form action='construct.php' method='post'>
      <h2>ID: <span><?php echo $item["id"]; ?></span></h2>
      <h3><?php echo $item["question"]; ?></h3>
      <input type='hidden' value='<?php echo $item["id"]; ?>' name='add_question_from_db_id'>
      <input type='submit' value='ADD'>
      <a href="edit_question.php?id=<?php echo $item['id']; ?>">EDIT</a>
    </form>
  <?php
  }
}
$select_result->free();
?>
<script src="../templates/format.js"></script>
<script>
  // ===SEARCH===
  const question_input = document.querySelector('.c_questions_search .question');
  const id_input = document.querySelector('.c_questions_search .id');
  let idArr = document.querySelectorAll('.question_body form h2 span');
  let questionsArr = document.querySelectorAll('.question_body form h3');

  function InputSearch(val, Array){
      val = val.trim().replaceAll(' ', '').toUpperCase();
      if(val != ''){
          Array.forEach(function(elem){
              if(elem.innerText.trim().replaceAll(' ', '').toUpperCase().search(val) == -1){
                  if (Array == questionsArr){
                    let cur = elem.parentNode;
                    cur.classList.add('hide');
                  }
                  else{
                    let cur = elem.parentNode;
                    cur = cur.parentNode;
                    cur.classList.add('hide');
                  }
                  
              }
              else{
                  if (Array == questionsArr){
                    let cur = elem.parentNode;
                    cur.classList.remove('hide');
                  }
                  else{
                    let cur = elem.parentNode;
                    cur = cur.parentNode;
                    cur.classList.remove('hide');
                  }
                  
              }
          });
      }
      //
      else{
          Array.forEach(function(elem){
              if (Array == questionsArr){
                let cur = elem.parentNode;
                cur.classList.remove('hide');
              }
              else{
                let cur = elem.parentNode;
                cur = cur.parentNode;
                cur.classList.remove('hide');
              }
              
          });
      }
  }


// Theme search

question_input.addEventListener('input', function(){
  InputSearch(question_input.value, questionsArr);
});

id_input.addEventListener('input', function(){
  InputSearch(id_input.value, idArr);
});
</script>
</body>
</html>
