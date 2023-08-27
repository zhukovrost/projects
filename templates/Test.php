<?php

class Test {
    private $id;
    public $name;
    public $task;
    public $test;
    public $themes;
    public $questions = array();

    public function __construct($name='', $task='', $test=[], $themes=[])
    {
        $this->name = $name;
        $this->task = $task;
        $this->test = $test;
        $this->themes = $themes;
    }

    public function get_id(){
        return $this->id;
    }
    public function set_last_id($conn){
        $this->id=mysqli_insert_id($conn);
    }
    public function set_test_data($conn, $get_id){
        $select_test_sql = "SELECT * FROM tests WHERE id=$get_id";
        if ($select_test_result = $conn->query($select_test_sql)){
            foreach ($select_test_result as $test_data){
                $this->id = $test_data['id'];
                $this->name = $test_data['name'];
                $this->test = json_decode($test_data['test']);
                $this->themes = json_decode($test_data['themes']);
                $this->task = $test_data['task'];
            }
            $select_test_result->free();
            return true;
        }else{
            return false;
        }
    }

    public function get_questions($conn){
        foreach ($this->test as $question_id){
            $question = new Question();
            $question->set_question_data($conn, $question_id);
            array_push($this->questions, $question);
        }
    }

    public function delete_question($num_del){
        $new_array = array();
        for ($i = 0; $i < $num_del; $i++){ array_push($new_array, $this->test[$i]); }
        for ($i = $num_del + 1; $i < count($this->test); $i++){ array_push($new_array, $this->test[$i]); }
        $this->test = $new_array;
    }

    public function set_themes(){
        if (count($this->questions) > 0){
            $this->themes = array();
            foreach ($this->questions as $question){
                if (!in_array($question->theme, $this->themes)){
                    array_push($this->themes, $question->theme);
                }
            }
            sort($this->themes);
            return true;
        }

        return false;
    }

    public function insert($conn){
        $add_question_sql = "INSERT INTO tests (name, task, test, themes) VALUES ('$this->name', '$this->task', '".json_encode($this->test)."', '".json_encode($this->themes)."')";
        if ($conn->query($add_question_sql)){
            $this->set_last_id($conn);
            return true;
        }else{
            echo $conn->error;
            return false;
        }
    }

    public function print_it($conn, $extend=false, $user_answers_id=-1){
        if (count($this->questions) == 0) { $this->get_questions($conn); }?>
        <form method="post" class="questions_list">
            <div class="container">
                <h1 class="curtest_title"><?php echo $this->task; ?></h1>
                <?php for ($i = 0; $i < count($this->test); $i++){
                    $question = $this->questions[$i];
                    $question->print_it($conn, $i, $extend, $user_answers_id);
                }
                if(!$extend && $user_answers_id == -1){ ?>
                    <button class="finish" id="FinsishButton" name="finish" value="1">Finish</button>
                <?php } ?>
            </div>
        </form>
    <?php }
}