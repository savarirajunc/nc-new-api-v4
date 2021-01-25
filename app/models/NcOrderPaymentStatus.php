<?php



class NcOrderPaymentStatus extends \Phalcon\Mvc\Model
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
    public $order_id;

    /**
     *
     * @var string
     */
    public $tracking_id;

    /**
     *
     * @var string
     */
    public $bank_ref_no;

    /**
     *
     * @var string
     */
    public $order_status;

    /**
     *
     * @var string
     */
    public $failure_message;

    /**
     *
     * @var string
     */
    public $payment_mode;

    /**
     *
     * @var string
     */
    public $card_name;

    /**
     *
     * @var string
     */
    public $status_code;

    /**
     *
     * @var string
     */
    public $status_message;

    /**
     *
     * @var string
     */
    public $currency;

    /**
     *
     * @var double
     */
    public $amount;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nc_order_payment_status");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderPaymentStatus[]|NcOrderPaymentStatus|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderPaymentStatus|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
