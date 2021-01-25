<?php



class NidaraParentsAddressInfo extends \Phalcon\Mvc\Model
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
    public $address_type;

    /**
     *
     * @var integer
     */
    public $users_id;

    /**
     *
     * @var string
     */
    public $address1;

    /**
     *
     * @var string
     */
    public $address2;

    /**
     *
     * @var string
     */
    public $town_city;

    /**
     *
     * @var integer
     */
    public $pincode;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $modified_at;

    /**
     *
     * @var string
     */
    public $is_billing_same;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nidara_parents_address_info");
        $this->belongsTo('users_id', '\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraParentsAddressInfo[]|NidaraParentsAddressInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraParentsAddressInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
