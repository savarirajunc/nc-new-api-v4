<?php



class Grade extends \Phalcon\Mvc\Model
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
    public $grade_name;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $order_value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("grade");
        $this->hasMany('id', 'GameCoreFrameMap', 'grade_id', ['alias' => 'GameCoreFrameMap']);
        $this->hasMany('id', 'GrMainReporting', 'gr_framwork_id', ['alias' => 'GrMainReporting']);
        $this->hasMany('id', 'HealthDevQuseDayMap', 'grade_id', ['alias' => 'HealthDevQuseDayMap']);
        $this->hasMany('id', 'HealthDevelopmentQustion', 'grade_id', ['alias' => 'HealthDevelopmentQustion']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Grade[]|Grade|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Grade|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
