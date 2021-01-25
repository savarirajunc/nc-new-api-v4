<?php



class GuidedLearningTestUser extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $month;

    /**
     *
     * @var integer
     */
    public $week;

    /**
     *
     * @var integer
     */
    public $kid_id;

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
        $this->setSource("guided_learning_test_user");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningTestUser[]|GuidedLearningTestUser|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GuidedLearningTestUser|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
