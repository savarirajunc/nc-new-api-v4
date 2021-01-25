<?php



class ZtestSubject extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $subno;

    /**
     *
     * @var string
     */
    public $subname;

    /**
     *
     * @var integer
     */
    public $cno;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("ztest_subject");
        $this->belongsTo('cno', '\ZtestClass', 'cno', ['alias' => 'ZtestClass']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ZtestSubject[]|ZtestSubject|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ZtestSubject|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
