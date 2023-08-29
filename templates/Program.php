<?php

class Program {
    private $id;
    public $name;
    private $program;
    public $rating;
    public $exercises=array();
    public $muscles=array();

    public function __construct($conn, $id){
        $select_sql = "SELECT * FROM programs WHERE id=$id";
        if ($select_result = $conn->query($select_sql)){
            foreach ($select_result as $item){
                $this->id = $item['id'];
                $this->name = $item['name'];
                $this->program = (array)json_decode($item['program']);
                $this->rating = $item['rating'];
            }
        }else{
            echo $conn->error;
        }
        $select_result->free();
    }

    public function set_exercises($conn){
        foreach ($this->program as $key=>$value){
            $reps = $value[0];
            $approaches = $value[1];
            array_push($this->exercises, new User_Exercise($conn, $key, $reps, $approaches));
        }
    }

    public function set_muscles($conn){
        if (count($this->muscles) == 0){
            $this->set_exercises($conn);
        }

        foreach ($this->exercises as $exercise) {
            foreach ($exercise->muscles as $muscle){
                if (!in_array($muscle, $this->muscles)){
                    array_push($this->muscles, $muscle);
                }
            }
        }
    }
}