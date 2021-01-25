<?php



class ClassSubjectMap extends \Phalcon\Mvc\Model
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
    public $classes_id;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("class_subject_map");
        $this->belongsTo('classes_id', '\Classes', 'id', ['alias' => 'Classes']);
        $this->belongsTo('subject_id', '\Subject', 'id', ['alias' => 'Subject']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClassSubjectMap[]|ClassSubjectMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClassSubjectMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
