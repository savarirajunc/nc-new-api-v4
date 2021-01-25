<?php



class DoctorVisit extends \Phalcon\Mvc\Model
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
    public $child_id;

    /**
     *
     * @var integer
     */
    public $visit_no;

    /**
     *
     * @var string
     */
    public $visit_date;

    /**
     *
     * @var string
     */
    public $time;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     *
     * @var string
     */
    public $modify;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("doctor_visit");
        $this->belongsTo('child_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DoctorVisit[]|DoctorVisit|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DoctorVisit|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
