<?php



class CoreFrameworks extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var integer
     */
    public $status;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("core_frameworks");
        $this->hasMany('id', 'CoreFrameworksSubjectMap', 'core_framework_id', ['alias' => 'CoreFrameworksSubjectMap']);
        $this->hasMany('id', 'GameCoreFrameMap', 'framework_id', ['alias' => 'GameCoreFrameMap']);
        $this->hasMany('id', 'GrMainReporting', 'gr_type_id', ['alias' => 'GrMainReporting']);
        $this->hasMany('id', 'GuidedLearningDayGameMap', 'framework_id', ['alias' => 'GuidedLearningDayGameMap']);
        $this->hasMany('id', 'HealthDevQuseDayMap', 'framework_id', ['alias' => 'HealthDevQuseDayMap']);
        $this->hasMany('id', 'HealthDevelopmentQustion', 'framework_id', ['alias' => 'HealthDevelopmentQustion']);
        $this->hasMany('id', 'StandardIndicatorsMap', 'coreframwor_id', ['alias' => 'StandardIndicatorsMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreFrameworks[]|CoreFrameworks|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreFrameworks|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
