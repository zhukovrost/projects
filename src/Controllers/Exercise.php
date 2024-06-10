<?php
require_once __DIR__ . "/../Models/ExerciseModel.php";

class Exercise {
    protected $model;

    public function is_rated($user_id){ // check if exercises is rated
        foreach ($this->model->ratings as $key=>$value)  // Checks if a user has rated the exercise
            if ($key == $user_id) return 1; // If the user has rated, returns 1 (true)
        return 0; // Returns 0 (false) if the user hasn't rated
    }

    public function get_rating(){ // get rating of exercise
        if (count($this->model->ratings) == 0) // Calculates and retrieves the average rating for the exercise
            return 0; // If there are no ratings, returns 0
        $cnt = 0; // Counter for the number of ratings
        $rating = 0; // Accumulator for total ratings
        foreach ($this->model->ratings as $key=>$value){
            $cnt++; // Incrementing the count for each rating
            $rating += $value; // Accumulating the total rating
        }
        return round($rating/$cnt, 1); // Calculates and returns the average rating, rounded to one decimal place
    }

    public function set_exercise_data($select_result){ // set data to exercise
        $this->model->set_exercise_data($select_result);
    }

    public function __construct($conn, $id=0){ // contructor for exercise
        if ($id != 0){
            $this->model = new ExerciseModel($conn, $id);
        }
    }

    public function get_id(){ // get exercise's id
        return $this->model->get_id(); // Returns the ID of the exercise
    }

    public function get_image(): string
    {
        return "../../assets/img/exercises/" . $this->get_id() . ".jpg"; // Returns the image file name or null if there's an error
    }

    public function is_featured($user, $conn): bool
    { // check if exercise is featured
        if (in_array($this->get_id(), $user->get_featured_exercises($conn))){ // Checks if the exercise is among the featured exercises of the user
            return true; // Returns true if the exercise is among the user's featured exercises
        }
        return false; // Returns false if it's not among the featured exercises
    }
    public function is_mine($user, $conn): bool
    { // check if exercise is mine
        if (in_array($this->get_id(), $user->get_my_exercises($conn))){ // Checks if the exercise is among the user's owned exercises
            return true; // Returns true if the exercise is among the user's owned exercises
        }
        return false; // Returns false if it's not among the owned exercises
    }

    public function print_it($conn, $is_featured=false, $is_mine=false, $construct=false, $is_current=false){ // print exercise card
        // set description
        if ($this->model->description == ""){
            $description = "Нет описания";
        }else{
            $description = $this->model->description;
        }

        if ($construct){ // for construct pages
            $button = '<button class="button-text exercise-item__add" name="add" value="'. $this->get_id(). '" type="button"><p>Добавить</p> <img src="../../assets/img/add.svg" alt=""></button>';
        }else{
            if ($is_mine){ // if mine
                $button = '<button class="button-text exercise-item__delite" name="delete" value="'.$this->get_id(). '"><p>Удалить</p> <img src="../../assets/img/delete.svg" alt=""></button>';
            }else{
                $button = '<button class="button-text exercise-item__add" name="add" value="'.$this->get_id(). '"><p>Добавить</p> <img src="../../assets/img/add.svg" alt=""></button>';
            }
        }

        if ($is_featured){ // if featured
            $button_featured = '<button class="exercise-item__favorite exercise-item__favorite--selected" name="featured" value="'.$this->get_id(). '"><img src="../../assets/img/favorite_added.svg" alt=""></button>';
        }else{
            $button_featured = '<button class="exercise-item__favorite" name="featured" value="'.$this->get_id(). '"><img src="../../assets/img/favorite.svg" alt=""></button>';
        }


        $muscle_list = translate_group($this->model->muscles); // Translate each muscle name and concatenate them with spaces

//        $muscle_list = str_replace(' ', '-', trim($muscle_list)); // Replace spaces with hyphens and trim the resulting string

        $replaces = array( // Create an array of placeholders and their corresponding values
            "{{ button }}" => $button,
            "{{ button_featured }}" => $button_featured,
            "{{ image }}" => $this->get_image(),
            "{{ name }}" => $this->model->name,
            "{{ rating }}" => $this->get_rating(),
            "{{ difficulty }}" => $this->model->difficulty,
            "{{ id }}" => $this->get_id(),
            "{{ muscle }}" => $muscle_list,
            "{{ description }}" => $description
        );
        echo render($replaces, "../../templates/exercise.html"); // Render the HTML template by replacing placeholders with actual data
    }
}

