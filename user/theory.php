<?php
include '../templates/func.php';
include '../templates/settings.php';

$title = "Theory";
$auth = $user_data['auth'];

$error_array = array(
  "theme_success" => false,
  "theme_error" => false,
  "theory_select_error" => false,
  "existing_theme" => false
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
        <!-- Most popular themes
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
        -->
    </nav>

    <main>
        <div class="container">

          <?php foreach ($themes as $theme){ ?>
          <section class="theory_block">
            <!-- Tags of current theory -->
            <div>
              <button class="close"><img src="../img/крест.svg" alt=""></button>
              <div class="underline_title">
                <h2><span><?php echo $theme['theme']; ?></span></h2>
              </div>
              <!-- Block with all theory -->
              <!-- Theory item -->
              <div class="item">
                <?php
                if (isset($_GET['view_all'])){
                  if ((int)$_GET['view_all'] == (int)$theme['id']){
                    $select_sql = "SELECT * FROM theory WHERE theme=".$theme['id']." ORDER BY id DESC";
                  }else{
                    $select_sql = "SELECT * FROM theory WHERE theme=".$theme['id']." ORDER BY id DESC LIMIT 3";
                  }
                }else{
                  $select_sql = "SELECT * FROM theory WHERE theme=".$theme['id']." ORDER BY id DESC LIMIT 3";
                }

                if ($result_sql = $conn->query($select_sql)){
                  if ($result_sql->num_rows != 0){
                    foreach ($result_sql as $content) { ?>
                      <div class="content">
                        <p><?php echo $content['theory']; ?></p>
                        <div>
                          <!-- Edit button -->
                          <button>Edit <img src="../img/edit.svg" alt=""></button>
                          <!-- User, date, time -->
                          <div class="content_caption">
                            <p class="user"><?php echo get_user_data($conn, $content['user'], true)['name']; ?></p>
                            <p class="time"><?php echo date("H:i", $content['date']); ?></p>
                            <p class="date"><?php echo date("d.m.Y", $content['date']); ?></p>
                          </div>
                        </div>

                      </div>
                  <?php }
                    if ($result_sql->num_rows > 3) { ?>
                    <a class="view_all" href="theory.php?view_all=<?php echo $theme['id']; ?>">View all</a>
                  <?php }
                  }?>
                  <button>Add new content</button>
                <?php }else{
                  $error_array['theory_select_error'] = true;
                }
                ?>
              </div>

          <?php } if ($auth) { ?>

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
    const theoryFooter = document.querySelector('.theory_footer')

    for(let i = 0; i < viewButtons.length; i++){
        viewButtons[i].addEventListener('click', function(){
            theoryFooter.style.cssText = `display:none`;

            for(let j = 0; j < themeItems.length; j++){
                viewButtons[j].classList.add('hide');
                if(i != j){
                    themeItems[j].style.cssText = `display: none`;
                }
            }

            themeItems[i].style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                padding: 10vh 10vw;
                min-height: 100vh;
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
    
    // Most popular themes
    function sortByCount(array)
        {
            if( !Array.isArray(array) )
                throw new TypeError('Неправильный тип, ожидался массив, получен ' + typeof array);

            let valuesMap = new Map();

            array.forEach(elem => {

                valuesMap.set(elem, valuesMap.has(elem) ? valuesMap.get(elem) + 1 :  1);
            });

            let arr =  [...valuesMap.entries()].sort((a, b) => b[1] - a[1]);

            return arr.map(value => value[0]);
        }

    let popularThemes = Array.from(document.querySelectorAll('.theory_block .underline_title h2 span'));
    for(let i = 0; i < popularThemes.length; i++){
        popularThemes[i] = popularThemes[i].innerHTML;
    }

    sortedPopularThemes = sortByCount(popularThemes);

    let popular = document.querySelectorAll('.theory_nav .themes .popular h1 button');
    let mediumPopular = document.querySelectorAll('.theory_nav .themes .medium_popular h1 button');
    let unpopular = document.querySelectorAll('.theory_nav .themes .unpopular h1 button');
    
    for(let i = 1; i < 3; i++){
        if(i < sortedPopularThemes.length){
            popular[i].innerHTML = sortedPopularThemes[i - 1];
        }
    }

    for(let i = 0; i < 3; i++){
        if(i + 2 < sortedPopularThemes.length){
            mediumPopular[i].innerHTML = sortedPopularThemes[i + 2];
        }
    }

    for(let i = 0; i < 3; i++){
        if(i + 5 < sortedPopularThemes.length){
            unpopular[i].innerHTML = sortedPopularThemes[i + 5];
        }
    }
    
    let themesNavigation = document.querySelectorAll('.theory_nav .themes button');
    let testThemes = document.querySelectorAll('.theory_block .underline_title h2');
    
    for(let k = 0; k < themesNavigation.length; k++){
        themesNavigation[k].addEventListener('click', function(){
            if(themesNavigation[k].innerHTML == "All"){
                for(let i = 0; i < testThemes.length; i++){
                    let cur_test = testThemes[i].parentNode.parentNode;
                    cur_test.classList.remove('hide');
                }
            }
            else{
                let val = themesNavigation[k].innerHTML.trim().replaceAll(' ', '').toUpperCase();
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

    </script>
    <script src="../main.js"></script>
</body>
</html>