<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class SalesmanRequestForm extends \Phalcon\Mvc\Model
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
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $email;

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
     * @var integer
     */
    public $pincode;

    /**
     *
     * @var integer
     */
    public $stage;

    /**
     *
     * @var integer
     */
    public $termsagree;

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
        $this->setSource("salesman_request_form");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesmanRequestForm[]|SalesmanRequestForm|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesmanRequestForm|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
