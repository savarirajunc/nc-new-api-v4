<?php



class ParentsMappingProfiles extends \Phalcon\Mvc\Model
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
    public $primary_parents_id;

    /**
     *
     * @var string
     */
    public $primary_parent_type;

    /**
     *
     * @var integer
     */
    public $secondary_parent_id;

    /**
     *
     * @var string
     */
    public $secondary_parent_type;

    /**
     *
     * @var string
     */
    public $parent_photo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("parents_mapping_profiles");
        $this->belongsTo('primary_parents_id', '\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ParentsMappingProfiles[]|ParentsMappingProfiles|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ParentsMappingProfiles|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
