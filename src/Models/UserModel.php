<?php
require_once __DIR__ . "/../Controllers/Program.php";

class UserModel
{
    private $id;
    public $login='';
    private $status="user";
    public $name="Guest";
    public $surname='';
    public $description='';
    public $avatar=1;
    private $password='';
    private $auth=false;
    public $subscriptions = [];
    public $subscribers = [];
    public $program;
    public $type;
    public $preparation;
    public $coach = NULL; # only for sportsmen
    public $doctor = NULL; # only for sportsmen
    public $sportsmen = []; # only for coaches and doctors
    public $vk = NULL;
    public $tg = NULL;


    public function __construct($conn, $id = -1, $auth = false) {
        if (isset($id) && $id != -1) { // Проверка, если ID установлен и не равен -1
            $select_sql = "SELECT * FROM users WHERE id=$id LIMIT 1"; // SQL запрос для выбора информации о пользователе по ID
            if ($select_result = $conn->query($select_sql)) {
                if ($item = $select_result->fetch_assoc()) { // Получение строки результата
                    $this->id = $item['id'];
                    $this->login = $item['login'];
                    $this->password = $item['password'];
                    $this->name = $item['name'];
                    $this->surname = $item['surname'];
                    $this->status = $item['status'];
                    $this->avatar = $item['avatar'];
                    $this->description = $item['description'];
                    $this->preparation = $item["preparation_level"];
                    $this->type = $item["type"];
                    $this->vk = $item["vk"];
                    $this->tg = $item["tg"];
                    $this->program = new Program($conn, 0);

                    $this->auth = $auth; // Установка статуса аутентификации

                    switch ($this->get_status()){ // get sportsmen associated with the coach or doctor
                        case "coach":
                            $sql3 = "SELECT user FROM user_to_coach WHERE coach=$this->id";
                            if ($result = $conn->query($sql3)){
                                foreach ($result as $item){
                                    $this->sportsmen[] = $item["user"];
                                }
                                $result->free();
                            }else{
                                echo $conn->error;
                            }
                            break;
                        case "doctor":
                            $sql4 = "SELECT user FROM user_to_doctor WHERE doctor=$this->id";

                            if ($result = $conn->query($sql4)){
                                foreach ($result as $item){
                                    $this->sportsmen[] = $item["user"];
                                }
                                $result->free();
                            }else{
                                echo $conn->error;
                            }
                            break;
                    }
                } else {
                    echo "Пользователь не найден."; // Пользователь не найден
                }
                $select_result->free(); // Освобождение результата
            } else {
                echo $conn->error; // Печать сообщения об ошибке
            }
        }
    }

    function get_auth(): bool
    {
        return $this->auth;
    }
    function get_id(): int
    {
        return $this->id;
    }
    function get_status(): string
    {
        return $this->status;
    }

    public function get_avatar($conn){ // function to get user avatar
        $select_sql = "SELECT file FROM avatars WHERE id=$this->avatar";
        if ($result_sql = $conn->query($select_sql)){  // Execute the SQL query to fetch the avatar file path
            if ($item = $result_sql->fetch_assoc()){
                $image = $item['file']; // Get the file path from the database
            } else {
                echo $conn->error;
                return "";
            }
        }else{
            echo $conn->error;
            return "";
        }

        return $image; // Return the file path to the avatar
    }

    public function get_news($conn){ // function to get news
        // Construct the initial SQL query
        $sql = "SELECT news.message, news.date, news.personal, avatars.file, users.name, users.surname, users.login FROM ((news INNER JOIN users ON news.user=users.id) INNER JOIN avatars ON users.avatar=avatars.id) WHERE (user=$this->id";
        $this->set_subscriptions($conn, true); // If not, retrieve the subscriptions

        if (count($this->subscriptions) != 0){ // If the user has subscriptions
            $sql .= " OR ";
            foreach ($this->subscriptions as $subscription){ // Append each subscription to the SQL query
                $sql .= "(user=$subscription AND personal=0) OR ";
            }
            $sql = substr($sql, 0, -4); // Remove the extra " OR " at the end of the query
        }
        $sql .= ") ORDER BY news.date DESC"; // Complete the SQL query
        if ($result = $conn->query($sql)){ // Execute the constructed query
            return $result;
        }else{
            echo $conn->error;
            return array();
        }
    }

