<?php



class CoreChildHealthQuestion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $question_type;

    /**
     *
     * @var integer
     */
    public $month;

    /**
     *
     * @var string
     */
    public $question;

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
        $this->setSource("core_child_health_question");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreChildHealthQuestion[]|CoreChildHealthQuestion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreChildHealthQuestion|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
