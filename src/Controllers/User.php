<?php
require_once __DIR__ . "/../Models/UserModel.php";

class User {
    private $model;

    public function __construct($conn, $id=-1, $auth=false){ // contructor for class
        $this->model = new UserModel($conn, $id, $auth);
    }

    public function update($conn) {
        $this->model = new UserModel($conn, $this->get_id(), $this->get_auth());
    }

    public function get_auth(): bool { // get auth function
        return $this->model->get_auth(); // Returns the value of the 'auth' property, indicating if the user is authenticated or logged in.
    }
    public function get_id(): int { // get id function
        return $this->model->get_id(); // Returns the 'id' property of the user object, representing the user's unique identification.
    }
    public function get_status(): string {  // get status function
        return $this->model->get_status(); // Returns the status of the user
    }
    public function is_admin(): bool {  // get id user is admin function
        return $this->get_status() == "admin"; // returns user is admin or not
    }
    public function get_requests($conn) { // get user's requests function
        return $this->model->get_requests($conn); // returns requests
    }
    public function get_sportsmen(): array
    { // get sportsmen function
        return $this->model->sportsmen; // returns sportsmen list
    }
    public function get_desc() {
        return $this->model->description;
    }
    public function get_surname() {
        return $this->model->surname;
    }
    public function get_name() {
        return $this->model->name;
    }


    public function get_subscriptions() {
        return $this->model->subscriptions;
    }
    public function get_subscribers() {
        return $this->model->subscribers;
    }

    public function get_coach() {
        return $this->model->coach;
    }
    public function get_doctor() {
        return $this->model->doctor;
    }

    public function get_tg() {
        return $this->model->tg;
    }
    public function get_vk() {
        return $this->model->vk;
    }

    public function get_avatar($conn) : string
    {
        return $this->model->get_avatar($conn); // returns avatar
    }
    public function get_sportsmen_advanced($conn): array
    {
        $return_val = array(); // Initialize an empty array to store advanced sportsmen data

        // Iterate through the list of sportsmen using the get_sportsmen() method
        foreach ($this->get_sportsmen() as $sportsman)
            $return_val[] = new UserModel($conn, $sportsman); // Create a new User object for each sportsman using the provided database connection ($conn)

        return $return_val; // Return an array containing advanced User objects for each sportsman
    }

    public function has_program($conn): int
    { // check if user has program
        $sql = "SELECT program FROM program_to_user WHERE user=" . $this->get_id() . " AND (date_start + (604800 * weeks)) > " . time(); // SQL query to check if the user has an active program based on the current time.
        if ($result = $conn->query($sql)){ // Execute the query.
            if ($result->num_rows == 0){ // Check if there are any rows in the result.
                return 0; // If there are no rows, the user does not have an active program.
            }
            if ($item = $result->fetch_assoc()) { // Iterate over the result set
                return (int)$item["program"];  // Return the ID of the active program for the user.
            }
        }
        return 0;
    }

    public function check_the_login($header=true, $way="../"): bool
    { // check user login
        if (!$this->get_auth()){ // Check if the user is not authenticated
            if ($header){ // If a header is required, redirect the user to the login page
                header('Location: '.$way.'log.php?please_log=1');
            }else{
                return false;  // If no header is required, return false to indicate authentication failure
            }
        }
        if (!$header){
            return true; // If no header is required and user is authenticated, return true to indicate successful authentication
        }
        return false;
    }

    public function redirect_logged($way=''){ // redirect to profile of user
        if ($this->get_auth()){
            header("Location: ".$way."src/Pages/profile.php"); // redirect to profile page
        }
    }
    public function authenticate($conn, $login, $password): array
    {
        return $this->model->authenticate($conn, $login, $password);
    }
    public function reg($conn, $login, $status, $password, $password2, $name, $surname) : array // check registration function
    {
        return $this->model->reg($conn, $login, $status, $password, $password2, $name, $surname);
    }

    public function update_avatar($conn, $data){ // function to update user avatar
        $this->model->update_avatar($conn, $data);
    }

    public function change_featured($conn, $exercise_id){ // function to change featured exercise
        $this->model->change_featured($conn, $exercise_id);
    }
    public function add_exercise($conn, $exercise_id){ // function to add exercise
        $this->model->add_exercise($conn, $exercise_id);
    }
    public function delete_exercise($conn, $exercise_id){
        $this->model->delete_exercise($conn, $exercise_id);
    }

    public function get_workout_history($conn)
    { // get workout history
        return $this->model->get_workout_history($conn);
    }

    function get_program_amount($conn): int { // get amount of program
        return $this->model->get_program_amount($conn);
    }

    public function get_news($conn)
    { // function to get news
        return $this->model->get_news($conn);
    }

    function get_featured_exercises($conn): array    {
        return $this->model->get_featured_exercises($conn);
    }

    function get_my_exercises($conn): array    {
        return $this->model->get_my_exercises($conn);
    }
    function get_full_name(): string
    {
        return $this->model->name . " " . $this->model->surname;
    }

