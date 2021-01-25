<?php



class GameHealthcareSlide extends \Phalcon\Mvc\Model
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
    public $core_health_name;

    /**
     *
     * @var string
     */
    public $core_health_definition;

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
        $this->setSource("game_healthcare_slide");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameHealthcareSlide[]|GameHealthcareSlide|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameHealthcareSlide|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
