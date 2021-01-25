<?php



class SalesOfficerExtInfo extends \Phalcon\Mvc\Model
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $center_id;

    /**
     *
     * @var string
     */
    public $qualifications;

    /**
     *
     * @var string
     */
    public $experience;

    /**
     *
     * @var string
     */
    public $create_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("sales_officer_ext_info");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesOfficerExtInfo[]|SalesOfficerExtInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SalesOfficerExtInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
