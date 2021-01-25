<?php



class GuidedLearningSchedule extends \Phalcon\Mvc\Model
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
    public $schedule_identified;

    /**
     *
     * @var integer
     */
    public $order_by;

    /**
     *
     * @var integer
     */
    public $guided_learning_id;

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
        $this->setSource("guided_learning_schedule");
        $this->belongsTo('guided_learning_id', '\GuidedLearning', 'id', ['alias' => 'GuidedLearning']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningSchedule[]|GuidedLearningSchedule|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningSchedule|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
