<?php



use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends \Phalcon\Mvc\Model
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
        $this->setSource("users");
        $this->hasMany('id', 'AccountStatus', 'users_id', ['alias' => 'AccountStatus']);
        $this->hasMany('id', 'ClinicAddress', 'user_id', ['alias' => 'ClinicAddress']);
        $this->hasMany('id', 'DoctorCode', 'user_id', ['alias' => 'DoctorCode']);
        $this->hasMany('id', 'DoctorComplete', 'user_id', ['alias' => 'DoctorComplete']);
        $this->hasMany('id', 'DoctorInfo', 'user_id', ['alias' => 'DoctorInfo']);
        $this->hasMany('id', 'DoctorParentMap', 'user_id', ['alias' => 'DoctorParentMap']);
        $this->hasMany('id', 'KidParentsMap', 'users_id', ['alias' => 'KidParentsMap']);
        $this->hasMany('id', 'NcOrderAmount', 'user_id', ['alias' => 'NcOrderAmount']);
        $this->hasMany('id', 'NidaraParentsAddressInfo', 'users_id', ['alias' => 'NidaraParentsAddressInfo']);
        $this->hasMany('id', 'ParentsMappingProfiles', 'primary_parents_id', ['alias' => 'ParentsMappingProfiles']);
        $this->hasMany('id', 'SchoolUsersMapping', 'users_id', ['alias' => 'SchoolUsersMapping']);
        $this->hasMany('id', 'TokenUsers', 'users_id', ['alias' => 'TokenUsers']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
