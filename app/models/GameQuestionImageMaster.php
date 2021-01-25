<?php



class GameQuestionImageMaster extends \Phalcon\Mvc\Model
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
    public $ref_game_id;

    /**
     *
     * @var string
     */
    public $object_name;

    /**
     *
     * @var string
     */
    public $image_name;

    /**
     *
     * @var integer
     */
    public $tina;

    /**
     *
     * @var integer
     */
    public $rahul;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("game_question_image_master");
        $this->belongsTo('ref_game_id', '\GamesDatabase', 'id', ['alias' => 'GamesDatabase']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameQuestionImageMaster[]|GameQuestionImageMaster|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameQuestionImageMaster|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
