<?php



class GuidedLearningDayGameMap extends \Phalcon\Mvc\Model
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
    public $day_id;

    /**
     *
     * @var integer
     */
    public $framework_id;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $day_guided_learning_id;

    /**
     *
     * @var integer
     */
    public $game_id;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("guided_learning_day_game_map");
        $this->belongsTo('day_id', '\Days', 'id', ['alias' => 'Days']);
        $this->belongsTo('framework_id', '\CoreFrameworks', 'id', ['alias' => 'CoreFrameworks']);
        $this->belongsTo('game_id', '\GamesDatabase', 'id', ['alias' => 'GamesDatabase']);
        $this->belongsTo('day_guided_learning_id', '\GuidedLearning', 'id', ['alias' => 'GuidedLearning']);
        $this->belongsTo('subject_id', '\Subject', 'id', ['alias' => 'Subject']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningDayGameMap[]|GuidedLearningDayGameMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningDayGameMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
