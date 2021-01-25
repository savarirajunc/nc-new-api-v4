<?php



class NcProduct extends \Phalcon\Mvc\Model
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
    public $product_name;

    /**
     *
     * @var string
     */
    public $product_type;

    /**
     *
     * @var string
     */
    public $product_des;

    /**
     *
     * @var string
     */
    public $product_img;

    /**
     *
     * @var integer
     */
    public $product_status;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nc_product");
        $this->hasMany('id', 'NcProductPriceType', 'product_id', ['alias' => 'NcProductPriceType']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcProduct[]|NcProduct|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcProduct|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
