<?php

class Exercise {
    private $id;
    public $name;
    public $static;
    public $description="";
    public $muscles=[];
    private $image=0;
    public $rating;

    private function set_exercise_data($select_result){
        foreach ($select_result as $item){
            $this->id = $item['id'];
            $this->name = $item['name'];
            $this->description = $item['description'];
            $this->muscles = json_decode($item['muscles']);
            $this->image = $item['image'];
            $this->rating = $item['rating'];
            $this->static = $item['static'];
        }
    }

    public function __construct($conn, $id){
        $select_sql = "SELECT * FROM exercises WHERE id=$id";
        if ($select_result = $conn->query($select_sql)){
            $this->set_exercise_data($select_result);
        }else{
            echo $conn->error;
        }
        $select_result->free();
    }

    public function get_id(){
        return $this->id;
    }

    public function get_image($conn){
        $select_sql = "SELECT file FROM exercise_images WHERE id=$this->image";
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

    public function update_rating($conn){

    }
}

class User_Exercise extends Exercise {
    public $reps;
    public $approaches;

    public function __construct($conn, $id, $reps, $approaches){
        $select_sql = "SELECT * FROM exercises WHERE id=$id";
        if ($select_result = $conn->query($select_sql)){
            $this->select_exercise_data($select_result);
            $this->reps = $reps;
            $this->approaches = $approaches;
        }else{
            echo $conn->error;
        }
        $select_result->free();
    }
}