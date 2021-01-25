<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class SalesmanAppointment extends \Phalcon\Mvc\Model
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
    public $email;

    /**
     *
     * @var string
     */
    public $mno;

    /**
     *
     * @var integer
     */
    public $information_session_type;

    /**
     *
     * @var string
     */
    public $parent_role;

    /**
     *
     * @var string
     */
    public $company;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $dob;

    /**
     *
     * @var string
     */
    public $gender;

    /**
     *
     * @var integer
     */
    public $grade;

    /**
     *
     * @var string
     */
    public $who_intrest;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $day_id;

    /**
     *
     * @var integer
     */
    public $officer_id;

    /**
     *
     * @var string
     */
    public $choose_date;

    /**
     *
     * @var string
     */
    public $nots;

    /**
     *
     * @var string
     */
    public $meeting_link;

    /**
     *
     * @var integer
     */
    public $meeting_status;

    /**
     *
     * @var string
     */
    public $status;

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
        $this->setSource("salesman_appointment");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesmanAppointment[]|SalesmanAppointment|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesmanAppointment|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
