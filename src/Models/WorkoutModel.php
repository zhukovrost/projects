<?php

class WorkoutModel
{
    protected $id;
    protected $creator;
    public $name;
    public $loops;
    public $weekday;
    public $holiday = true;
    protected $exercises=array();

    public function __construct($conn, $workout_id, $weekday){ // constrictor of Workout class
        if ($workout_id == 0){ // Check if the workout ID is 0, indicating a holiday
            $this->holiday = true;
        }else{ // Fetch workout details from the database based on the provided workout ID
            $this->holiday = false;
            $select_sql = "SELECT * FROM workouts WHERE id=$workout_id";
            if ($select_result = $conn->query($select_sql)){ // Execute the SQL query
                $this->weekday = $weekday;
                if ($item = $select_result->fetch_assoc()) { // Iterate through the result set
                    // Assign fetched values to object properties
                    $this->id = $item['id'];
                    $this->loops = $item['loops'];
                    $this->name = $item['name'];
                    $this->creator = $item["creator"];
                    $this->set_exercises($conn);
                }
            }else{
                echo $conn->error; // Output any database errors
            }
            $select_result->free(); // Free the result set after processing
        }
    }

    public function set_exercises($conn)
    {
        $sql = "SELECT exercise_id, sets, reps FROM workout_exercises WHERE workout_id=$this->id";
        if ($select_result = $conn->query($sql)){
            foreach ($select_result as $user) {
                $this->exercises[] = new User_Exercise($conn, $user['exercise_id'], $user['reps'], $user['sets']);
            }
        }else{
            echo $conn->error;
        }
    }

    public function get_exercises() {
        return $this->exercises;
    }

    public function get_id() {
        return $this->id;
    }

    public function is_done($conn, $user_id, $day): bool{ // check if workout is done or not
        $next_day = $day + 86400; // Calculate the next day timestamp

        // Преобразуем временные метки в формат datetime
        $sql = "SELECT id FROM workout_history WHERE user=$user_id AND UNIX_TIMESTAMP(date_completed) >= $day AND UNIX_TIMESTAMP(date_completed) < $next_day"; // SQL query to check if the workout is completed
        if ($result = $conn->query($sql)){ // Execute the query
            if ($result->num_rows > 0){
                return true; // return that user has completed the workout for the specified day
            }
        }else{
            echo $conn->error;
        }
        return false; // return that user has not completed the workout for the specified day
    }

    function is_holiday() {
        return $this->holiday;
    }
}

class Control_Workout_Model extends WorkoutModel {
    public $is_done = false;
    public $date;
    public $user;

    public function set_exercises($conn) {
        $sql = "SELECT exercise_id, reps FROM workout_exercises WHERE workout_id=$this->id";
        if ($select_result = $conn->query($sql)){
            foreach ($select_result as $user) {
                if ($this->is_done){
                    $this->exercises[] = new User_Exercise($conn, $user['exercise_id'], $user['reps']);
                } else {
                    $this->exercises[] = new User_Exercise($conn, $user['exercise_id'], 0);
                }
            }
        }else{
            echo $conn->error;
        }
    }

    public function __construct($conn, $workout_id){ // constructor for control workout
        if ($workout_id == 0){ // Check if the workout_id is 0, indicating a holiday
            $this->holiday = true;
        }else{ // Fetch workout details from the database using the provided workout_id
            $select_sql = "SELECT cw.id, cw.user, cw.is_done, cw.date, w.creator, w.name, w.loops 
    FROM control_workouts cw JOIN new_sport.workouts w ON cw.id = w.id WHERE cw.id = $workout_id";
            if ($select_result = $conn->query($select_sql)){ // Attempt to execute the SQL query
                if ($item = $select_result->fetch_assoc()) { // Assign values to object properties from the fetched data
                    $this->id = $item['id'];
                    $this->name = $item['name'];
                    $this->creator = $item["creator"];
                    $this->is_done = (bool)$item["is_done"];
                    $this->date = $item["date"];
                    $this->loops = $item["loops"];
                    $this->user = $item["user"];
                    $this->weekday = date("N", strtotime($this->date));
                    $this->holiday = false;
                    $this->set_exercises($conn);
                }
            }else{
                echo $conn->error; // Display any database errors encountered during the query
            }
            $select_result->free(); // Free the result set
        }
    }

    function is_holiday(){
        return !$this->is_done;
    }
    function get_date(){
        return $this->date;
    }
}
