<?php



class GameTaggingTransection extends \Phalcon\Mvc\Model
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
    public $gameID;

    /**
     *
     * @var integer
     */
    public $slideid;

    /**
     *
     * @var integer
     */
    public $slidenum;

    /**
     *
     * @var integer
     */
    public $health_parameter;

    /**
     *
     * @var integer
     */
    public $data_Capture_Parameter;

    /**
     *
     * @var double
     */
    public $weightage;

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
        $this->setSource("game_tagging_transection");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameTaggingTransection[]|GameTaggingTransection|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameTaggingTransection|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
