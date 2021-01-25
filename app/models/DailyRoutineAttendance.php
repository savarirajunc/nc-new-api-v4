<?php



class DailyRoutineAttendance extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $start_time;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     *
     * @var string
     */
    public $attendanceDate;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("daily_routine_attendance");
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineAttendance[]|DailyRoutineAttendance|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineAttendance|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