    public function get_workout_history($conn)
    {
        $sql = "SELECT * FROM workout_history WHERE user=$this->id"; // Construct the SQL query
        if ($result = $conn->query($sql)){ // Execute the SQL query
            if ($result->num_rows == 0){
                return array();
            } else {
                return $result;
            }
        } else {
            echo "<p>".$conn->error."</p>";
            return array();
        }
    }

    public function get_program_amount($conn): int
    {
        $sql = "SELECT program FROM program_to_user WHERE user={$this->get_id()}";
        if ($result = $conn->query($sql)){
            return $result->num_rows; // return the count of programs
        }else{
            echo $conn->error;
        }
        return 0;
    }

    function get_phys_updates($conn): array
    {
        $sql = "SELECT height, weight, date FROM phys_updates WHERE user=$this->id ORDER BY date DESC";
        if ($result = $conn->query($sql)){
            $res = array();
            foreach ($result as $item){
                $res[(string)strtotime($item["date"])] = array("height" => $item["height"], "weight" => $item["weight"]);
            }
            return $res;
        }else{
            echo $conn->error;
            return array();
        }
    }

    public function get_current_phys_data($conn): array
    { // get current user's physic data
        $id = $this->get_id();
        $sql = "SELECT height, weight FROM phys_updates WHERE user=$id ORDER BY date DESC LIMIT 1"; // SQL query to select height, weight, and date from the 'phys_updates' table for a specific user ID
        if ($result = $conn->query($sql)){ // Executing the SQL query and checking for success
            foreach ($result as $item){
                return array("height" => $item["height"], "weight" => $item["weight"]); // return physical updates in an associative array indexed by date, containing height and weight information
            }
            return array("height" => 0, "weight" => 0); // return phycis updates with zeros
        }else{
            echo $conn->error; // Displaying an error message if the query fails
            return array();
        }
    }

    public function get_control_workouts($conn, $is_done, $user_id=NULL): array
    { // get control workouts list
        $sql = NULL;
        switch ($this->get_status()) {
            case "coach":
                $sql = "SELECT id FROM control_workouts WHERE user=$user_id ";
                break;
            case "user":
                $sql = "SELECT id FROM control_workouts WHERE user=$this->id ";
                break;
        }
        if ($is_done)
            $sql .= "AND is_done=1 ORDER BY date DESC";
        else
            $sql .= "AND is_done=0 ORDER BY date";
        $return_val = array(); // Initialize an empty array to store the resulting workouts
        if ($result = $conn->query($sql)){ // Execute the SQL query
            foreach ($result as $item){
                $return_val[] = new Control_Workout($conn, $item["id"]); //  For each resulting item, create a Control_Workout object and add it to the return array
            }
        }else{
            echo $conn->error;// Output any SQL errors
        }
        return $return_val; // Return the array of Control_Workout objects
    }

    function check_request($conn, $id): bool
    {
        $user = $this->get_id(); // Get the ID of the current user
        $sql = "SELECT user FROM requests WHERE user=$user AND receiver=$id"; // SQL query to check for a request between the current user and the provided $id
        if ($result = $conn->query($sql)){ // Check if the query executes successfully
            // Check if there are any rows returned from the query
            // If rows exist, a request is found, return true
            return $result->num_rows > 0;
        }else{ // If there's an error with the query, output the error message
            echo $conn->error;
            return false; // Return false indicating an error or no request found
        }
    }
    function get_requests($conn) {
        $user = $this->get_id(); // Get the ID of the current user
        $sql = "SELECT receiver, user FROM requests WHERE receiver=$user"; // SQL query to check for a request between the current user and the provided $id
        if ($result = $conn->query($sql)){ // Check if the query executes successfully
            // Check if there are any rows returned from the query
            // If rows exist, a request is found, return true
            return $result;
        }else{ // If there's an error with the query, output the error message
            echo $conn->error;
            return NULL; // Return false indicating an error or no request found
        }
    }

