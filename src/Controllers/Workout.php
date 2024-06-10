<?php
require_once __DIR__ . "/../Models/WorkoutModel.php";

class Workout { // class of workout
    protected $model;
    public $muscles = array(
        "arms" => 0,
        "legs" => 0,
        "press" => 0,
        "back" => 0,
        "chest" => 0,
        "cardio" => 0,
        "cnt" => 0
    );

    public function __construct($conn, $workout_id, $weekday=0){ // constrictor of Workout class
        $this->model = new WorkoutModel($conn, $workout_id, $weekday);
    }

    public function set_muscles (): array
    { // set muscle groups
        $muscles = array(
            "arms" => 0,
            "legs" => 0,
            "press" => 0,
            "back" => 0,
            "chest" => 0,
            "cardio" => 0,
            "cnt" => 0
        );
        foreach ($this->model->get_exercises() as $exercise){ // Iterate through each exercise in the workout
            $muscles[$exercise->get_muscles()]++;
            $muscles['cnt']++;
        }
        foreach ($muscles as $muscle=>$value){ // Calculate the percentage distribution of muscles worked
            if ($value != 0){
                $this->muscles[$muscle] = round($value / $muscles['cnt'] * 100, 0);
            }
        }

        return $muscles; // Return the muscle array
    }

    public function get_id(){ // det id of workout
        return $this->model->get_id(); // return id
    }
    function get_name() {
        return $this->model->name;
    }
    function get_loops()
    {
        return $this->model->loops;
    }

    public function print_exercises($conn){ // print exercises array
        foreach ($this->model->get_exercises() as $exercise){
            $exercise->print_it($conn); // print each exercise
        }
    }

    public function get_groups_amount(): int
    { // get amount of workout groups
        $cnt = 0;
        foreach ($this->muscles as $key=>$value){ // Loop through each muscle group in the $this->muscles array
            if ($value!=0) $cnt++; // If the value (percentage) for a muscle group is not zero, increment the counter
        }
        return $cnt; // Return the count of muscle groups worked in the workout
    }

    public function is_holiday(): bool {
        return $this->model->is_holiday();
    }

    public function print_workout_info($expand_buttons=0, $user_id=-1, $additional_info=false){ // print info about workout
        $workout = false;
        // section for workout information?>
        <section class="workouts-card__info">
            <?php
            if ($this->model->holiday){ ?>
                <div class="day-workouts__card-day-off">Выходной</div>
            <?php }else{ $workout = true; // Set the workout to true since it's not a holiday ?>
                <h2 class="day-workouts__card-name"><?php echo $this->model->name; ?></h2>
                <div class="workouts-card__info-line"></div>
                <div class="workouts-card__muscle-groups">
                    <p class="workouts-card__item">Руки: <span><?php echo $this->muscles["arms"]; ?>%</span></p>
                    <p class="workouts-card__item">Ноги: <span><?php echo $this->muscles["legs"]; ?>%</span></p>
                    <p class="workouts-card__item">Грудь: <span><?php echo $this->muscles["chest"]; ?>%</span></p>
                    <p class="workouts-card__item">Спина: <span><?php echo $this->muscles["back"]; ?>%</span></p>
                    <p class="workouts-card__item">Пресс: <span><?php echo $this->muscles["press"]; ?>%</span></p>
                    <p class="workouts-card__item">Кардио: <span><?php echo $this->muscles["cardio"]; ?>%</span></p>
                </div>
                <?php if ($expand_buttons == 1){ // buttons for my program page ?>
                <div class="day-workouts__card-buttons">
                    <div class="workouts-card__info-line"></div>
                    <?php if ($additional_info){ ?>
                        <img class="day-workouts__card-img" src="../../assets/img/done.svg" alt="">
                    <?php }else{ ?>
                        <img class="day-workouts__card-img" src="../../assets/img/not_done.svg" alt="">
                    <?php }?>
                </div>
            <?php } else if ($expand_buttons == 2){ // buttons for workout page ?>
                <div class="day-workouts__card-buttons">
                    <?php
                    if ($additional_info){ ?>
                        <button class="button-text day-workouts__card-button day-workouts__card-button--time"><p>Таймер</p><img src="../../assets/img/time.svg" alt=""></button>
                    <?php } ?>
                </div>
                <?php if ($additional_info){ ?>
                    <a href="../Pages/workout_session.php" class="button-text day-workouts__card-button day-workouts__card-button--start"><p>Начать</p><img src="../../assets/img/arrow_white.svg" alt=""></a>
                <?php } ?>
            <?php }
            }
            ?>
        </section>
            <?php
        return $workout; // Return the status of the workout
    }

    public function print_workout_info_block($day, $expand_buttons=0, $user_id=-1, $is_done=false, $button=''){ // print block for my program page ?>
        <section class="day-workouts__card">
            <h3 class="day-workouts__card-title"><?php echo get_day($day); ?></h3>
            <div class="day-workouts__card-content">
                <?php $this->print_workout_info($expand_buttons, $user_id, $is_done); ?>
            </div>
            <?php if ($button != '') echo $button; ?>
        </section>
    <?php
    }

    public function is_done($conn, $user_id, $day){ // check if workout is done or not
        return $this->model->is_done($conn, $user_id, $day);
    }
    public function get_exercises(){
        return $this->model->get_exercises();
    }
}

class Control_Workout extends Workout{ // class of control workout
    public function __construct($conn, $workout_id){ // constructor for control workout
        $this->model = new Control_Workout_Model($conn, $workout_id);
    }

    public function get_is_done() // check if workout if fone
    {
        return $this->model->is_done; // return true or false
    }

    public function print_control_workout_info($expand_buttons = 0, $user_id = -1, $additional_info = false)  // print info about control workout
    {
        // section for workout information?>
        <section class="workouts-card__info">
                <h2 class="day-workouts__card-name"><?php echo $this->model->name; ?></h2>
                <div class="workouts-card__info-line"></div>
                <div class="workouts-card__muscle-groups">
                    <p class="workouts-card__item">Руки: <span><?php echo $this->muscles["arms"]; ?>%</span></p>
                    <p class="workouts-card__item">Ноги: <span><?php echo $this->muscles["legs"]; ?>%</span></p>
                    <p class="workouts-card__item">Грудь: <span><?php echo $this->muscles["chest"]; ?>%</span></p>
                    <p class="workouts-card__item">Спина: <span><?php echo $this->muscles["back"]; ?>%</span></p>
                    <p class="workouts-card__item">Пресс: <span><?php echo $this->muscles["press"]; ?>%</span></p>
                    <p class="workouts-card__item">Кардио: <span><?php echo $this->muscles["cardio"]; ?>%</span></p>
                </div>
            <div class="workouts-card__info-line"></div>
            <!-- if not done print start button -->
            <?php if (!$this->model->is_done) echo '<a class="button-text day-workouts__card-button day-workouts__card-button--start" href="control_workout_session.php?id='.$this->id. '"><p>Начать</p><img src="../../assets/img/arrow_white.svg" alt=""></a>'; ?>
        </section>
        <?php
    }

    public function print_control_exercises($conn, $user_data=NULL){
        foreach ($this->model->get_exercises() as $exercise){ // Iterate through each exercise in the workout
            if ($user_data != NULL) // Check if $user_data is provided
                $exercise->print_control_exercise($conn, $exercise->is_featured($user_data, $conn)); // Print the control exercise based on whether it's featured for the user
            else
                $exercise->print_control_exercise($conn); // If $user_data is not provided, print the control exercise without considering featured status
        }
    }
    function get_date(){
        return $this->model->get_date();
    }
}
