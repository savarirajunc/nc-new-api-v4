<?php



class NcOrderList extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $bysource;

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
        $this->setSource("nc_order_list");
        $this->hasMany('order_id', 'NcOrderAmount', 'order_id', ['alias' => 'NcOrderAmount']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderList[]|NcOrderList|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderList|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
