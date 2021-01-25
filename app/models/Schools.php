<?php



class Schools extends \Phalcon\Mvc\Model
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
    public $school_name;

    /**
     *
     * @var string
     */
    public $access_key;

    /**
     *
     * @var integer
     */
    public $status;

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
     * @var integer
     */
    public $town_city;

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
    public $seating_temp;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $modified_at;

    /**
     *
     * @var string
     */
    public $phone_number;

    /**
     *
     * @var string
     */
    public $principal_name;

    /**
     *
     * @var string
     */
    public $registration_number;

    /**
     *
     * @var string
     */
    public $principal_mobile;

    /**
     *
     * @var string
     */
    public $principal_email;

    /**
     *
     * @var integer
     */
    public $board_of_education;

    /**
     *
     * @var string
     */
    public $block;

    /**
     *
     * @var integer
     */
    public $post_code;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("schools");
        $this->hasMany('id', 'KidSchoolMapping', 'schools_id', ['alias' => 'KidSchoolMapping']);
        $this->hasMany('id', 'SchoolUsersMapping', 'schools_id', ['alias' => 'SchoolUsersMapping']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Schools[]|Schools|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Schools|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
