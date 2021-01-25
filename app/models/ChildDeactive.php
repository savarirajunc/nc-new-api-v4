<?php



class ChildDeactive extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $elaboration;

    /**
     *
     * @var integer
     */
    public $users_id;

    /**
     *
     * @var integer
     */
    public $why_are_you_leaving_id;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("child_deactive");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ChildDeactive[]|ChildDeactive|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ChildDeactive|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
