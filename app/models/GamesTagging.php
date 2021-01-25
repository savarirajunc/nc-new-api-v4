<?php



class GamesTagging extends \Phalcon\Mvc\Model
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
    public $indicators_id;

    /**
     *
     * @var integer
     */
    public $games_database_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("games_tagging");
        $this->belongsTo('games_database_id', '\GamesDatabase', 'id', ['alias' => 'GamesDatabase']);
        $this->belongsTo('indicators_id', '\Indicators', 'id', ['alias' => 'Indicators']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GamesTagging[]|GamesTagging|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GamesTagging|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
