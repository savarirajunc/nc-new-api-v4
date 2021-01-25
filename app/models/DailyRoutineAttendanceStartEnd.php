<?php



class DailyRoutineAttendanceStartEnd extends \Phalcon\Mvc\Model
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
    public $child_id;

    /**
     *
     * @var integer
     */
    public $start_end;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $session_id;

    /**
     *
     * @var string
     */
    public $create_in;

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
        $this->setSource("daily_routine_attendance_start_end");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineAttendanceStartEnd[]|DailyRoutineAttendanceStartEnd|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineAttendanceStartEnd|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
