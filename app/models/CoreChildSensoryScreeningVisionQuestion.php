<?php



class CoreChildSensoryScreeningVisionQuestion extends \Phalcon\Mvc\Model
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
    public $question;

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
        $this->setSource("core_child_sensory_screening_vision_question");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreChildSensoryScreeningVisionQuestion[]|CoreChildSensoryScreeningVisionQuestion|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreChildSensoryScreeningVisionQuestion|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
