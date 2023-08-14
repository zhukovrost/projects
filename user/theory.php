<?php
include '../templates/func.php';
include '../templates/settings.php';

$title = "Theory";
$auth = $user_data['auth'];


$error_array = array(
  "theme_success" => false,
  "theme_error" => false,
  "theory_select_error" => false,
  "existing_theme" => false,
  "auth_error" => false,
  "content_success" => false,
  "content_error" => false
);

#-------------- add new theme to db ----------------

if (isset($_POST['add_theme']) && $auth){
  if ($_POST['add_theme'] != "" || $_POST['add_theme'] != null){
    $check_sql = "SELECT id FROM themes WHERE theme=".$_POST['add_theme'];
    if ($conn->query($check_sql)->num_rows == 0){
      $add_theme_sql = "INSERT INTO themes(theme) VALUES('".$_POST['add_theme']."')";
      if($conn->query($add_theme_sql)){
        $error_array['theme_success'] = true;
      }else{
        $error_array['theme_error'] = true;
      }
    }else{
      $error_array['existing_theme'] = true;
    }
  }
}

# ----------------- select all themes ---------------------------

$themes = array();
$get_themes_sql = "SELECT id, theme FROM themes ORDER BY theme ASC";
if($result = $conn->query($get_themes_sql)){
  foreach($result as $option){
    array_push($themes, $option);
  }
  $result -> free();
} else {
  $error_array["theme_error"] = true;
}

# -------------------- add new content --------------------

if (isset($_POST['theme_id']) && $_POST['add_content'] != '' && $auth){
  $insert_sql = "INSERT INTO theory (theory, theme, user, date) VALUES ('".$_POST['add_content']."', '".$_POST['theme_id']."', ".$user_data['id'].", ".time().")";
  if ($conn->query($insert_sql)){
    $error_array['content_success'] = true;
  }else{
    $error_array['content_error'] = true;
  }
}else if (!$auth && isset($_POST['theme_id'])){
  $error_array['auth_error'] = true;
}


# --------------- edit content ------------------

