<?php



class Standard extends \Phalcon\Mvc\Model
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
    public $standard_name;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var double
     */
    public $weightage;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("standard");
        $this->hasMany('id', 'GameCoreFrameMap', 'standard_id', ['alias' => 'GameCoreFrameMap']);
        $this->hasMany('id', 'StandardIndicatorsMap', 'standard_id', ['alias' => 'StandardIndicatorsMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Standard[]|Standard|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Standard|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
