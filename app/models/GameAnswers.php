<?php



class GameAnswers extends \Phalcon\Mvc\Model
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
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $questions_no;

    /**
     *
     * @var integer
     */
    public $slide_no;

    /**
     *
     * @var string
     */
    public $answers;

    /**
     *
     * @var integer
     */
    public $actual_time;

    /**
     *
     * @var string
     */
    public $object_name;

    /**
     *
     * @var string
     */
    public $slide_type;

    /**
     *
     * @var integer
     */
    public $replaycount;

    /**
     *
     * @var integer
     */
    public $time;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $rec_data;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("game_answers");
        $this->belongsTo('game_id', '\GamesDatabase', 'id', ['alias' => 'GamesDatabase']);
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameAnswers[]|GameAnswers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameAnswers|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
