<?php



class GamesDatabase extends \Phalcon\Mvc\Model
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
    public $game_id;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $modified_at;

    /**
     *
     * @var string
     */
    public $games_name;

    /**
     *
     * @var string
     */
    public $game_internal_name;

    /**
     *
     * @var integer
     */
    public $tina;

    /**
     *
     * @var integer
     */
    public $rahul;

    /**
     *
     * @var string
     */
    public $games_folder;

    /**
     *
     * @var string
     */
    public $daily_tips;

    /**
     *
     * @var string
     */
    public $game_type;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("games_database");
        $this->hasMany('id', 'GameAnswers', 'game_id', ['alias' => 'GameAnswers']);
        $this->hasMany('id', 'GameAudioRecord', 'game_id', ['alias' => 'GameAudioRecord']);
        $this->hasMany('id', 'GameQuestionAnswer', 'game_id', ['alias' => 'GameQuestionAnswer']);
        $this->hasMany('id', 'GameQuestionImageMaster', 'ref_game_id', ['alias' => 'GameQuestionImageMaster']);
        $this->hasMany('id', 'GamesTagging', 'games_database_id', ['alias' => 'GamesTagging']);
        $this->hasMany('id', 'GuidedLearningDayGameMap', 'game_id', ['alias' => 'GuidedLearningDayGameMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GamesDatabase[]|GamesDatabase|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GamesDatabase|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
