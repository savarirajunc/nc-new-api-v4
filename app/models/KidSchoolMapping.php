<?php



class KidSchoolMapping extends \Phalcon\Mvc\Model
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
    public $nidara_kid_profile_id;

    /**
     *
     * @var integer
     */
    public $schools_id;

    /**
     *
     * @var integer
     */
    public $class_id;

    /**
     *
     * @var integer
     */
    public $sections_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("kid_school_mapping");
        $this->belongsTo('class_id', '\Classes', 'id', ['alias' => 'Classes']);
        $this->belongsTo('schools_id', '\Schools', 'id', ['alias' => 'Schools']);
        $this->belongsTo('sections_id', '\Sections', 'id', ['alias' => 'Sections']);
        $this->belongsTo('nidara_kid_profile_id', '\NidaraSchoolKidProfile', 'id', ['alias' => 'NidaraSchoolKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return KidSchoolMapping[]|KidSchoolMapping|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return KidSchoolMapping|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
