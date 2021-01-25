<?php



class SchoolRegistrationDate extends \Phalcon\Mvc\Model
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
    public $school_id;

    /**
     *
     * @var string
     */
    public $start_date;

    /**
     *
     * @var string
     */
    public $end_date;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("school_registration_date");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SchoolRegistrationDate[]|SchoolRegistrationDate|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SchoolRegistrationDate|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
