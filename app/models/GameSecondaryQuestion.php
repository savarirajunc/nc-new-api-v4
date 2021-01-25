<?php



class GameSecondaryQuestion extends \Phalcon\Mvc\Model
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
    public $game_id;

    /**
     *
     * @var integer
     */
    public $sectiontype;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $standard;

    /**
     *
     * @var integer
     */
    public $indicators;

    /**
     *
     * @var integer
     */
    public $question_type;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var integer
     */
    public $answer_show_type;

    /**
     *
     * @var integer
     */
    public $question_id;

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
        $this->setSource("game_secondary_question");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameSecondaryQuestion[]|GameSecondaryQuestion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameSecondaryQuestion|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
