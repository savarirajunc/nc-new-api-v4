<?php



class KidsGamesStatus extends \Phalcon\Mvc\Model
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
    public $session_id;

    /**
     *
     * @var integer
     */
    public $game_id;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var integer
     */
    public $current_status;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     *
     * @var string
     */
    public $created_it;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("kids_games_status");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return KidsGamesStatus[]|KidsGamesStatus|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return KidsGamesStatus|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
