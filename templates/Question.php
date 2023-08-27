<?php
class Question
{
    private $id;
    public $question;
    public $theme;
    public $type;
    public $variants;
    private $right_answers;
    public $score;
    public $image;

    public function __construct($question='', $theme=0, $type='', $variants=[], $right_answers=[], $score=0, $image=0)
    {
        $this->question = $question;
        $this->theme = $theme;
        $this->type = $type;
        $this->variants = $variants;
        $this->right_answers = $right_answers;
        $this->score = $score;
        $this->image = $image;
    }

    public function get_id(){
        return $this->id;
    }
    public function get_right_answers(){
        return $this->right_answers;
    }

    public function set_last_id($conn){
        $this->id=mysqli_insert_id($conn);
    }

    public function set_question_data($conn, $get_id){
        $select_question_sql = "SELECT * FROM questions WHERE id=$get_id";
        if ($select_question_result = $conn->query($select_question_sql)){
            foreach ($select_question_result as $question_data){
                $this->id = $question_data['id'];
                $this->question = $question_data['question'];
                $this->theme = $question_data['theme'];
                $this->type = $question_data['type'];
                $this->variants = json_decode($question_data['variants']);
                $this->right_answers = json_decode($question_data['right_answers']);
                $this->score = $question_data['score'];
                $this->image = $question_data['image'];
            }
            $select_question_result->free();
            return true;
        }else{
            return false;
        }
    }

    public function insert($conn){
        $add_question_sql = "INSERT INTO questions (question, theme, type, score, variants, right_answers, image) VALUES ('$this->question', $this->theme, '$this->type', $this->score, '".json_encode($this->variants, 256)."', '".json_encode($this->right_answers, 256)."', $this->image)";
        if ($conn->query($add_question_sql)){
            $this->set_last_id($conn);
            return true;
        }else{
            echo $conn->error;
            return false;
        }
    }

    public function save($conn){
        $new_variants = json_encode($this->variants, 256);
        $new_right_answers = json_encode($this->right_answers, 256);
        $sql = "UPDATE questions SET question='$this->question', variants='$new_variants', right_answers='$new_right_answers' WHERE id=$this->id";
        if ($conn->query($sql)){
            return true;
        }
        return false;
    }

    public function update($conn, $new_question, $new_variants, $new_right_answers){
        $this->question = $new_question;
        $this->variants = $new_variants;
        $this->right_answers = $new_right_answers;
        $this->save($conn);
    }

    public function print_image($conn){
        if ($this->image != 0){
            $select_image_sql = "SELECT image FROM test_images WHERE id=$this->id";
            if ($select_image_result = $conn->query($select_image_sql)){
                foreach ($select_image_result as $item){
                    $image = $item['image'];
                }
                echo '<img src="data:image;base64,'.base64_encode($image).'"/>'; # image
            }
            $select_image_result->free();
        }
    }

    public function print_it($conn, $question_number, $extend=false, $user_answers_id=-1){
        if ($user_answers_id != -1){
            $user_answers = (array)json_decode(get_tests_to_users_data($conn, $user_answers_id)['answers']);
        } ?>
        <section class="question">
            <div class="underline_title">
                <h2>Question: <span><?php echo $question_number + 1; ?></span></h2>
                <h2>Score: <span><?php echo $this->score; ?></span></h2>
            </div>
            <div class="content">
                <h1><?php echo $this->question; ?></h1>
                <?php switch ($this->type){
                    case "radio":
                        ?> <p>Choose one answer:</p><?php
                        break;
                    case "checkbox":
                        ?> <p>Choose n answers:</p> <?php
                        break;
                    case "definite_mc":
                    case "definite":
                        ?> <p>Answer the question:</p><?php
                        break;
                    case "missing_words":
                        ?> <p>Enter missing words:</p><?php
                        break;
                } ?>
                <div class="answers">
                    <?php if ($this->type == 'radio' || $this->type == 'checkbox'){
                        for ($i = 0; $i < count($this->variants); $i++){?>
                            <div>
                                <?php if ($user_answers_id != -1 && array_key_exists($question_number, $user_answers) && in_array($i, $user_answers[$question_number])){
                                    echo "<input type='$this->type' name='test_input[$question_number][]' value='$i' checked>";
                                }else{
                                    echo "<input type='$this->type' name='test_input[$question_number][]' value='$i'>";
                                }
                                echo "<label>$this->variants</label>"; ?>
                            </div>
                            <?php }
                    }else if ($this->type == 'missing_words'){
                        for ($i = 0; $i < count($this->get_right_answers()); $i++){
                            ?> <div> <?php
                                if ($user_answers_id == -1) {
                                    echo "<input type='text' name='test_input[$question_number][]'>";
                                }else{
                                    $value = $user_answers[$question_number][(string)$i];
                                    echo "<input type='text' name='test_input[$question_number][]' value='$value'>";
                                } ?>
                            </div>
                            <?php }
                    } else if ($this->type == "definite"){
                        if ($user_answers_id == -1){
                            echo "<div><input type='text' name='test_input[$question_number][]'></div>";
                        }else{
                            $value = $user_answers[$question_number][0];
                            echo "<div><input type='text' name='test_input[$question_number][]' value='$value'></div>";
                        }
                    }else if ($this->type == "definite_mc"){ ?>
                        <input type="hidden" name="for_verification" value="1">
                        <?php if ($user_answers_id == -1 || $user_answers[$question_number] == null){
                            echo "<div><textarea name='test_input[$question_number][]'></textarea></div>";
                        }else{
                            echo "<div><textarea name='test_input[$question_number][]'>".$user_answers[$question_number][0]."</textarea></div>";
                        }
                    }
                    $this->print_image($conn);
                    if ($extend && $this->type != "definite_mc"){
                        echo "<p>Right answer(s): ";
                        foreach ($this->get_right_answers() as $right_answer) {
                            if ($this->type == "missing_words" || $this->type == "definite"){
                                echo $right_answer."; ";
                            }else{
                                echo $this->variants[$right_answer] . "; ";
                            }
                        }
                        echo "</p>";
                    } ?>
                </div>
            </div>
        </section>
    <?php }
}