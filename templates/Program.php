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

    public function count_workouts(){
        $cnt = 0;
        foreach ($this->program as $id){
            if ($id != 0){
                $cnt++;
            }
        }
        return $cnt * $this->reps;
    }

    public function count_exercises(){
        $cnt = 0;
        foreach ($this->workouts as $workout){
            $cnt += count($workout->exercises);
        }
        return $cnt * $this->reps;
    }
}