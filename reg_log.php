<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/adaptation.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body class="log_reg_body">
    <div class="container">
        <!-- Log and reg logo -->
        <a href="welcome.html" class="logo">
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
            <form class="log_form" action="">
                <label for="login_entry">Логин</label>
                <input name="log_login" type="text" id="login_entry">
                <label for="password_entry">Пароль</label>
                <input name="log_password" type="password" id="password_entry">
                <button type="submit">Войти</button>
                <a class="forgot_password" href="">Не помнишь пароль?</a>
            </form>
            <!-- Registration form -->
            <form class="reg_form" action="">
                <label for="name">Имя</label>
                <input name="reg_name" type="text" id="name">
                <label for="surname">Фамилия</label>
                <input name="reg_surname" type="text" id="surname">
                <h2>Выберите профиль</h2>
                <!-- User's profile -->
                <div class="profiles">
                    <div>
                        <input type="radio" name="profile" id="sportsman">
                        <label for="sportsman">Спортсмен</label>
                    </div>
                    <div>
                        <input type="radio" name="profile" id="coach">
                        <label for="sportsman">Тренер</label>
                    </div>
                    <div>
                        <input type="radio" name="profile" id="doctor">
                        <label for="sportsman">Врач</label>
                    </div>
                </div>
                <label for="email">Почта</label>
                <input name="reg_email" type="text" id="email">
                <label for="login">Логин</label>
                <input name="reg_login" type="text" id="login">
                <label for="password">Пароль</label>
                <input name="reg_password" type="text" id="password">
                <label for="check_password">Подтвердите пароль</label>
                <input name="reg_password2" type="text" id="check_password">
                <button type="submit">Зарегистрироваться</button>
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