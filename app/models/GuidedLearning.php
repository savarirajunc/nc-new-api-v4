<?php



class GuidedLearning extends \Phalcon\Mvc\Model
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
    public $learning_model;

    /**
     *
     * @var string
     */
    public $learning_code;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $description;

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
        $this->setSource("guided_learning");
        $this->hasMany('id', 'GuidedLearningDayGameMap', 'day_guided_learning_id', ['alias' => 'GuidedLearningDayGameMap']);
        $this->hasMany('id', 'GuidedLearningSchedule', 'guided_learning_id', ['alias' => 'GuidedLearningSchedule']);
        $this->hasMany('id', 'KidGuidedLearningMap', 'guided_learning_id', ['alias' => 'KidGuidedLearningMap']);
        $this->hasMany('id', 'StandardIndicatorsMap', 'guided_id', ['alias' => 'StandardIndicatorsMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearning[]|GuidedLearning|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearning|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
