<?php
require_once __DIR__ . "/../Models/ProgramModel.php";


class Program {
    private $model;

    public function __construct($conn, $id, $date_start=0, $weeks=0){ // Constructor to initialize the Program
        $this->model = new ProgramModel($conn, $id, $date_start, $weeks);
    }

    public function get_id(){ // Method to retrieve the program ID
        return $this->model->get_id(); // ruturn program id
    }

    public function count_workouts(){ // Method to count the total number of workouts in the program
        $cnt = 0;
        foreach ($this->model->workouts as $workout){
            if (!$workout->is_holiday()){
                $cnt++;
            }
        }
        return $cnt * $this->model->weeks; // return number of workouts in the program
    }

    public function count_exercises(){ // Method to count the total number of exercises in the program
        $cnt = 0;
        foreach ($this->model->workouts as $workout){
            $cnt += count($workout->get_exercises());
        }
        return $cnt * $this->model->weeks; // return number of exercises in the program
    }

    public function print_program_info(){ // Method to print program information?>
        <section class="cover" navigation="true">
            <?php
            foreach ($this->model->workouts as $workout){ // print workout info items
                $workout->print_workout_info(true);
            }
            ?>
        </section>
    <?php }

    public function set_additional_data($conn, $user){ // Method to set additional data for the program (start date, weeks)
        $this->model->set_additional_data($conn, $user);
    }

    public function get_workout_by_day($day) {
        return $this->model->workouts[$day % 7];
    }
    public function get_workouts()
    {
        return $this->model->workouts;
    }
    public function get_weeks() {
        return $this->model->weeks;
    }
    public function get_date_start() {
        return $this->model->date_start;
    }
}