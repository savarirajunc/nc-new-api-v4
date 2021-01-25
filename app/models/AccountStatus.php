<?php



class AccountStatus extends \Phalcon\Mvc\Model
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("account_status");
        $this->belongsTo('users_id', '\Users', 'id', ['alias' => 'Users']);
        $this->belongsTo('why_are_you_leaving_id', '\WhyAreYouLeaving', 'id', ['alias' => 'WhyAreYouLeaving']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AccountStatus[]|AccountStatus|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AccountStatus|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
