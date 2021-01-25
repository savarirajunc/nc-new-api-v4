<?php



class SchedulerDays extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $status;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("scheduler_days");
        $this->hasMany('id', 'DailySchedulerDaysMap', 'scheduler_days_id', ['alias' => 'DailySchedulerDaysMap']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SchedulerDays[]|SchedulerDays|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SchedulerDays|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
