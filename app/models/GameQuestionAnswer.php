<?php



class GameQuestionAnswer extends \Phalcon\Mvc\Model
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
    public $question_id;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var string
     */
    public $answer;

    /**
     *
     * @var string
     */
    public $answer_des;

    /**
     *
     * @var integer
     */
    public $game_type;

    /**
     *
     * @var integer
     */
    public $game_type_value;

    /**
     *
     * @var integer
     */
    public $game_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("game_question_answer");
        $this->belongsTo('game_id', '\GamesDatabase', 'id', ['alias' => 'GamesDatabase']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameQuestionAnswer[]|GameQuestionAnswer|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameQuestionAnswer|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
