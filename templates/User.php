<?php

class User {
    private $id;
    public $login='';
    private $status="user";
    public $online=0;
    public $name="Guest";
    public $surname='';
    public $description='';
    private $email;
    public $avatar=1;
    private $password='';
    public $subscriptions = [];
    public $subscribers = [];
    private $auth=false;
    public $featured_exercises = [];
    public $my_exercises = [];
    public $program;
    public $workout_history = [];


    function set_subscriptions($conn){
        $sql = "SELECT user FROM subs WHERE subscriber=$this->id";
        $this->subscriptions = array();
        if ($result = $conn->query($sql)){
            foreach ($result as $user){
                array_push($this->subscriptions, $user['user']);
            }
        }else{
            echo $conn->query;
        }
    }
    function set_subscribers($conn){
        $sql = "SELECT subscriber FROM subs WHERE user=$this->id";
        $this->subscribers = array();
        if ($result = $conn->query($sql)){
            foreach ($result as $user){
                array_push($this->subscribers, $user['subscriber']);
            }
        }else{
            echo $conn->query;
        }
    }

    public function __construct($conn, $id=-1, $auth=false){
        if (isset($id) && $id != -1) {
            $select_sql = "SELECT * FROM users WHERE id=$id LIMIT 1";
            if ($select_result = $conn->query($select_sql)) {
                foreach ($select_result as $item) {
                    $this->id = $item['id'];
                    $this->login = $item['login'];
                    $this->name = $item['name'];
                    $this->surname = $item['surname'];
                    $this->email = $item['email'];
                    $this->status = $item['status'];
                    $this->avatar = $item['avatar'];
                    $this->password = $item['password'];
                    $this->description = $item['description'];
                    $this->online = $item['online'];
                    $this->featured_exercises = json_decode($item['featured_exercises']);
                    $this->my_exercises = json_decode($item['my_exercises']);
                }
                $this->auth = $auth;
            }else{
                echo $conn -> error;
            }
            $select_result->free();
        }
    }
    public function get_auth(){
        return $this->auth;
    }
    public function get_id(){
        return $this->id;
    }
    public function is_admin(){
        return $this->status == "admin";
    }
    public function get_status(){
        return $this->status;
    }

    public function check_the_login($header=true, $way="../"){
        if (!$this->get_auth()){
            if ($header){
                header('Location: '.$way.'log.php?please_log=1');
            }else{
                return false;
            }
        }
        if (!$header){
            return true;
        }
    }

    public function redirect_logged($way=''){
        if ($this->auth){
            header("Location: ".$way."user/profile.php");
        }
    }

    public function authenticate($conn, $login, $password){
        $error_array = array(
            "log_conn_error" => false,
            "log_fill_all_input_fields" => false,
            "log_incorrect_login_or_password" => false,
            "reg_fill_all_input_fields" => false,
            "reg_login_is_used" => false,
            "reg_passwords_are_not_the_same" => false,
            "reg_conn_error" => false,
            "reg_success" => false,
            "too_long_string" => false,
            "adding_stats" => false
        );

        $login = trim($login);
        $password = trim($password);

        if ($login == "" || $password == ""){
            $error_array['log_fill_all_input_fields'] = true;
            return $error_array;
        }
        $log_sql = "SELECT id, password FROM users WHERE login='$login' LIMIT 1";
        if (!($log_result = $conn->query($log_sql))){
            $error_array['log_conn_error'] = true;
            return $error_array;
        }
        if ($log_result->num_rows == 0){
            $error_array['log_incorrect_login_or_password'] = true;
            return $error_array;
        }

        foreach ($log_result as $check_password){
            $_SESSION["user"] = $check_password['id'];
            if ($check_password['password'] != md5($password)){
                $error_array['log_incorrect_login_or_password'] = true;
                return $error_array;
            }
        }
        header('Location: user/profile.php');
    }

