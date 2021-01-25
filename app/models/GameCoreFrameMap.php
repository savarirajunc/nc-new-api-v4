<?php



class GameCoreFrameMap extends \Phalcon\Mvc\Model
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
    public $gamecoretype;

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
     * @var integer
     */
    public $game_id;

    /**
     *
     * @var integer
     */
    public $standard_id;

    /**
     *
     * @var integer
     */
    public $indicator_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("game_core_frame_map");
        $this->belongsTo('framework_id', '\CoreFrameworks', 'id', ['alias' => 'CoreFrameworks']);
        $this->belongsTo('grade_id', '\Grade', 'id', ['alias' => 'Grade']);
        $this->belongsTo('indicator_id', '\Indicators', 'id', ['alias' => 'Indicators']);
        $this->belongsTo('standard_id', '\Standard', 'id', ['alias' => 'Standard']);
        $this->belongsTo('subject_id', '\Subject', 'id', ['alias' => 'Subject']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameCoreFrameMap[]|GameCoreFrameMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GameCoreFrameMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
