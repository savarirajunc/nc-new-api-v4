<?php



class CoreFrameworksSubjectMap extends \Phalcon\Mvc\Model
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
    public $core_framework_id;

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
        $this->setSource("core_frameworks_subject_map");
        $this->belongsTo('core_framework_id', '\CoreFrameworks', 'id', ['alias' => 'CoreFrameworks']);
        $this->belongsTo('subject_id', '\Subject', 'id', ['alias' => 'Subject']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreFrameworksSubjectMap[]|CoreFrameworksSubjectMap|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CoreFrameworksSubjectMap|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
