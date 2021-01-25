<?php



class SalesMeetingAppointment extends \Phalcon\Mvc\Model
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
    public $meeting_no;

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
    public $apo_date;

    /**
     *
     * @var integer
     */
    public $apo_time;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("sales_meeting_appointment");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesMeetingAppointment[]|SalesMeetingAppointment|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesMeetingAppointment|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
