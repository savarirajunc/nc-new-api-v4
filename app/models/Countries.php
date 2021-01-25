<?php



class Countries extends \Phalcon\Mvc\Model
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
    public $sortname;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $phonecode;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("countries");
        $this->hasMany('id', 'State', 'country_id', ['alias' => 'State']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Countries[]|Countries|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Countries|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
