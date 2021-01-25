<?php



class UserAzureAdInfo extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $ad_id;

    /**
     *
     * @var string
     */
    public $user_name;

    /**
     *
     * @var integer
     */
    public $status;

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
        $this->setSource("user_azure_ad_info");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserAzureAdInfo[]|UserAzureAdInfo|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserAzureAdInfo|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
