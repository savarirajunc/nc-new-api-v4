<?php



class NidaraKidProfile extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $birthterm;

    /**
     *
     * @var integer
     */
    public $birthweek;

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
    public $test_kid_status;

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
        $this->setSource("nidara_kid_profile");
        $this->hasMany('id', 'Answers', 'nidara_kid_profile_id', ['alias' => 'Answers']);
        $this->hasMany('id', 'DailyRoutine', 'nidara_kid_profile_id', ['alias' => 'DailyRoutine']);
        $this->hasMany('id', 'DailyRoutineAttendance', 'nidara_kid_profile_id', ['alias' => 'DailyRoutineAttendance']);
        $this->hasMany('id', 'DailyRoutineToday', 'nidara_kid_profile_id', ['alias' => 'DailyRoutineToday']);
        $this->hasMany('id', 'DailyScheduler', 'nidara_kid_profile_id', ['alias' => 'DailyScheduler']);
        $this->hasMany('id', 'DoctorVisit', 'child_id', ['alias' => 'DoctorVisit']);
        $this->hasMany('id', 'GameAnswers', 'nidara_kid_profile_id', ['alias' => 'GameAnswers']);
        $this->hasMany('id', 'GameAudioRecord', 'child_id', ['alias' => 'GameAudioRecord']);
        $this->hasMany('id', 'KidGuidedLearningMap', 'nidara_kid_profile_id', ['alias' => 'KidGuidedLearningMap']);
        $this->hasMany('id', 'KidParentsMap', 'nidara_kid_profile_id', ['alias' => 'KidParentsMap']);
        $this->hasMany('id', 'NidaraKidCaregiverInfo', 'nidara_kid_profile_id', ['alias' => 'NidaraKidCaregiverInfo']);
        $this->hasMany('id', 'NidaraKidFamilyInfo', 'nidara_kid_profile_id', ['alias' => 'NidaraKidFamilyInfo']);
        $this->hasMany('id', 'NidaraKidFriendsInfo', 'nidara_kid_profile_id', ['alias' => 'NidaraKidFriendsInfo']);
        $this->hasMany('id', 'NidaraKidLanguageInfo', 'nidara_kid_profile_id', ['alias' => 'NidaraKidLanguageInfo']);
        $this->hasMany('id', 'NidaraKidPhysicalInfo', 'nidara_kid_profile_id', ['alias' => 'NidaraKidPhysicalInfo']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidProfile[]|NidaraKidProfile|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidProfile|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
