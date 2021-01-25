<?php



class NcTimelist extends \Phalcon\Mvc\Model
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
    public $tittle_name;

    /**
     *
     * @var string
     */
    public $tittle_value;

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
        $this->setSource("nc_timelist");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcTimelist[]|NcTimelist|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcTimelist|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
