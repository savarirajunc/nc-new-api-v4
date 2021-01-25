<?php



class GameTaggingHealthMaster extends \Phalcon\Mvc\Model
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
    public $heath_name;

    /**
     *
     * @var string
     */
    public $heath_definition;

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
        $this->setSource("game_tagging_health_master");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameTaggingHealthMaster[]|GameTaggingHealthMaster|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameTaggingHealthMaster|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
