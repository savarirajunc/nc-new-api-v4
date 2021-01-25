<?php



class NcOrderAmount extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var double
     */
    public $total_amount;

    /**
     *
     * @var double
     */
    public $tax_amount;

    /**
     *
     * @var integer
     */
    public $discoun_amount;

    /**
     *
     * @var double
     */
    public $cart_amount;

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
        $this->setSource("nc_order_amount");
        $this->belongsTo('order_id', '\NcOrderList', 'order_id', ['alias' => 'NcOrderList']);
        $this->belongsTo('user_id', '\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderAmount[]|NcOrderAmount|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderAmount|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
