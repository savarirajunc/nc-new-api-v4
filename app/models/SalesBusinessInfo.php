<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class SalesBusinessInfo extends \Phalcon\Mvc\Model
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
    public $company_name;

    /**
     *
     * @var string
     */
    public $owner_name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $pan_number;

    /**
     *
     * @var string
     */
    public $gst_number;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $address_1;

    /**
     *
     * @var string
     */
    public $landmark;

    /**
     *
     * @var integer
     */
    public $country;

    /**
     *
     * @var integer
     */
    public $state;

    /**
     *
     * @var integer
     */
    public $city;

    /**
     *
     * @var integer
     */
    public $post_code;

    /**
     *
     * @var integer
     */
    public $center_id;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("sales_business_info");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesBusinessInfo[]|SalesBusinessInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesBusinessInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
