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
    private $creator = 0;

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
                    $this->creator = $item["creator"];
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

    public function print_workout_info($expand_buttons=0, $user_id=-1){
        $workout = false;
        if ($this->holiday){ ?>
            <div class="day-workouts__card-day-off">Выходной</div>
        <?php }else{ $workout = true; ?>
            <h2 class="day-workouts__card-name"><?php echo $this->name; ?></h2>
            <p class="day-workouts__card-item">Руки: <span><?php echo $this->muscles["arms"]; ?>%</span></p>
            <p class="day-workouts__card-item">Ноги: <span><?php echo $this->muscles["legs"]; ?>%</span></p>
            <p class="day-workouts__card-item">Грудь: <span><?php echo $this->muscles["chest"]; ?>%</span></p>
            <p class="day-workouts__card-item">Спина: <span><?php echo $this->muscles["back"]; ?>%</span></p>
            <p class="day-workouts__card-item">Пресс: <span><?php echo $this->muscles["press"]; ?>%</span></p>
            <p class="day-workouts__card-item">Кардио: <span><?php echo $this->muscles["cardio"]; ?>%</span></p>
            <?php if ($expand_buttons == 1){ ?>
            <div class="day-workouts__card-buttons">
                <button class="button-img day-workouts__card-button"><img src="../img/more_white.svg" alt=""></button>
                <button class="button-img day-workouts__card-button"><img src="../img/edit.svg" alt=""></button>
                <img class="day-workouts__card-img" src="../img/done.svg" alt="">
            </div>
        <?php } else if ($expand_buttons == 2){ ?>
            <div class="day-workouts__card-buttons">
                <?php if ($this->creator == $user_id){ ?>
                    <button class="button-img day-workouts__card-button"><img src="../img/edit.svg" alt=""></button>
                <?php } ?>
                <a href="workout_session.php" class="button-text day-workouts__card-button">Начать</a>
            </div>
        <?php }
        }
        return $workout;
    }

    public function print_workout_info_block($day, $expand_buttons=0, $user_id=-1){ ?>
        <section class="day-workouts__card">
            <h3 class="day-workouts__card-title"><?php echo get_day($day); ?></h3>
            <div class="day-workouts__card-content">
                <?php $this->print_workout_info($expand_buttons, $user_id); ?>
            </div>
        </section>
    <?php
    }
}