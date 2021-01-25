<?php



class SalesMeetingAvailability extends \Phalcon\Mvc\Model
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
    public $center_id;

    /**
     *
     * @var string
     */
    public $start_time;

    /**
     *
     * @var string
     */
    public $end_time;

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
        $this->setSource("sales_meeting_availability");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesMeetingAvailability[]|SalesMeetingAvailability|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesMeetingAvailability|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
