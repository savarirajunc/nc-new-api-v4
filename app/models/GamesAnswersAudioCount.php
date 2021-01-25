<?php



class GamesAnswersAudioCount extends \Phalcon\Mvc\Model
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
    public $session_id;

    /**
     *
     * @var integer
     */
    public $game_id;

    /**
     *
     * @var integer
     */
    public $child_id;

    /**
     *
     * @var integer
     */
    public $click_count;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var integer
     */
    public $slide_no;

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
        $this->setSource("games_answers_audio_count");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GamesAnswersAudioCount[]|GamesAnswersAudioCount|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GamesAnswersAudioCount|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
