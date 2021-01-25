<?php



class GuidedLearningGamesMap extends \Phalcon\Mvc\Model
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
    public $guided_learning_schedule_id;

    /**
     *
     * @var integer
     */
    public $games_tagging_id;

    /**
     *
     * @var integer
     */
    public $day_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("guided_learning_games_map");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningGamesMap[]|GuidedLearningGamesMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningGamesMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
