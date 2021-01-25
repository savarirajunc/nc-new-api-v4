<?php



class GameColors extends \Phalcon\Mvc\Model
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
    public $day;

    /**
     *
     * @var string
     */
    public $background_color;

    /**
     *
     * @var string
     */
    public $gif;

    /**
     *
     * @var string
     */
    public $img;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("game_colors");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameColors[]|GameColors|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameColors|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
