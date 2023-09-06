<?php

class Workout {
    private $id;
    public $exercises = array();
    public $loops = 1;
    public $weekday;
    public $muscles = array(
        "arms" => 0,
        "legs" => 0,
        "press" => 0,
        "back" => 0,
        "chest" => 0,
        "cardio" => 0
    );
    public $holiday = false;
    public $name = '';

    public function __construct($conn, $workout_id, $weekday){
        if ($workout_id == 0){
            $this->holiday = true;
        }else{
            $select_sql = "SELECT * FROM workouts WHERE id=$workout_id";
            if ($select_result = $conn->query($select_sql)){
                $this->weekday = $weekday;
                foreach ($select_result as $item){
                    $this->id = $item['id'];
                    $this->loops = $item['loops'];
                    $exercises = json_decode($item['exercises']);
                    $reps = json_decode($item['reps']);
                    $approaches = json_decode($item['approaches']);
                    $this->name = $item['name'];
                }

                for ($i = 0; $i < count($exercises); $i++){
                    array_push($this->exercises, new User_Exercise($conn, $exercises[$i], $reps[$i], $approaches[$i]));
                }
            }else{
                echo $conn->error;
            }
            $select_result->free();
        }
    }

    public function set_muscles (){
        $muscles = array(
            "arms" => 0,
            "legs" => 0,
            "press" => 0,
            "back" => 0,
            "chest" => 0,
            "cardio" => 0,
            "cnt" => 0
        );
        foreach ($this->exercises as $exercise){
            foreach ($exercise->muscles as $muscle){
                $muscles[$muscle]++;
                $muscles['cnt']++;
            }
        }
        foreach ($muscles as $muscle=>$value){
            if ($value != 0){
                $this->muscles[$muscle] = round($value / $muscles['cnt'] * 100, 0);
            }
        }

        return $muscles;
    }

    public function print_exercises($conn){
        foreach ($this->exercises as $exercise){
            $exercise->print_it($conn);
        }
    }

    public function get_groups_amount(){
        $cnt = 0;
        foreach ($this->muscles as $key=>$value){
            if ($value!=0) $cnt++;
        }
        return $cnt;
    }

    public function print_workout_info(){ ?>
        <div class="workout_info">
            <div class="muscle_groups">
                <p>Руки: <span><?php echo $this->muscles["arms"]; ?>%</span></p>
                <p>Ноги: <span><?php echo $this->muscles["legs"]; ?>%</span></p>
                <p>Грудь: <span><?php echo $this->muscles["chest"]; ?>%</span></p>
                <p>Спина: <span><?php echo $this->muscles["back"]; ?>%</span></p>
                <p>Пресс: <span><?php echo $this->muscles["press"]; ?>%</span></p>
                <p>Кардио: <span><?php echo $this->muscles["cardio"]; ?>%</span></p>
            </div>
            <div class="line"></div>
            <div class="exercise">
                <p>Упражнений: <span><?php echo count($this->exercises); ?></span></p>
                <p>Кругов: <span><?php echo $this->loops; ?></span></p>
            </div>
            <div class="line"></div>
            <div class="buttons">
                <button><img src="../img/edit.svg" alt=""></button>
                <button>Начать</button>
            </div>
        </div>
    <?php }
}