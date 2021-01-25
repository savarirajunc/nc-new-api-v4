<?php



class NidaraKidProfileAabackup extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $choose_time;

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
    public $board_of_education;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $admission_status;

    /**
     *
     * @var integer
     */
    public $free_trial;

    /**
     *
     * @var string
     */
    public $order_id;

    /**
     *
     * @var string
     */
    public $cancel_subscription;

    /**
     *
     * @var integer
     */
    public $relationship_to_child;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nidara_kid_profile_aabackup");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidProfileAabackup[]|NidaraKidProfileAabackup|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidProfileAabackup|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
