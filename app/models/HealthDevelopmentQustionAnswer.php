<?php



class HealthDevelopmentQustionAnswer extends \Phalcon\Mvc\Model
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
    public $parent_question_id;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $parent_answer;

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
        $this->setSource("health_development_qustion_answer");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevelopmentQustionAnswer[]|HealthDevelopmentQustionAnswer|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevelopmentQustionAnswer|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
