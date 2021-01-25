<?php



class GameSlideTime extends \Phalcon\Mvc\Model
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
    public $game_id;

    /**
     *
     * @var integer
     */
    public $child_id;

    /**
     *
     * @var integer
     */
    public $slide_number;

    /**
     *
     * @var integer
     */
    public $time_count;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("game_slide_time");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameSlideTime[]|GameSlideTime|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameSlideTime|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
