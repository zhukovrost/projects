<?php

class Program {
    private $id;
    public $name;
    public $program; # []
    public $rating;
    public $workouts=array();
    public $muscles=array();
    public $reps;

    public function __construct($conn, $id){
        $select_sql = "SELECT * FROM programs WHERE id=$id";
        if ($select_result = $conn->query($select_sql)){
            foreach ($select_result as $item){
                $this->id = $item['id'];
                $this->name = $item['name'];
                $this->program = json_decode($item['program']);
                $this->rating = $item['rating'];
                $this->reps = $item['reps'];
            }
        }else{
            echo $conn->error;
        }
        $select_result->free();
    }

    public function set_workouts($conn){
        for ($i = 0; $i < 7; $i++){
            array_push($this->workouts, new Workout($conn, $this->program[$i], $i));
        }
    }
}