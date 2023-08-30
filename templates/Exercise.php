<?php

class Exercise {
    private $id;
    public $name;
    public $static;
    public $description="";
    public $muscles=[];
    private $image=0;
    public $rating;
    private $creator=1;
    public $difficulty;

    private function set_exercise_data($select_result){
        foreach ($select_result as $item){
            $this->id = $item['id'];
            $this->name = $item['name'];
            $this->description = $item['description'];
            $this->muscles = json_decode($item['muscles']);
            $this->image = $item['image'];
            $this->rating = $item['rating'];
            $this->static = $item['static'];
            $this->creator = $item['creator'];
            $this->difficulty = $item['difficulty'];
        }
    }

    public function __construct($conn, $id=0){
        if ($id != 0){
            $select_sql = "SELECT * FROM exercises WHERE id=$id";
            if ($select_result = $conn->query($select_sql)){
                $this->set_exercise_data($select_result);
            }else{
                echo $conn->error;
            }
            $select_result->free();
        }else{
            echo "Error";
        }
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

    public function is_featured($user){
        if (in_array($this->id, $user->featured_exercises)){
            return true;
        }
        return false;
    }
    public function is_mine($user){
        if (in_array($this->id, $user->my_exercises)){
            return true;
        }
        return false;
    }

    public function print_it($conn, $is_featured=false, $is_mine=false){
        if ($this->description == ""){
            $description = "No description for this exercise";
        }else{
            $description = $this->description;
        }
        if ($is_mine){
            $button = '<button class="add delete" name="delete" value="'.$this->id.'">Удалить <img src="../img/delete.svg" alt=""></button>';
        }else{
            $button = '<button class="add" name="add" value="'.$this->id.'">Добавить <img src="../img/add.svg" alt=""></button>';
        }
        if ($is_featured){
            $button_featured = '<button class="favorite" name="featured" value="'.$this->id.'"><img src="../img/favorite_added.svg" alt=""></button>';
        }else{
            $button_featured = '<button class="favorite" name="featured" value="'.$this->id.'"><img src="../img/favorite.svg" alt=""></button>';
        }
        $muscle_list = "";
        foreach ($this->muscles as $muscle){
            $muscle_list .= translate_group($muscle) . " ";
        }
        $muscle_list = str_replace(' ', '-', trim($muscle_list));

        $replaces = array(
            "{{ button }}" => $button,
            "{{ button_featured }}" => $button_featured,
            "{{ image }}" => $this->get_image($conn),
            "{{ name }}" => $this->name,
            "{{ rating }}" => $this->rating,
            "{{ difficulty }}" => $this->difficulty,
            "{{ id }}" => $this->id,
            "{{ muscle }}" => $muscle_list
        );
        echo render($replaces, "../templates/exercise.html");
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