<?php



class DailyRoutineSubject extends \Phalcon\Mvc\Model
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
    public $kid_id;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var string
     */
    public $start_time;

    /**
     *
     * @var string
     */
    public $end_time;

    /**
     *
     * @var string
     */
    public $play_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("daily_routine_subject");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineSubject[]|DailyRoutineSubject|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineSubject|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
