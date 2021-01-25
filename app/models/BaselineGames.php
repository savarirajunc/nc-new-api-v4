<?php



class BaselineGames extends \Phalcon\Mvc\Model
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
    public $framework_id;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var string
     */
    public $game_name;

    /**
     *
     * @var string
     */
    public $game_path;

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
        $this->setSource("baseline_games");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BaselineGames[]|BaselineGames|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BaselineGames|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
