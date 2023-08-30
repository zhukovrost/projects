<?php

class User {
    private $id;
    public $login='';
    private $status="user";
    public $online=0;
    public $name="Guest";
    public $surname='';
    private $email;
    public $avatar=1;
    private $password='';
    private $subscriptions = [];
    private $auth=false;
    public $featured_exercises = [];
    public $my_exercises = [];

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
                    $this->subscriptions = json_decode($item['subscriptions']);
                    $this->online = $item['online'];
                    $this->featured_exercises = json_decode($item['featured_exercises']);
                    $this->my_exercises = json_decode($item['my_exercises']);
                }
                $this->auth = true;
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
            header("Location: ".$way."user/workout.php");
        }
    }

    public function authenticate($conn, $login, $password){
        $error_array = array(
            "log_conn_error" => false,
            "log_fill_all_input_fields" => false,
            "log_incorrect_login_or_password" => false
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
        header('Location: index.php');
    }

    public function reg($conn, $login, $status, $password, $password2, $name, $surname, $email)
    {
        # ---------- collecting errors ---------------
        $error_array = array(
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
        $subscriptions = json_encode($this->subscriptions);
        $sql = "UPDATE users SET my_exercises='$my_exercise', featured_exercises='$featured_exercises', subscriptions='$subscriptions' WHERE id=$this->id";
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
}
