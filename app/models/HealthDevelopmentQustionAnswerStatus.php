<?php



class HealthDevelopmentQustionAnswerStatus extends \Phalcon\Mvc\Model
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
    public $day_id;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $status;

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
        $this->setSource("health_development_qustion_answer_status");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevelopmentQustionAnswerStatus[]|HealthDevelopmentQustionAnswerStatus|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevelopmentQustionAnswerStatus|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
