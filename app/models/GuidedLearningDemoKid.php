<?php



class GuidedLearningDemoKid extends \Phalcon\Mvc\Model
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
    public $grade_id;

    /**
     *
     * @var integer
     */
    public $day_id;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("guided_learning_demo_kid");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningDemoKid[]|GuidedLearningDemoKid|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningDemoKid|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
