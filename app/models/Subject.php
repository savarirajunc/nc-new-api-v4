<?php



class Subject extends \Phalcon\Mvc\Model
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
    public $subject_name;

    /**
     *
     * @var integer
     */
    public $core_type;

    /**
     *
     * @var string
     */
    public $description;

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
        $this->setSource("subject");
        $this->hasMany('id', 'ClassSubjectMap', 'subject_id', ['alias' => 'ClassSubjectMap']);
        $this->hasMany('id', 'CoreFrameworksSubjectMap', 'subject_id', ['alias' => 'CoreFrameworksSubjectMap']);
        $this->hasMany('id', 'GameCoreFrameMap', 'subject_id', ['alias' => 'GameCoreFrameMap']);
        $this->hasMany('id', 'GuidedLearningDayGameMap', 'subject_id', ['alias' => 'GuidedLearningDayGameMap']);
        $this->hasMany('id', 'HealthDevQuseDayMap', 'subject_id', ['alias' => 'HealthDevQuseDayMap']);
        $this->hasMany('id', 'HealthDevelopmentQustion', 'subject_id', ['alias' => 'HealthDevelopmentQustion']);
        $this->hasMany('id', 'StandardIndicatorsMap', 'subject_id', ['alias' => 'StandardIndicatorsMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subject[]|Subject|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subject|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
