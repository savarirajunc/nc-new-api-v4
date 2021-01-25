<?php



class StandardIndicatorsMap extends \Phalcon\Mvc\Model
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
    public $guided_id;

    /**
     *
     * @var integer
     */
    public $coreframwor_id;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $standard_id;

    /**
     *
     * @var integer
     */
    public $indicators_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("standard_indicators_map");
        $this->belongsTo('coreframwor_id', '\CoreFrameworks', 'id', ['alias' => 'CoreFrameworks']);
        $this->belongsTo('guided_id', '\GuidedLearning', 'id', ['alias' => 'GuidedLearning']);
        $this->belongsTo('indicators_id', '\Indicators', 'id', ['alias' => 'Indicators']);
        $this->belongsTo('standard_id', '\Standard', 'id', ['alias' => 'Standard']);
        $this->belongsTo('subject_id', '\Subject', 'id', ['alias' => 'Subject']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return StandardIndicatorsMap[]|StandardIndicatorsMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return StandardIndicatorsMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
