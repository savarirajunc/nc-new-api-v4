<?php



class NidaraAnnouncement extends \Phalcon\Mvc\Model
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
    public $start_date;

    /**
     *
     * @var string
     */
    public $end_date;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $messages;

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
        $this->setSource("nidara_announcement");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraAnnouncement[]|NidaraAnnouncement|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NidaraAnnouncement|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
