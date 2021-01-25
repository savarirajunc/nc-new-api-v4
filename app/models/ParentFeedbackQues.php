<?php



class ParentFeedbackQues extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var integer
     */
    public $question_type;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("parent_feedback_ques");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ParentFeedbackQues[]|ParentFeedbackQues|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ParentFeedbackQues|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
