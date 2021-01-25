<?php



class GameAudioRecord extends \Phalcon\Mvc\Model
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
    public $slide;

    /**
     *
     * @var string
     */
    public $status;

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
        $this->setSource("game_audio_record");
        $this->belongsTo('child_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
        $this->belongsTo('game_id', '\GamesDatabase', 'id', ['alias' => 'GamesDatabase']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameAudioRecord[]|GameAudioRecord|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameAudioRecord|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
