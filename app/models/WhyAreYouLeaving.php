<?php



class WhyAreYouLeaving extends \Phalcon\Mvc\Model
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
        $this->setSource("why_are_you_leaving");
        $this->hasMany('id', 'AccountStatus', 'why_are_you_leaving_id', ['alias' => 'AccountStatus']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return WhyAreYouLeaving[]|WhyAreYouLeaving|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return WhyAreYouLeaving|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
