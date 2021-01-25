<?php



class Indicators extends \Phalcon\Mvc\Model
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
    public $indicator_name;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("indicators");
        $this->hasMany('id', 'GameCoreFrameMap', 'indicator_id', ['alias' => 'GameCoreFrameMap']);
        $this->hasMany('id', 'GamesTagging', 'indicators_id', ['alias' => 'GamesTagging']);
        $this->hasMany('id', 'StandardIndicatorsMap', 'indicators_id', ['alias' => 'StandardIndicatorsMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Indicators[]|Indicators|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Indicators|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
