<?php
require_once __DIR__ . "/../Controllers/Workout.php";

class ProgramModel
{
    private $id;
    public $name;
    public $workouts=array();
    public $weeks=1;
    public $date_start;


    public function __construct($conn, $id, $date_start, $weeks){ // Constructor to initialize the Program
        if ($id != 0){
            $select_sql = "SELECT * FROM programs WHERE id=$id";
            $this->weeks = $weeks;
            $this->date_start = $date_start;
            if ($select_result = $conn->query($select_sql)){
                foreach ($select_result as $item){
                    $this->id = $item['id'];
                    $this->name = $item['name'];
                    for ($i = 0; $i < 7; $i++) {
                        $this->workouts[] = new Workout($conn, 0, $i);
                    }
                    $this->set_workouts($conn);
                }
            }else{
                echo $conn->error; // Output database error if query fails
            }
            $select_result->free(); // free up the result set
        }else{
            $this->id = $id;
        }
    }

    public function get_id() {
        return $this->id;
    }

    public function set_workouts($conn){ // Method to set workouts associated with the program
        $sql = "SELECT workout_id, week_day FROM program_workouts WHERE program_id=$this->id";
        if ($select_result = $conn->query($sql)){
            foreach ($select_result as $item){
                $this->workouts[$item["week_day"]] = new Workout($conn, $item["workout_id"], $item["week_day"]);
            }
        }
    }

    public function set_additional_data($conn, $user){ // Method to set additional data for the program (start date, weeks)
        $sql = "SELECT date_start, weeks FROM program_to_user WHERE user=$user AND (date_start + 604800 * weeks) > ".time()." LIMIT 1"; // SQL query to fetch program start date and duration (in weeks) for a specific user
        if ($result = $conn->query($sql)){ // If the SQL query executes successfully
            foreach ($result as $times){ // Iterating through the fetched result
                $this->date_start = $times['date_start']; // Assigning the 'date_start' value from the result to the object property
                $this->weeks = $times["weeks"]; // Assigning the 'weeks' value from the result to the object property
            }
        }else{
            echo $conn->error; // Output database error if query fails
        }
    }
}