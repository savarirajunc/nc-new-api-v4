<?php



class SalesCenterOpenClose extends \Phalcon\Mvc\Model
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
    public $center_id;

    /**
     *
     * @var integer
     */
    public $day_id;

    /**
     *
     * @var string
     */
    public $open_time;

    /**
     *
     * @var string
     */
    public $close_time;

    /**
     *
     * @var integer
     */
    public $day_status;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("sales_center_open_close");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesCenterOpenClose[]|SalesCenterOpenClose|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesCenterOpenClose|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
