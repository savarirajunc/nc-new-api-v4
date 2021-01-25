<?php



class NidaraKidSchoolInfo extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $school_name;

    /**
     *
     * @var string
     */
    public $school_type;

    /**
     *
     * @var string
     */
    public $address2;

    /**
     *
     * @var string
     */
    public $town_city;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var string
     */
    public $country;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("nidara_kid_school_info");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidSchoolInfo[]|NidaraKidSchoolInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidSchoolInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