    public function get_my_news($conn){ // get user's own news
        // Construct the SQL query to retrieve user's own news
        $sql = "SELECT news.message, news.date, news.personal, avatars.file, users.name, users.surname, users.login 
                FROM ((news INNER JOIN users ON news.user=users.id) INNER JOIN avatars ON users.avatar=avatars.id) 
                WHERE user={$this->get_id()} ORDER BY date DESC";
        if ($result = $conn->query($sql)){// Execute the SQL query
            return $result; // return result object
        }else{
            echo $conn->error;
            return false; // In case of an error, display the error message and return false
        }
    }

    public function print_workout_history($conn){ // print history of workout ?>
    <!-- print section -->
        <section class="last-trainings">
            <h1 class="last-trainings__title">Последние тренировки</h1>
            <div class="last-trainings__content">
                <?php
                foreach ($this->get_workout_history($conn) as $item){
                    $date = new DateTime($item['date_completed']);
                    $workout = new Workout($conn, $item['workout'], $date->format("N"));
                    $workout->set_muscles();
                    list($hours, $minutes, $seconds) = explode(':', $item['time_spent']);
                    $timeSpentInMinutes = $hours * 60 + $minutes + $seconds / 60;
                    $replacements = array(
                        "{{ minutes }}" => round($timeSpentInMinutes, 0),
                        "{{ muscle_group_amount }}" => $workout->get_groups_amount(),
                        "{{ exercise_amount }}" => count($workout->get_exercises()),
                        "{{ date }}" => $item["date_completed"]
                    );
                    echo render($replacements, "../../templates/workout_history_item.html"); // render workout_history_item with replacements
                }
                ?>
            </div>
        </section>
    <?php }

    public function print_status(){ // print status of user(return status)
        switch ($this->get_status()){
            case "admin":
                echo "Админ";
                break;
            case "user":
                echo "Спортсмен";
                break;
            case "coach":
                echo "Тренер";
                break;
            case "doctor":
                echo "Доктор";
                break;
        }
    }

    public function print_prep() // level of physic(return physic parametr)
    {
        if ($this->get_status() == "user" || $this->get_status() == "admin") {
            switch ($this->model->preparation) {
                case 0:
                    echo "Не указан";
                    break;
                case 1:
                    echo "Низкий";
                    break;
                case 2:
                    echo "Средний";
                    break;
                case 3:
                    echo "Высокий";
                    break;
            }
        }
    }
    public function print_type(){ // print user type(return types of user's paramtres)
        if ($this->model->type == 0){
            echo "Не указан";
            return;
        }
        if ($this->get_status() == "user" || $this->get_status() == "admin"){
            switch ($this->model->type){
                case 1:
                    echo "Любитель";
                    return;
                case 2:
                    echo "Профессионал";
                    return;
            }
        }
        if ($this->get_status() == "coach"){
            switch ($this->model->type){
                case 2:
                    echo "Тренер команды";
                    return;
                case 1:
                    echo "Личный тренер";
                    return;
            }
        }
        if ($this->get_status() == "doctor"){
            switch ($this->model->type){
                case 2:
                    echo "Врач команды";
                    return;
                case 1:
                    echo "Личный врач";
                    return;
            }
        }
    }

    public function set_subscriptions($conn, $check=false) {
        $this->model->set_subscriptions($conn, $check);
    }
    public function set_subscribers($conn, $check=false) {
        $this->model->set_subscribers($conn, $check);
    }
    public function set_staff($conn, $check=false) {
        $this->model->set_staff($conn, $check);
    }

    public function get_program() {
        return $this->model->program;
    }
    public function set_program($conn): bool {
        return $this->model->set_program($conn);
    }

    public function check_request($conn, $id): bool
    { // checks user's request
        return $this->model->check_request($conn, $id);
    }

    public function update_phys($conn, $height, $weight){ // update physic data
        $this->model->update_phys($conn, $height, $weight);
    }

    public function set_desc($new_desc) {
        $new_desc = trim($new_desc);
        if ($new_desc == "" || $new_desc == NULL){
            $new_desc = "Нет описания";
        }
        $this->model->description = $new_desc;
    }

    public function get_phys_updates($conn): array
    { // get physic data updates
        return $this->model->get_phys_updates($conn);
    }

    public function get_current_phys_data($conn): array { // get current user's physic data
        return $this->model->get_current_phys_data($conn);
    }

    public function get_doctor_data($conn, $user_id=NULL){ // get data for doctor
        return $this->model->get_doctor_data($conn, $user_id);
    }

    public function get_closest_workout($conn){ // fet user's closest workout
        $this->model->get_closest_workout($conn);
    }

    public function get_coach_data($conn, $user_id=NULL){ // get coach data
        return $this->model->get_coach_data($conn, $user_id);
    }

    public function get_control_workouts($conn, $is_done, $user_id=NULL): array
    { // get control workouts list
        return $this->model->get_control_workouts($conn, $is_done, $user_id);
    }

    public function get_workout_by_day($weekday) {
        return $this->model->program->get_workout_by_day($weekday);
    }
    public function get_workouts() {
        return $this->model->program->get_workouts();
    }

    public function set_additional_program_data($conn) {
        $this->model->program->set_additional_data($conn, $this->get_id());
    }
}