    public function reg($conn, $login, $status, $password, $password2, $name, $surname, $email)
    {
        # ---------- collecting errors ---------------
        $error_array = array(
            "log_conn_error" => false,
            "log_fill_all_input_fields" => false,
            "log_incorrect_login_or_password" => false,
            "reg_fill_all_input_fields" => false,
            "reg_login_is_used" => false,
            "reg_passwords_are_not_the_same" => false,
            "reg_conn_error" => false,
            "reg_success" => false,
            "too_long_string" => false,
            "adding_stats" => false
        );
        # --------- deleting spaces --------------

        $login = trim($login);
        $password = trim($password);
        $password2 = trim($password2);
        $name = trim($name);
        $surname = trim($surname);
        $email = trim($email);

        # --------- checking data --------------

        if ($login == '' || $password == '' || $name == '' || $surname == '') { # checking blank fields
            $error_array['reg_fill_all_input_fields'] = true;
            return $error_array;
        }
        if ($password != $password2) { # checking password equality
            $error_array['reg_passwords_are_not_the_same'] = true;
            return $error_array;
        }
        $password = $password2 = md5($password); # hashing password
        if (strlen($login) > 32 || strlen($surname) > 32 || strlen($email) > 64 || strlen($name) > 32 || strlen($password) > 256) { # checking length
            $error_array["too_long_string"] = true;
            return $error_array;
        }

        $check_sql = "SELECT id FROM users WHERE login='$login'";
        if ($reg_result = $conn->query($check_sql)) { # querying
            $rowsCount = $reg_result->num_rows;
            if ($rowsCount > 0) { # checking existence of the login
                $error_array['reg_login_is_used'] = true;
                return $error_array;
            }
        } else {
            $error_array['reg_conn_error'] = true;
            return $error_array;
        }

        $reg_sql = "INSERT INTO users(login, status, password, name, surname, email) VALUES('$login', '$status', '$password', '$name', '$surname', '$email')";
        if (!($conn->query($reg_sql))) { # querying
            $error_array['reg_conn_error'] = true;
            header("Location: reg_log.php?reg=1");
            # return $error_array;
        }

        insert_news($conn, "Пользователь $login зарегистрировался на платформе.", mysqli_insert_id($conn), false);
        /*
        $stats_sql = "INSERT INTO stats(user) VALUES (LAST_INSERT_ID())";
        if ($conn->query($stats_sql)) { # querying
            $_SESSION["reg_login"] = $login;
            header("Location: log.php?reg=1");
        } else {
            $error_array['adding_stats'] = true;
            return $error_array;
        }
        */
        return $error_array;
    }

    public function update($conn){
        $my_exercise = json_encode($this->my_exercises);
        $featured_exercises = json_encode($this->featured_exercises);
        $sql = "UPDATE users SET my_exercises='$my_exercise', featured_exercises='$featured_exercises' WHERE id=$this->id";
        if ($conn->query($sql)){
            return true;
        }else{
            echo $conn->error;
            return false;
        }
    }

    public function get_avatar($conn){
        $select_sql = "SELECT file FROM avatars WHERE id=$this->avatar";
        if ($result_sql = $conn->query($select_sql)){
            foreach ($result_sql as $item){
                $image = $item['file'];
            }
        }else{
            $image=null;
            echo $conn->error;
        }

        return $image;
    }

    public function update_avatar($conn, $data){
        if ($this->avatar == 1){
            $sql = "INSERT INTO avatars (file) VALUES ('$data')";
        }else{
            $sql = "UPDATE avatars SET file='$data' WHERE id=$this->avatar";
        }
        if ($conn->query($sql)){
            if ($this->avatar == 1){
                $new_avatar_id = mysqli_insert_id($conn);
                $update_sql = "UPDATE users SET avatar=$new_avatar_id WHERE id=$this->id";
                if ($conn->query($update_sql)){
                    header("Refresh: 0");
                }else{
                    echo $conn->error;
                }
            }else{
                header("Refresh: 0");
            }
        }else{
            echo $conn->error;
        }
    }

    public function change_featured($conn, $exercise_id){
        $index = array_search($exercise_id, $this->featured_exercises);
        if (is_numeric($index)) {
            array_splice($this->featured_exercises, $index, 1);
        }else{
            array_push($this->featured_exercises, $exercise_id);
        }

        $this->update($conn);
    }

