<?php



class DailySchedulerDaysMap extends \Phalcon\Mvc\Model
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
    public $daily_scheduler_id;

    /**
     *
     * @var integer
     */
    public $scheduler_days_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("daily_scheduler_days_map");
        $this->belongsTo('daily_scheduler_id', '\DailyScheduler', 'id', ['alias' => 'DailyScheduler']);
        $this->belongsTo('scheduler_days_id', '\SchedulerDays', 'id', ['alias' => 'SchedulerDays']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailySchedulerDaysMap[]|DailySchedulerDaysMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailySchedulerDaysMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
