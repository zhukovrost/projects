<?php
include "templates/func.php";
include "templates/settings.php";

$user_data->redirect_logged();
$error_array = array(
    "reg_fill_all_input_fields" => false,
    "reg_login_is_used" => false,
    "reg_passwords_are_not_the_same" => false,
    "reg_conn_error" => false,
    "reg_success" => false,
    "too_long_string" => false,
    "adding_stats" => false,
    "log_conn_error" => false,
    "log_fill_all_input_fields" => false,
    "log_incorrect_login_or_password" => false
);

if (isset($_POST['reg'])){
    $error_array = $user_data->reg($conn, $_POST['reg_login'], $_POST['reg_status'], $_POST['reg_password'], $_POST['reg_password2'], $_POST['reg_name'], $_POST['reg_surname'], $_POST['reg_email']);
}

if (isset($_POST['log'])){
    $error_array = $user_data->authenticate($conn, $_POST['log_login'], $_POST['log_password']);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php inc_head("OpenDoor", true); ?>
<body class="log_reg_body">
    <div class="container">
        <!-- Log and reg logo -->
        <a href="index.php" class="logo">
            <img src="img/logo_reg_log.svg" alt="">
            <p>Training</p>
        </a>

        <!-- Content of log & reg -->
        <section class="content">
            <!-- title -->
            <h1>ЛИЧНЫЙ КАБИНЕТ</h1>
            <!-- Switch buttons (login or registration) -->
            <div class="buttons">
                <button class="log_button active">Вход</button>
                <button class="reg_button">Регистрация</button>
            </div>
            <!-- Login form -->
            <form class="log_form" action="" method="post">
                <label for="login_entry">Логин</label>
                <input name="log_login" type="text" id="login_entry">
                <label for="password_entry">Пароль</label>
                <input name="log_password" type="password" id="password_entry">
                <button type="submit" name="log" value="1">Войти</button>
                <a class="forgot_password" href="">Не помнишь пароль?</a>
                <?php
                log_warning($error_array['log_incorrect_login_or_password'], "Неправильный логин или пароль");
                log_warning($error_array['log_fill_all_input_fields'], "Заполните все поля");
                if ($error_array['log_conn_error']){ log_warning($error_array['log_conn_error'], "Ошибка: " . $conn->error); };
                if (isset($_GET['please_log'])){ echo "<p class=''> Пожалуйста авторизуйтесь</p>"; }
                if (isset($_GET['reg'])){ echo "<p class=''>Регистрация прошла успешно, пожалуйста авторизуйтесь</p>"; }
                ?>
            </form>
            <!-- Registration form -->
            <form class="reg_form" action="" method="post">
                <label for="name">Имя</label>
                <input name="reg_name" type="text" id="name">
                <label for="surname">Фамилия</label>
                <input name="reg_surname" type="text" id="surname">
                <h2>Выберите профиль</h2>
                <!-- User's profile -->
                <div class="profiles">
                    <div>
                        <input type="radio" name="reg_status" id="sportsman" value="user">
                        <label for="sportsman">Спортсмен</label>
                    </div>
                    <div>
                        <input type="radio" name="reg_status" id="coach" value="coach">
                        <label for="sportsman">Тренер</label>
                    </div>
                    <div>
                        <input type="radio" name="reg_status" id="doctor" value="doctor">
                        <label for="sportsman">Врач</label>
                    </div>
                </div>
                <label for="email">Почта</label>
                <input name="reg_email" type="email" id="email">
                <label for="login">Логин</label>
                <input name="reg_login" type="text" id="login">
                <label for="password">Пароль</label>
                <input name="reg_password" type="text" id="password">
                <label for="check_password">Подтвердите пароль</label>
                <input name="reg_password2" type="text" id="check_password">
                <button type="submit" name="reg" value="1">Зарегистрироваться</button>
                <?php
                reg_warning($error_array['reg_login_is_used'], "This login is not available");
                reg_warning($error_array['reg_passwords_are_not_the_same'], "Passwords are not equal, try again");
                reg_warning($error_array['reg_fill_all_input_fields'], "Fill all the fields");
                reg_warning($error_array["too_long_string"], "Too long string");
                if ($error_array['reg_conn_error']){ reg_warning($error_array['reg_conn_error'], "Error: " . $conn->error); }
                $conn->close();
                ?>
            </form>
        </section>
    </div>

    <script>
        // Switch buttons (login or registration)
        let logButton = document.querySelector('.log_button');
        let regButton = document.querySelector('.reg_button');
        let logForm = document.querySelector('.log_form');
        let regForm = document.querySelector('.reg_form');

        logButton.addEventListener('click', function(){
            if (logButton.classList.contains('active') == false){
                logButton.classList.add('active');
                regButton.classList.remove('active');
                logForm.style.cssText = `display: flex;`;
                regForm.style.cssText = `display: nonr;`;
            }
        });

        regButton.addEventListener('click', function(){
            if (regButton.classList.contains('active') == false){
                logButton.classList.remove('active');
                regButton.classList.add('active');
                regForm.style.cssText = `display: flex;`;
                logForm.style.cssText = `display: none;`;
            }
        });
    </script>
</body>
</html>