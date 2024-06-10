<?php

class ExerciseModel
{
    private $id;
    public $name;
    public $static;
    public $description="";
    public $muscles=[];
    public $ratings = array();
    public $difficulty=3;
    private $creator=1;

    public function get_id() {
        return $this->id;
    }

    public function __construct($conn, $id=0){ // constructor for exercise
        $select_sql = "SELECT * FROM exercises WHERE id=$id"; // Fetches exercise data from the database based on the provided ID
        if ($select_result = $conn->query($select_sql)){ // Sets exercise data using the retrieved information
            $this->set_exercise_data($select_result);
            $this->set_rating($conn); // Sets exercise ratings
        }else{
            echo $conn->error; // Outputs an error if the query fails
        }
        $select_result->free();
    }

    public function set_exercise_data($select_result){ // set data to exercise
        foreach ($select_result as $item){ // Assigning properties with data from the database query result
            $this->id = $item['id'];
            $this->creator = $item['creator'];
            $this->name = $item['name'];
            $this->description = $item['description'];
            $this->muscles = $item['muscles'];
            $this->static = $item['static'];
            $this->difficulty = $item["difficulty"];
        };
    }

    private function set_rating($conn){ // set exercise rating
        $sql = "SELECT user, rate FROM exercise_ratings WHERE exercise=".$this->id;  // SQL query to select users and their respective ratings for a specific exercise
        if ($result = $conn->query($sql)){ // If the SQL query executes successfully
            foreach ($result as $rate) { // Looping through the fetched results
                $this->ratings[$rate["user"]] = $rate["rate"];  // Assigning user ratings to the exercise's 'ratings' property
            }
        }else
            echo $conn->error; // Outputting any database error messages if the query fails
    }
}


class User_Exercise_Model extends ExerciseModel
{
    public $reps;
    public $sets;

    public function __construct($conn, $id=0, $reps=0, $sets=0)
    {
        parent::__construct($conn, $id);
        $this->reps = $reps;
        $this->sets = $sets;
    }
}