class User_Exercise extends Exercise { // class of exercise with reps and approaches
    public function __construct($conn, $id = 0, $reps = 0, $approaches = 0) { // contract for class
        parent::__construct($conn, $id);
        $this->model = new User_Exercise_Model($conn, $id, $reps, $approaches);
    }

    public function print_it($conn, $is_featured=false, $is_mine=false, $construct=false, $is_current=false){ // print exercise card
        // set description
        if ($this->model->description == ""){
            $description = "Нет описания";
        }else{
            $description = $this->model->description;
        }
        if ($is_featured){ // check featured
            $button_featured = '<button class="exercise-item__favorite exercise-item__favorite--selected" name="featured" value="'.$this->get_id(). '"><img src="../../assets/img/favorite_added.svg" alt=""></button>';
        }else{
            $button_featured = '<button class="exercise-item__favorite" name="featured" value="'.$this->get_id(). '"><img src="../../assets/img/favorite.svg" alt=""></button>';
        }

        $muscle_list = translate_group($this->model->muscles); // Translate each muscle name and concatenate them with spaces

//        $muscle_list = str_replace(' ', '-', trim($muscle_list)); // Replace spaces with hyphens and trim the resulting string
        $btn_done = '';
        if ($is_current)
            $btn_done = '<button class="button-text exercise-item__done"><p>Подход сделан</p><img src="../../assets/img/done_white.svg" alt=""></button>';

        $replaces = array( // Create an array of placeholders and their corresponding values
            "{{ button_featured }}" => $button_featured,
            "{{ image }}" => $this->get_image(),
            "{{ name }}" => $this->model->name,
            "{{ rating }}" => $this->get_rating(),
            "{{ difficulty }}" => $this->model->difficulty,
            "{{ id }}" => $this->get_id(),
            "{{ muscle }}" => $muscle_list,
            "{{ reps }}" => $this->model->reps,
            "{{ approaches }}" => $this->model->sets,
            "{{ description }}" => $description,
            "{{ button_done }}" => $btn_done
        );
        echo render($replaces, "../../templates/user_exercise.html"); // Render the HTML template by replacing placeholders with actual data
    }

    public function print_control_exercise($conn, $is_featured=false, $current=false, $construct=false){ // same as print_it function, but get control exercise data and render control exercise template
        if ($this->model->description == ""){
            $description = "Нет описания";
        }else{
            $description = $this->model->description;
        }

        $muscle_list = $this->model->muscles;

//        $muscle_list = str_replace(' ', '-', trim($muscle_list));
        $inp = '<div class="exercise-item__repetitions"><p class="exercise-item__repetitions-score">Нет данных</p></div>';
        if ($current) {
            $inp = '<p class="exercise-item__repetitions-score"><input class="exercise-item__input" type="number" placeholder="результат" name="reps[]"></p>';
        }

        if ($construct)
            $inp = "<div class='exercise-item__buttons'><input type='hidden' name='exercise' value='".$this->get_id(). "'><button class='button-text exercise-item__add'><p>Добавить</p><img src='../../assets/img/add.svg' alt=''></button></div>";

        if (!$construct && !$current && $this->model->reps > 0)
            $inp =  '<div class="exercise-item__repetitions"><p class="exercise-item__repetitions-score">'.$this->get_reps().'</p></div>';
        if (!$current)
            if ($is_featured)
                $button_featured = '<button class="exercise-item__favorite exercise-item__favorite--selected" name="featured" value="'.$this->get_id(). '"><img src="../../assets/img/favorite_added.svg" alt=""></button>';
            else
                $button_featured = '<button class="exercise-item__favorite" name="featured" value="'.$this->get_id(). '"><img src="../../assets/img/favorite.svg" alt=""></button>';
        else
            $button_featured = '';
        $replaces = array(
            "{{ image }}" => $this->get_image(),
            "{{ name }}" => $this->model->name,
            "{{ rating }}" => $this->get_rating(),
            "{{ difficulty }}" => $this->model->difficulty,
            "{{ muscle }}" => $muscle_list,
            "{{ description }}" => $description,
            "{{ input }}" => $inp,
            "{{ button_featured }}" => $button_featured
        );
        echo render($replaces, "../../templates/control_exercise.html");
    }

    function get_muscles()
    {
        return $this->model->muscles;
    }
    function get_reps(){
        return $this->model->reps;
    }
    function get_sets(){
        return $this->model->sets;
    }
}