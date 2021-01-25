<?php



class GrType extends \Phalcon\Mvc\Model
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
    public $gr_framework_id;

    /**
     *
     * @var string
     */
    public $type_name;

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
        $this->setSource("gr_type");
        $this->belongsTo('gr_framework_id', '\GrFramework', 'id', ['alias' => 'GrFramework']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GrType[]|GrType|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GrType|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
