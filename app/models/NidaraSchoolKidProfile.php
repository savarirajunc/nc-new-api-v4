<?php


class NidaraSchoolKidProfile extends \Phalcon\Mvc\Model
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
    public $ncs_id;

    /**
     *
     * @var string
     */
    public $first_name;

    /**
     *
     * @var string
     */
    public $middle_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $date_of_birth;

    /**
     *
     * @var integer
     */
    public $age;

    /**
     *
     * @var string
     */
    public $gender;

    /**
     *
     * @var integer
     */
    public $height;

    /**
     *
     * @var integer
     */
    public $weight;

    /**
     *
     * @var string
     */
    public $grade;

    /**
     *
     * @var string
     */
    public $child_photo;

    /**
     *
     * @var string
     */
    public $child_avatar;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $expiry_date;

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
    public $status;

    /**
     *
     * @var string
     */
    public $cancel_subscription;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nidara_school_kid_profile");
        $this->hasMany('id', 'KidSchoolMapping', 'nidara_kid_profile_id', ['alias' => 'KidSchoolMapping']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraSchoolKidProfile[]|NidaraSchoolKidProfile|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraSchoolKidProfile|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