if (isset($_POST['theory_id']) && $auth){
  if ($_POST['edit_content'] == ''){
    $sql = "DELETE FROM theory WHERE id=".$_POST['theory_id'];
  }else{
    $sql = "UPDATE theory SET theory='".$_POST['edit_content']."', user=".$user_data['id'].", date=".time()." WHERE id=".$_POST['theory_id'];
  }

  if ($conn -> query($sql)){
    $error_array['content_success'] = true;
  }else{
    $error_array['content_error'] = true;
  }

}else if (isset($_POST['theory_id']) && !$auth){
  $error_array['auth_error'] = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include "../templates/head.php"; ?>
<body>
<!-- Main header -->

<?php include "../templates/header.html"; ?>

<!-- Navigation -->
<nav class="theory_nav">
  <!-- Search by tag -->
  <div class="search">
    <p>Search: </p>
    <input type="text" placeholder="write tag">
  </div>
  <!-- Most popular themes -->
  <div class="themes">
    <div class="popular">
      <h1><button>All</button> - <button></button> - <button></button></h1>
    </div>
    <div class="medium_popular">
      <h1><button>None</button> - <button>None</button> - <button>None</button></h1>
    </div>
    <div class="unpopular">
      <h1><button>None</button> - <button>None</button> - <button>None</button></h1>
    </div>
  </div>

</nav>

<main>
  <div class="container">

    <section class="theory_block">
      <?php foreach ($themes as $theme){ ?>
        <!-- Tags of current theory -->
        <div class="item_cover">
          <button class="close"><img src="../img/крест.svg" alt=""></button>
          <div class="underline_title">
            <h2><span><?php echo $theme['theme']; ?></span></h2>
          </div>
          <!-- Theory item -->
          <div class="item">
            <?php
            $select_sql = "SELECT * FROM theory WHERE theme=".$theme['id']." ORDER BY id DESC";

            if ($result_sql = $conn->query($select_sql)){
              if ($result_sql->num_rows != 0){
                foreach ($result_sql as $content) { ?>
                  <form class="content" method="post">
                    <p><?php echo $content['theory']; ?></p>
                    <div>
                      <!-- Edit button -->
                      <div class="buttons">
                          <button type="button">Edit <img src="../img/edit.svg" alt=""></button>
                          <button type="button">Cancel <img src="../img/cancel.svg" alt=""></button>
                          <button type="submit">Save <img src="../img/save.svg" alt=""></button>
                      </div>
                      <!-- User, date, time -->
                      <div class="content_caption">
                        <p class="user"><?php echo get_user_data($conn, $content['user'], true)['name']; ?></p>
                        <p class="time"><?php echo date("H:i", $content['date']); ?></p>
                        <p class="date"><?php echo date("d.m.Y", $content['date']); ?></p>
                      </div>
                    </div>

                    <input type="hidden" name="theory_id" value="<?php echo $content['id']; ?>">
                    <input type="hidden" name="edit_content" value="">
                  </form>
                <?php }
              }?>
              <form class="new_message" method="post">
                <h1>Add new content</h1>
                <div>
                  <textarea name="add_content" id="" placeholder="type something..."></textarea>
                  <input type="hidden" name="theme_id" value="<?php echo $theme['id']; ?>">
                  <button type="submit">Add</button>
                </div>
              </form>
            <?php }else{
              $error_array['theory_select_error'] = true;
            }
            ?>
          </div>
          <button class="view_all">View all</button>
        </div>
      <?php } ?>
    </section>


    <?php if ($auth) { ?>

      <!-- Block to add new post -->
      <section class="new_post">
        <h1>Add a new theme</h1>
        <!-- Form to write tags -->
        <form method="post">
          <p>New theme:</p>
          <input type="text" placeholder="write theme" name="add_theme">
          <button class="add_post">Add</button>
        </form>
      </section>
    <?php } ?>
  </div>
</main>

<?php include "../templates/footer.html"; ?>


<script src="../templates/format.js"></script>
<script>
    // ===SEARCH===
    // Tag search
    const search_input = document.querySelector('.theory_nav .search input');
    search_input.oninput = function(){
        let val = this.value.trim().replaceAll(' ', '').toUpperCase();
        let testThemes = document.querySelectorAll('.theory_block .underline_title h2');
        if(val != ''){
            for(let i = 0; i < testThemes.length; i++){
                let titlesArr = Array.from(testThemes[i].children);
                count = 0;
                titlesArr.forEach(function(elem){
                    if(elem.innerText.trim().replaceAll(' ', '').toUpperCase().search(val) == -1){
                        count += 1;
                    }
                });

                if(count == titlesArr.length){
                    let cur_test = testThemes[i];
                    cur_test = cur_test.parentNode.parentNode;
                    cur_test.classList.add('hide');
                }
                else{
                    let cur_test = testThemes[i];
                    cur_test = cur_test.parentNode.parentNode;
                    cur_test.classList.remove('hide');
                }
            }
        }
        else{
            for(let i = 0; i < testThemes.length; i++){
                let cur_test = testThemes[i].parentNode.parentNode;
                cur_test.classList.remove('hide');
            }
        }
    }

    // View all
    let viewButtons = document.querySelectorAll('.theory_block .view_all');
    let themeTitles = document.querySelectorAll('.theory_block .underline_title');
    let themeItems = document.querySelectorAll('.theory_block .item');
    let closeButtons = document.querySelectorAll('.theory_block .close');
    const theoryFooter = document.querySelector('footer')

    for(let i = 0; i < viewButtons.length; i++){
        viewButtons[i].addEventListener('click', function(){
            theoryFooter.style.cssText = `display:none`;

            for(let j = 0; j < themeItems.length; j++){
                viewButtons[j].classList.add('hide');
                if(i != j){
                    themeTitles[j].style.cssText = `display: none`;
                    themeItems[j].style.cssText = `display: none`;
                }
            }

            themeItems[i].style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                padding: 10vh 10vw;
                min-height: 100vh;
                height: auto;
                max-height: none;
            `;
            themeTitles[i].style.cssText = `
                position: absolute;
                z-index: 1000;
                top: 0;
                left: 0;
                margin: 10px 20px;
                color: #5a5a5a;
            `;
            closeButtons[i].style.cssText = `display: block`;
        });
    }

    for(let i = 0; i < closeButtons.length; i++){
        closeButtons[i].addEventListener('click', function(){
            theoryFooter.style.cssText = `display: flex`;

            for(let j = 0; j < themeItems.length; j++){
                viewButtons[j].classList.remove('hide');
                themeItems[j].style.cssText = `display: block`;
            }

            themeItems[i].style.cssText = `
                position: relative;
                top: none;
                left: none;
                padding: 20px 30px;
                max-height: 55vh;
            `;
            themeTitles[i].style.cssText = `
                position: static;
                z-index: 1;
                top: none;
                left: none;
                margin: none;
                margin-bottom: 5px;
                color: #000000;
            `;
            closeButtons[i].style.cssText = `display: none`;
        });
    }


    let AllThemes = Array.from(document.querySelectorAll('.theory_block .item_cover'));
    let sortedPopularThemes = [];

    for(let i = 0; i < AllThemes.length; i++){
        sortedPopularThemes[i] = {
            name: AllThemes[i].children[1].children[0].children[0].innerHTML,
            count: AllThemes[i].children[2].children.length
        }
    }

    sortedPopularThemes.sort((a, b) => a.count > b.count ? 1 : 1);

    let popular = document.querySelectorAll('.theory_nav .themes .popular h1 button');
    let mediumPopular = document.querySelectorAll('.theory_nav .themes .medium_popular h1 button');
    let unpopular = document.querySelectorAll('.theory_nav .themes .unpopular h1 button');
    let AllThemesNav = document.querySelectorAll('.theory_nav .themes h1 button');

    for(let i = 1; i < 3; i++){
        if(i < sortedPopularThemes.length){
            popular[i].innerHTML = sortedPopularThemes[i - 1].name;
        }
    }

    for(let i = 0; i < 3; i++){
        if(i + 2 < sortedPopularThemes.length){
            mediumPopular[i].innerHTML = sortedPopularThemes[i + 2].name;
        }
    }

    for(let i = 0; i < 3; i++){
        if(i + 5 < sortedPopularThemes.length){
            unpopular[i].innerHTML = sortedPopularThemes[i + 5].name
            ;
        }
    }

    let FullAllThemesNav = [];
    for(let i = 0; i < AllThemesNav.length; i++){
        FullAllThemesNav[i] = AllThemesNav[i].innerHTML;
    }

    for(let i = 0; i < AllThemesNav.length; i++){
        if (String(AllThemesNav[i].innerHTML).length > 15){

            AllThemesNav[i].innerHTML = String(AllThemesNav[i].innerHTML).slice(0, 15) + '...';
        }
    }

    let themesNavigation = document.querySelectorAll('.theory_nav .themes button');
    let testThemes = document.querySelectorAll('.theory_block .underline_title h2');

    for(let k = 0; k < themesNavigation.length; k++){
        themesNavigation[k].addEventListener('click', function(){
            if(FullAllThemesNav[k] == "All"){
                for(let i = 0; i < testThemes.length; i++){
                    let cur_test = testThemes[i].parentNode.parentNode;
                    cur_test.classList.remove('hide');
                }
            }
            else{
                let val = FullAllThemesNav[k].trim().replaceAll(' ', '').toUpperCase();
                for(let i = 0; i < testThemes.length; i++){
                    let titlesArr = Array.from(testThemes[i].children);
                    count = 0;
                    titlesArr.forEach(function(elem){
                        if(elem.innerText.trim().replaceAll(' ', '').toUpperCase().search(val) == -1){
                            count += 1;
                        }
                    });

                    if(count == titlesArr.length){
                        let cur_test = testThemes[i];
                        cur_test = cur_test.parentNode.parentNode;
                        cur_test.classList.add('hide');
                    }
                    else{
                        let cur_test = testThemes[i];
                        cur_test = cur_test.parentNode.parentNode;
                        cur_test.classList.remove('hide');
                    }
                }
            }
        });
    }


    
    // Buttons to add new messages
    let messageButtons = document.querySelectorAll('.theory_block .item .content .buttons');
    let contentsP = document.querySelectorAll('.theory_block .item .content p');
    let contentsBlock = document.querySelectorAll('.theory_block .item .content');

    for(let i = 0; i < messageButtons.length; i++){
        let buttonsArr = messageButtons[i].children;
        buttonsArr[1].style.cssText = `display:none`;
        buttonsArr[2].style.cssText = `display:none`;
    }

    for(let i = 0; i < messageButtons.length; i++){
        let buttonsArr = messageButtons[i].children;
        let prText = contentsBlock[i].firstElementChild.innerHTML;

        buttonsArr[0].addEventListener('click', function(){
            buttonsArr[0].style.cssText = `display:none`;
            buttonsArr[1].style.cssText = `display:flex`;
            buttonsArr[2].style.cssText = `display:flex`;


            let curText = contentsBlock[i].firstElementChild.innerHTML;
            let NewElem = document.createElement('textarea');
            NewElem.value = curText;
            NewElem.name = 'edit_content';
            NewElem.style.height = (contentsBlock[i].firstElementChild.scrollHeight) + "px";
            contentsBlock[i].removeChild(contentsBlock[i].firstElementChild);

            
            contentsBlock[i].prepend(NewElem);
        });

        buttonsArr[1].addEventListener('click', function(){
            buttonsArr[0].style.cssText = `display:flex`;
            buttonsArr[1].style.cssText = `display:none`;
            buttonsArr[2].style.cssText = `display:none`;

            let NewElem = document.createElement('p');
            
            NewElem.innerHTML = prText;
            contentsBlock[i].removeChild(contentsBlock[i].firstElementChild);

            
            contentsBlock[i].prepend(NewElem);
        });

        buttonsArr[2].addEventListener('click', function(){
            buttonsArr[0].style.cssText = `display:flex`;
            buttonsArr[1].style.cssText = `display:none`;
            buttonsArr[2].style.cssText = `display:none`;

            let curText = contentsBlock[i].firstChild.value;
            let NewElem = document.createElement('p');
            NewElem.innerHTML = curText;
            contentsBlock[i].removeChild(contentsBlock[i].firstElementChild);

            contentsBlock[i].prepend(NewElem);
        });
    }

    
    // Прокрутка к текущему блоку
    let editButtons = document.querySelectorAll('.theory_block .item .content button[type="submit"]');

    if(localStorage.getItem('ScrollToBlock')){
      window.scrollTo(0, localStorage.getItem('ScrollToBlock'));
    }

    
    
    for(let i = 0; i < editButtons.length; i++){
      editButtons[i].addEventListener('click', function(){
        localStorage.setItem('ScrollToBlock', window.pageYOffset);
      });
    }

</script>
<script src="../main.js"></script>
</body>
</html>