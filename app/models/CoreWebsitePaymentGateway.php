<?php



class CoreWebsitePaymentGateway extends \Phalcon\Mvc\Model
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
    public $accesscode;

    /**
     *
     * @var string
     */
    public $workingcode;

    /**
     *
     * @var string
     */
    public $merchentid;

    /**
     *
     * @var string
     */
    public $redirect_url;

    /**
     *
     * @var string
     */
    public $cancel_url;

    /**
     *
     * @var string
     */
    public $method;

    /**
     *
     * @var string
     */
    public $apiurl;

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
        $this->setSource("core_website_payment_gateway");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreWebsitePaymentGateway[]|CoreWebsitePaymentGateway|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreWebsitePaymentGateway|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
