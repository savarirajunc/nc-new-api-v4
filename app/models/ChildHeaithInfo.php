<?php



class ChildHeaithInfo extends \Phalcon\Mvc\Model
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
    public $child_id;

    /**
     *
     * @var integer
     */
    public $height;

    /**
     *
     * @var integer
     */
    public $age;

    /**
     *
     * @var integer
     */
    public $weight;

    /**
     *
     * @var string
     */
    public $doctor_name;

    /**
     *
     * @var string
     */
    public $medical_concerns;

    /**
     *
     * @var integer
     */
    public $circumference;

    /**
     *
     * @var string
     */
    public $vision;

    /**
     *
     * @var string
     */
    public $hearing;

    /**
     *
     * @var string
     */
    public $oral_hygiene;

    /**
     *
     * @var string
     */
    public $breakfast;

    /**
     *
     * @var string
     */
    public $morning_snack;

    /**
     *
     * @var string
     */
    public $lunch;

    /**
     *
     * @var string
     */
    public $evening_snack;

    /**
     *
     * @var string
     */
    public $dinner;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("child_heaith_info");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ChildHeaithInfo[]|ChildHeaithInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ChildHeaithInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
