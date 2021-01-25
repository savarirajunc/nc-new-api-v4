<?php



class DailyScheduler extends \Phalcon\Mvc\Model
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
    public $from_time;

    /**
     *
     * @var string
     */
    public $to_time;

    /**
     *
     * @var string
     */
    public $reminder;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

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
        $this->setSource("daily_scheduler");
        $this->hasMany('id', 'DailySchedulerDaysMap', 'daily_scheduler_id', ['alias' => 'DailySchedulerDaysMap']);
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyScheduler[]|DailyScheduler|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyScheduler|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
