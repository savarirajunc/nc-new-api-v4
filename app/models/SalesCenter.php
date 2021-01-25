<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class SalesCenter extends \Phalcon\Mvc\Model
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
    public $address_1;

    /**
     *
     * @var string
     */
    public $address_2;

    /**
     *
     * @var string
     */
    public $landmark;

    /**
     *
     * @var integer
     */
    public $city;

    /**
     *
     * @var integer
     */
    public $state;

    /**
     *
     * @var integer
     */
    public $country;

    /**
     *
     * @var string
     */
    public $open_time;

    /**
     *
     * @var string
     */
    public $close_time;

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
    public $a_mobile;

    /**
     *
     * @var string
     */
    public $center_overview;

    /**
     *
     * @var integer
     */
    public $post_code;

    /**
     *
     * @var string
     */
    public $center_type;

    /**
     *
     * @var string
     */
    public $created_at;

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
        $this->setSource("sales_center");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesCenter[]|SalesCenter|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesCenter|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
