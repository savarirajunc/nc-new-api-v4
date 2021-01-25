<?php



class HealthDevelopmentCat extends \Phalcon\Mvc\Model
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
    public $health_dev_cat;

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
        $this->setSource("health_development_cat");
        $this->hasMany('id', 'HealthDevQuseDayMap', 'heth_cat', ['alias' => 'HealthDevQuseDayMap']);
        $this->hasMany('id', 'HealthDevelopmentQustion', 'heth_cat', ['alias' => 'HealthDevelopmentQustion']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevelopmentCat[]|HealthDevelopmentCat|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return HealthDevelopmentCat|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
