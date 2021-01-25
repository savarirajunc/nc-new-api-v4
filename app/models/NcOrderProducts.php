<?php



class NcOrderProducts extends \Phalcon\Mvc\Model
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
    public $product_id;

    /**
     *
     * @var integer
     */
    public $order_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nc_order_products");
        $this->belongsTo('product_id', '\NcProductPriceType', 'id', ['alias' => 'NcProductPriceType']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderProducts[]|NcOrderProducts|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcOrderProducts|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
