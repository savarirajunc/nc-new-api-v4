<?php



class KidGuidedLearningMapAabackup extends \Phalcon\Mvc\Model
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
    public $guided_learning_id;

    /**
     *
     * @var integer
     */
    public $nidara_kid_profile_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("kid_guided_learning_map_aabackup");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return KidGuidedLearningMapAabackup[]|KidGuidedLearningMapAabackup|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return KidGuidedLearningMapAabackup|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
