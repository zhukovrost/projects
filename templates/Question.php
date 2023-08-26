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
}