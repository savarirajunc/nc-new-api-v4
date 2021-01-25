<?php



class SalesCenterAppointment extends \Phalcon\Mvc\Model
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
    public $dev_off_id;

    /**
     *
     * @var integer
     */
    public $coustomer_id;

    /**
     *
     * @var integer
     */
    public $child_id;

    /**
     *
     * @var string
     */
    public $choose_date;

    /**
     *
     * @var integer
     */
    public $sales_center_available_id;

    /**
     *
     * @var string
     */
    public $meeting_link;

    /**
     *
     * @var integer
     */
    public $meeting_status;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $note;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("sales_center_appointment");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesCenterAppointment[]|SalesCenterAppointment|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesCenterAppointment|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
