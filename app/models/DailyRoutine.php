<?php



class DailyRoutine extends \Phalcon\Mvc\Model
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
    public $task_name;

    /**
     *
     * @var integer
     */
    public $session_for;

    /**
     *
     * @var string
     */
    public $repeatday;

    /**
     *
     * @var string
     */
    public $reminder;

    /**
     *
     * @var string
     */
    public $set_time;

    /**
     *
     * @var string
     */
    public $end_time;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $createdDate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("daily_routine");
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutine[]|DailyRoutine|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutine|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
