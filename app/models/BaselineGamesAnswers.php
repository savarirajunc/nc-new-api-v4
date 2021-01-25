<?php



class BaselineGamesAnswers extends \Phalcon\Mvc\Model
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
    public $kid_id;

    /**
     *
     * @var integer
     */
    public $game_id;

    /**
     *
     * @var integer
     */
    public $question_id;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var integer
     */
    public $timeval;

    /**
     *
     * @var integer
     */
    public $replaycount;

    /**
     *
     * @var integer
     */
    public $slide_no;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $slide_type;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("baseline_games_answers");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BaselineGamesAnswers[]|BaselineGamesAnswers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BaselineGamesAnswers|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
