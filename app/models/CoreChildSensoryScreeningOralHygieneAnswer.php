<?php



class CoreChildSensoryScreeningOralHygieneAnswer extends \Phalcon\Mvc\Model
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
    public $child_id;

    /**
     *
     * @var integer
     */
    public $question_id;

    /**
     *
     * @var string
     */
    public $answer;

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
        $this->setSource("core_child_sensory_screening_oral_hygiene_answer");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreChildSensoryScreeningOralHygieneAnswer[]|CoreChildSensoryScreeningOralHygieneAnswer|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreChildSensoryScreeningOralHygieneAnswer|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
