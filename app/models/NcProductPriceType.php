<?php



class NcProductPriceType extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $product_main_id;

    /**
     *
     * @var integer
     */
    public $product_price;

    /**
     *
     * @var string
     */
    public $product_type;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     *
     * @var string
     */
    public $modify_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nc_product_price_type");
        $this->hasMany('id', 'NcOrderProducts', 'product_id', ['alias' => 'NcOrderProducts']);
        $this->belongsTo('product_id', '\NcProduct', 'id', ['alias' => 'NcProduct']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcProductPriceType[]|NcProductPriceType|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcProductPriceType|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
