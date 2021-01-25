<?php



class SalesmanParentQuery extends \Phalcon\Mvc\Model
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
    public $dev_off_id;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var integer
     */
    public $question;

    /**
     *
     * @var string
     */
    public $query_string;

    /**
     *
     * @var string
     */
    public $query_answer;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $close_time;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("salesman_parent_query");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesmanParentQuery[]|SalesmanParentQuery|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesmanParentQuery|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
