<?php



class ZtestClass extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cno;

    /**
     *
     * @var string
     */
    public $cname;

    /**
     *
     * @var string
     */
    public $sname;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("ztest_class");
        $this->hasMany('cno', 'ZtestSubject', 'cno', ['alias' => 'ZtestSubject']);
        $this->belongsTo('sname', '\ZtestSchool', 'sname', ['alias' => 'ZtestSchool']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ZtestClass[]|ZtestClass|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ZtestClass|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
