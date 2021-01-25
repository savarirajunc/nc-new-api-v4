<?php



class DailyRoutineToday extends \Phalcon\Mvc\Model
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
    public $set_time;

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
        $this->setSource("daily_routine_today");
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineToday[]|DailyRoutineToday|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineToday|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
