<?php



class Classes extends \Phalcon\Mvc\Model
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
    public $class_name;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $modified_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("classes");
        $this->hasMany('id', 'ClassSubjectMap', 'classes_id', ['alias' => 'ClassSubjectMap']);
        $this->hasMany('id', 'KidSchoolMapping', 'class_id', ['alias' => 'KidSchoolMapping']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Classes[]|Classes|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Classes|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
