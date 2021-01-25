<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class UsersAabackup extends \Phalcon\Mvc\Model
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
    public $parent_type;

    /**
     *
     * @var string
     */
    public $user_type;

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
    public $photo;

    /**
     *
     * @var string
     */
    public $occupation;

    /**
     *
     * @var string
     */
    public $company_name;

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
     * @var integer
     */
    public $country_of_residence;

    /**
     *
     * @var integer
     */
    public $country_of_citizenship;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $act_status;

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
        $this->setSource("users_aabackup");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersAabackup[]|UsersAabackup|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UsersAabackup|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