    public function reg($conn, $login, $status, $password, $password2, $name, $surname): array // check registration function
    {
        # ---------- collecting errors ---------------
        $error_array = array(
            "reg_fill_all_input_fields" => false,
            "reg_login_is_used" => false,
            "reg_login_too_short" => false,
            "reg_passwords_are_not_the_same" => false,
            "reg_password_not_fit" => false,
            "reg_password_too_short" => false,
            "reg_conn_error" => false,
            "reg_success" => false,
            "too_long_string" => false,
            "adding_stats" => false,
            "log_conn_error" => false,
            "log_fill_all_input_fields" => false,
            "log_incorrect_login_or_password" => false
        );
        # --------- deleting spaces --------------

        $login = trim($login);
        $password = trim($password);
        $password2 = trim($password2);
        $name = trim($name);
        $surname = trim($surname);

        # --------- checking data --------------

        if ($login == '' || $password == '' || $name == '' || $surname == '' || $status == NULL) { # checking blank fields
            $error_array['reg_fill_all_input_fields'] = true;
            return $error_array;
        }
        if (mb_strlen($login) < 3){
            $error_array['reg_login_too_short'] = true;
            return $error_array;
        }
        if (mb_strlen($password) < 8){
            $error_array['reg_password_too_short'] = true;
            return $error_array;
        }
        if (preg_match('/^[^\p{L}]+$/u', $password)){
            $error_array['reg_password_not_fit'] = true;
            return $error_array;
        }
        if ($password != $password2) { # checking password equality
            $error_array['reg_passwords_are_not_the_same'] = true;
            return $error_array;
        }

        if (strlen($login) > 32 || strlen($surname) > 32 || strlen($name) > 32) { # checking length
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

        $reg_sql = "INSERT INTO users(login, status, password, name, surname) VALUES('$login', '$status', MD5('$password'), '$name', '$surname')";
        if (!($conn->query($reg_sql))) { # querying
            $error_array['reg_conn_error'] = true;
//            header("Location: reg_log.php?reg=1");
            return $error_array;
        }

        insert_news($conn, "Пользователь $login зарегистрировался на платформе.", mysqli_insert_id($conn), false);

        return $error_array; // return array of error messages
    }

    public function authenticate($conn, $login, $password): array
    {
        $error_array = array( // Array to manage different error states during authentication
            "reg_fill_all_input_fields" => false,
            "reg_login_is_used" => false,
            "reg_login_too_short" => false,
            "reg_passwords_are_not_the_same" => false,
            "reg_password_not_fit" => false,
            "reg_password_too_short" => false,
            "reg_conn_error" => false,
            "reg_success" => false,
            "too_long_string" => false,
            "adding_stats" => false,
            "log_conn_error" => false,
            "log_fill_all_input_fields" => false,
            "log_incorrect_login_or_password" => false
        );

        // Trim login and password
        $login = trim($login);
        $password = trim($password);

        if ($login == "" || $password == ""){ // Check if login or password is empty
            $error_array['log_fill_all_input_fields'] = true;
            return $error_array;
        }
        $log_sql = "SELECT id, password FROM users WHERE login='$login' LIMIT 1"; // Query to select user ID and password from the database based on login
        if (!($log_result = $conn->query($log_sql))){
            $error_array['log_conn_error'] = true;
            return $error_array; // return array of error messages
        }
        if ($log_result->num_rows == 0){
            $error_array['log_incorrect_login_or_password'] = true;
            return $error_array; // return array of error messages
        }

        if ($item = $log_result->fetch_assoc()){
            if ($item['password'] != md5($password)){
                $error_array['log_incorrect_login_or_password'] = true;
                return $error_array; // return array of error messages
            }else{
                $_SESSION["user"] = $item["id"];  // If password matches, set the user ID in the session
            }
        }
        header('Location: src/Pages/profile.php');
        exit;
    }

    public function update_avatar($conn, $data){ // function to update user avatar
        if ($this->avatar == 1){ // If the user doesn't have an avatar (ID = 1 is a placeholder)
            $sql = "INSERT INTO avatars (file) VALUES ('$data')";
        }else{ // If the user already has an avatar, update the existing avatar
            $sql = "UPDATE avatars SET file='$data' WHERE id=$this->avatar";
        }
        if ($conn->query($sql)){ // Execute the SQL query to update or insert the avatar
            if ($this->avatar == 1){ // If a new avatar was inserted, update the user's avatar ID
                $new_avatar_id = mysqli_insert_id($conn);
                $update_sql = "UPDATE users SET avatar=$new_avatar_id WHERE id=$this->id";
                if ($conn->query($update_sql)){ // Update the user's avatar reference in the 'users' table
                    header("Refresh: 0"); // Refresh the page after successful update
                }else{
                    echo $conn->error; // Output an error if the update fails
                }
            }else{
                header("Refresh: 0"); // // Refresh the page after successful update
            }
        }else{
            echo $conn->error; // Output an error if the query fails
        }
    }

    function set_subscriptions($conn, $check=false){ // set subscriptions function
        if (!$check or count($this->subscriptions) == 0){
            $sql = "SELECT user FROM subs WHERE subscriber=$this->id"; // Query to retrieve subscriptions of the current user
            $this->subscriptions = array(); // Reset the subscriptions array
            if ($result = $conn->query($sql)){ // Execute the SQL query
                foreach ($result as $user){ // Loop through the query result to fetch subscriptions
                    $this->subscriptions[] = $user['user']; // Push each subscription user into the subscriptions array
                }
            }else{
                echo $conn->query; // Output an error message in case of query failure
            }
        }
    }
    function set_subscribers($conn, $check=false){ // the same as  subscriptions function, but with subscribers
        if (!$check or count($this->subscribers) == 0) {
            $sql = "SELECT subscriber FROM subs WHERE user=$this->id";
            $this->subscribers = array();
            if ($result = $conn->query($sql)) {
                foreach ($result as $user) {
                    $this->subscribers[] = $user['subscriber'];
                }
            } else {
                echo $conn->query;
            }
        }
    }

    function set_staff($conn, $check=false): int
    { // add staff (coach and doctor)
        if (!$check or $this->doctor == NULL or $this->coach == NULL) {
            $id = $this->id; // Получить ID текущего пользователя
            $sql = "SELECT (SELECT coach FROM user_to_coach WHERE user = $id) AS selected_coach, 
                       (SELECT doctor FROM user_to_doctor WHERE user = $id) AS selected_doctor"; // SQL запрос для выбора тренера и доктора, назначенных пользователю

            if ($result = $conn->query($sql)) {  // Выполнение SQL запроса
                if ($item = $result->fetch_assoc()) {  // Получение результата в виде ассоциативного массива
                    if ($item["selected_coach"] != null) { // Если тренер назначен, создать объект User для тренера и назначить его свойству 'coach'
                        $this->coach = new User($conn, $item["selected_coach"]);
                    }
                    if ($item["selected_doctor"] != null) { // Если доктор назначен, создать объект User для доктора и назначить его свойству 'doctor'
                        $this->doctor = new User($conn, $item["selected_doctor"]);
                    }
                    return 1; // Возврат 1 для указания на успешное назначение персонала
                }
                $result->free(); // Освобождение результата
            } else {
                echo $conn->error; // Вывод сообщения об ошибке, если запрос не выполнен
            }
        }
        return 0; // Возврат 0 в случае неудачи
    }

    function set_program($conn){ // function to set program
        $select_sql = "SELECT * FROM program_to_user WHERE user=$this->id AND date_start + INTERVAL weeks WEEK >= NOW() LIMIT 1"; // fix it
        if ($result_sql = $conn->query($select_sql)){
            if ($result_sql->num_rows == 0){
                $this->program = new Program($conn, 0); // If no active program is found, set the program property to a default program (ID 0)
                return false;
            }
            foreach ($result_sql as $item){ // If an active program is found, set the program property
                $this->program = new Program($conn, $item['program'], $item['date_start'], $item['weeks']);
            }
            return true; // if success return true
        }else{
            echo $conn->error;
            return false; // if error return false
        }
    }

    public function change_featured($conn, $ex_id)
    {
        // Step 1: Check if the row exists
        $sql_check = "SELECT COUNT(*) as count FROM user_featured_exercises WHERE user_id = {$this->get_id()} AND exercise_id=$ex_id";
        if ($result_check = $conn->query($sql_check)){
            if ($row = $result_check->fetch_assoc()){
                if ($row['count'] > 0) {
                    // Step 2: If row exists, delete it
                    $sql_delete = "DELETE FROM user_featured_exercises WHERE user_id = {$this->get_id()} AND exercise_id = $ex_id";
                    if (!$conn->query($sql_delete)) {
                        echo "Error deleting row: " . $conn->error;
                    }
                } else {
                    // Step 3: If row does not exist, insert it
                    $sql_insert = "INSERT INTO user_featured_exercises (user_id, exercise_id) VALUES ({$this->get_id()}, $ex_id)";
                    if (!$conn->query($sql_insert)) {
                        echo "Error inserting row: " . $conn->error;
                    }
                }
            } else {
                echo $conn->error;
            }
        } else {
            echo $conn->error;
        }
    }

    function get_featured_exercises($conn)
    {
        $sql_check = "SELECT exercise_id FROM user_featured_exercises WHERE user_id = {$this->get_id()}";
        if ($result_check = $conn->query($sql_check)){
            $exercises = array();
            foreach ($result_check as $item){
                $exercises[] = (int)$item['exercise_id'];
            }
            return $exercises;
        }
        return array();
    }

    function get_my_exercises($conn)
    {
        $sql_check = "SELECT exercise_id FROM user_added_exercises WHERE user_id = {$this->get_id()}";
        if ($result_check = $conn->query($sql_check)){
            $exercises = array();
            foreach ($result_check as $item){
                $exercises[] = (int)$item['exercise_id'];
            }
            return $exercises;
        }
        return array();
    }

    function add_exercise($conn, $ex_id)
    {
        $sql = "INSERT INTO user_added_exercises (user_id, exercise_id) VALUES ({$this->get_id()}, $ex_id)";
        if (!$conn->query($sql)) {
            echo $conn->error;
        }
    }

    function delete_exercise($conn, $ex_id)
    {
        $sql = "DELETE FROM user_added_exercises WHERE user_id={$this->get_id()} AND exercise_id=$ex_id";
        if (!$conn->query($sql)) {
            echo $conn->error;
        }
    }

    function update_phys($conn, $height, $weight)
    {
        // Prepare the SQL statement with placeholders
        $sql = "INSERT INTO phys_updates (user, height, weight, date) VALUES ($this->id, $height, $weight, NOW())";
        if (!$conn->query($sql)){
            echo $conn->error; // print errors
        }
    }

    public function get_coach_data($conn, $user_id=NULL){ // get coach data
        $goals_sql = NULL;
        $advice_sql = NULL;
        $competitions_sql = NULL;

        switch ($this->get_status()) { // Construct SQL query based on the user's status
            case "coach":  // For a doctor, fetch data of a specific user they are assigned to
                $goals_sql = "SELECT id, name, done, user FROM goals WHERE user = $user_id";
                $advice_sql = "SELECT id, name, link, user FROM coach_advice WHERE user = $user_id";
                $competitions_sql = "SELECT id, name, link, user, date FROM competitions WHERE user = $user_id";
                break;
            case "user": // For a user, fetch their data related to their assigned doctor
                $goals_sql = "SELECT id, name, done, user FROM goals WHERE user = $this->id";
                $advice_sql = "SELECT id, name, link, user FROM coach_advice WHERE user = $this->id";
                $competitions_sql = "SELECT id, name, link, date, user FROM competitions WHERE user = $this->id";
                break;
        }

        $response = array(
            "competitions" => NULL,
            "goals" => NULL,
            "info" => NULL,
        );

        // Fetch medicines data
        if ($goals_sql) {
            if ($result = $conn->query($goals_sql)){
                $response['goals'] = $result;
            }
        }

        // Fetch recommendations data
        if ($advice_sql) {
            if ($result = $conn->query($advice_sql)){
                $response["info"] = $result;
            }
        }

        // Fetch treatment period data
        if ($competitions_sql) {
            if ($result = $conn->query($competitions_sql)){
                $response["competitions"] = $result;
            }
        }

        // Return the response as JSON
        return $response;
    }

    public function get_doctor_data($conn, $user_id = NULL) { // get data for doctor
        $medicines_sql = NULL;
        $recommendations_sql = NULL;
        $treatment_period_sql = NULL;

        switch ($this->get_status()) { // Construct SQL query based on the user's status
            case "doctor":  // For a doctor, fetch data of a specific user they are assigned to
                $medicines_sql = "SELECT id, name, caption FROM medicines WHERE user = $user_id";
                $recommendations_sql = "SELECT recommendation FROM recommendations WHERE user = $user_id";
                $treatment_period_sql = "SELECT intake_start, intake_end FROM treatment_period WHERE user = $user_id";
                break;
            case "user": // For a user, fetch their data related to their assigned doctor
                $medicines_sql = "SELECT id, name, caption FROM medicines WHERE user = $this->id";
                $recommendations_sql = "SELECT recommendation FROM recommendations WHERE user = $this->id";
                $treatment_period_sql = "SELECT intake_start, intake_end FROM treatment_period WHERE user = $this->id";
                break;
        }

        $response = array(
            "intake_start" => NULL,
            "intake_end" => NULL,
            "medicines" => NULL
        );

        // Fetch medicines data
        if ($medicines_sql) {
            if ($result = $conn->query($medicines_sql)){
                $response['medicines'] = $result;
            }
        }

        // Fetch recommendations data
        if ($recommendations_sql) {
            if ($result = $conn->query($recommendations_sql)){
                if ($item = $result->fetch_assoc()){
                    $response['recommendations'] = $item["recommendation"];
                }
            }
        }

        // Fetch treatment period data
        if ($treatment_period_sql) {
            if ($result = $conn->query($treatment_period_sql)){
                if ($item = $result->fetch_assoc()) {
                    $response["intake_start"] = $item['intake_start'];
                    $response["intake_end"] = $item['intake_end'];
                }
            }
        }

        // Return the response as JSON
        return $response;
    }

    public function get_closest_workout($conn){ // fet user's closest workout
        $this->program->set_additional_data($conn, $this->get_id()); // Fetch additional program data for the user
        for ($i = 0; $i < 7; $i++) {  // Iterate over the next 7 days
            $workouts = $this->program->get_workouts();
            if ($workouts[(date("N") + $i - 1) % 7]->get_id() != 0 && ((time() + $i * 86400) <
                (strtotime($this->program->get_date_start()) * $this->program->get_weeks() * 604800)))
                return time() + $i * 86400; // Return the timestamp of the closest upcoming workout
        }
        return NULL; // Return NULL if no upcoming workout is found within the next 7 days
    }
}