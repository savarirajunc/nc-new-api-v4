<?php



class SuperadminMessage extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var string
     */
    public $role;

    /**
     *
     * @var string
     */
    public $message;

    /**
     *
     * @var string
     */
    public $enable_from;

    /**
     *
     * @var string
     */
    public $enable_to;

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
        $this->setSource("superadmin_message");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SuperadminMessage[]|SuperadminMessage|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SuperadminMessage|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
