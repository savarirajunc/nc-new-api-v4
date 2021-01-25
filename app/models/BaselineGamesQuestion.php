<?php



class BaselineGamesQuestion extends \Phalcon\Mvc\Model
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
    public $baseline_games_id;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var string
     */
    public $answer_value;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $question_type;

    /**
     *
     * @var integer
     */
    public $question_order_id;

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
        $this->setSource("baseline_games_question");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BaselineGamesQuestion[]|BaselineGamesQuestion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BaselineGamesQuestion|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
