<?php



class NidaraKidLanguageInfo extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $language;

    /**
     *
     * @var string
     */
    public $location;

    /**
     *
     * @var string
     */
    public $child_understand_english;

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
        $this->setSource("nidara_kid_language_info");
        $this->belongsTo('nidara_kid_profile_id', '\NidaraKidProfile', 'id', ['alias' => 'NidaraKidProfile']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidLanguageInfo[]|NidaraKidLanguageInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraKidLanguageInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
