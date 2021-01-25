<?php



class DailyRoutineDayMap extends \Phalcon\Mvc\Model
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
    public $daily_routine_id;

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
        $this->setSource("daily_routine_day_map");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineDayMap[]|DailyRoutineDayMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyRoutineDayMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
