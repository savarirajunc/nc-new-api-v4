<?php



class HealthDevQuseDayMap extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $heth_cat;

    /**
     *
     * @var integer
     */
    public $day_id;

    /**
     *
     * @var integer
     */
    public $question_id;

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
        $this->setSource("health_dev_quse_day_map");
        $this->belongsTo('framework_id', '\CoreFrameworks', 'id', ['alias' => 'CoreFrameworks']);
        $this->belongsTo('grade_id', '\Grade', 'id', ['alias' => 'Grade']);
        $this->belongsTo('heth_cat', '\HealthDevelopmentCat', 'id', ['alias' => 'HealthDevelopmentCat']);
        $this->belongsTo('subject_id', '\Subject', 'id', ['alias' => 'Subject']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevQuseDayMap[]|HealthDevQuseDayMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevQuseDayMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
