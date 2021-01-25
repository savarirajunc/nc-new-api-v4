<?php



class Answers extends \Phalcon\Mvc\Model
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
    public $questions_id;

    /**
     *
     * @var string
     */
    public $session_id;

    /**
     *
     * @var string
     */
    public $is_correct;

    /**
     *
     * @var integer
     */
    public $options_id;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $modified_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("answers");
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
        $this->belongsTo('options_id', '\Options', 'id', ['alias' => 'Options']);
        $this->belongsTo('questions_id', '\Questions', 'id', ['alias' => 'Questions']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Answers[]|Answers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Answers|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
