<?php



class NcProductDiscountCoupon extends \Phalcon\Mvc\Model
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
    public $discount_coupon_name;

    /**
     *
     * @var integer
     */
    public $coupon_type;

    /**
     *
     * @var string
     */
    public $discount_valid;

    /**
     *
     * @var string
     */
    public $discount_valid_end;

    /**
     *
     * @var integer
     */
    public $discount_limit;

    /**
     *
     * @var string
     */
    public $coupon_code;

    /**
     *
     * @var string
     */
    public $discount_type;

    /**
     *
     * @var integer
     */
    public $discount_value;

    /**
     *
     * @var integer
     */
    public $coupon_status;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     *
     * @var string
     */
    public $modified_data;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nc_product_discount_coupon");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcProductDiscountCoupon[]|NcProductDiscountCoupon|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NcProductDiscountCoupon|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
