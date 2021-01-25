<?php



class ZtestSchool extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $sno;

    /**
     *
     * @var string
     */
    public $sname;

    /**
     *
     * @var integer
     */
    public $sage;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("ztest_school");
        $this->hasMany('sname', 'ZtestClass', 'sname', ['alias' => 'ZtestClass']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ZtestSchool[]|ZtestSchool|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ZtestSchool|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