    public function add_exercise($conn, $exercise_id){
        array_push($this->my_exercises, $exercise_id);
        $this->update($conn);
    }
    public function delete_exercise($conn, $exercise_id){
        $index = array_search($exercise_id, $this->my_exercises);
        if (is_numeric($index)) {
            array_splice($this->my_exercises, $index, 1);
        }
        $this->update($conn);
    }

    public function set_program($conn){
        $select_sql = "SELECT program FROM program_to_user WHERE user=$this->id LIMIT 1";
        if ($result_sql = $conn->query($select_sql)){
            if ($result_sql->num_rows == 0){
                return false;
            }
            foreach ($result_sql as $item){
                $this->program = new Program($conn, $item['program']);
            }
            return true;
        }else{
            echo $conn->error;
            return false;
        }
    }
    public function get_news($conn){
        $sql = "SELECT news.message, news.date, news.personal, avatars.file, users.name, users.surname, users.login FROM ((news INNER JOIN users ON news.user=users.id) INNER JOIN avatars ON users.avatar=avatars.id) WHERE (user=$this->id";
        if (count($this->subscriptions) == 0){
            $this->set_subscriptions($conn);
        }
        if (count($this->subscriptions) != 0){
            $sql .= " OR ";
            foreach ($this->subscriptions as $subscription){
                $sql .= "(user=$subscription AND personal=0) OR ";
            }
            $sql = substr($sql, 0, -4);
        }
        $sql .= ") ORDER BY news.date DESC";
        if ($result = $conn->query($sql)){
            return $result;
        }else{
            echo $conn->error;
            return false;
        }
    }

    public function get_my_news($conn){
        $sql = "SELECT news.message, news.date, news.personal, avatars.file, users.name, users.surname, users.login FROM ((news INNER JOIN users ON news.user=users.id) INNER JOIN avatars ON users.avatar=avatars.id) WHERE user=$this->id ORDER BY date DESC";
        if ($result = $conn->query($sql)){
            return $result;
        }else{
            echo $conn->error;
            return false;
        }
    }

    public function print_workout_history($conn){ ?>
        <section class="last-trainings">
            <h1 class="last-trainings__title">Последние тренировки</h1>
            <div class="last-trainings__content">
                <?php
                    $sql = "SELECT * FROM workout_history WHERE user=$this->id";
                    if ($result = $conn->query($sql)){
                        if ($result->num_rows == 0){
                            echo "<p class='last-trainings__no-workout'>Нет тренировок</p>";
                        }else{
                            foreach ($result as $item){
                                $workout = new Workout($conn, $item['workout'], date("N", $item['date_completed']));
                                $workout->set_muscles();
                                $replacements = array(
                                    "{{ minutes }}" => round($item['time_spent'] / 60, 0),
                                    "{{ muscle_group_amount }}" => $workout->get_groups_amount(),
                                    "{{ exercise_amount }}" => count($workout->exercises),
                                    "{{ link }}" => ''
                                );
                                echo render($replacements, "../templates/workout_history_item.html");
                            }
                        }
                    }else{
                        echo "<p>".$conn->error."</p>";
                    }
                ?>
            </div>
        </section>
    <?php }


    public function get_workout_history($conn){
        $sql = "SELECT workout_history.id, workout_history.date_completed, workouts.exercises, workouts.approaches FROM workout_history INNER JOIN workouts ON workout_history.workout = workouts.id WHERE workout_history.user=$this->id ORDER BY workout_history.date_completed DESC";
        if ($result = $conn->query($sql)){
            foreach ($result as $item){
                array_push($this->workout_history, $item);
            }
        }else{
            echo $conn->error;
        }
    }

    function get_program_amount($conn){
        $sql = "SELECT program FROM program_to_user WHERE user=$this->id";
        if ($result = $conn->query($sql)){
            return $result->num_rows;
        }else{
            echo $conn->error;
        }
    }
}
