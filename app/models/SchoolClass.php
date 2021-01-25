<?php



class SchoolClass extends \Phalcon\Mvc\Model
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
    public $class_id;

    /**
     *
     * @var integer
     */
    public $school_id;

    /**
     *
     * @var integer
     */
    public $section_id;

    /**
     *
     * @var integer
     */
    public $kid_id;

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
        $this->setSource("school_class");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SchoolClass[]|SchoolClass|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SchoolClass|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
