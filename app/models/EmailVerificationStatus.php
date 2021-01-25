<?php



class EmailVerificationStatus extends \Phalcon\Mvc\Model
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
    public $encrypt_code;

    /**
     *
     * @var string
     */
    public $email_id;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_date;

    /**
     *
     * @var string
     */
    public $modify;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
        $this->setSource("email_verification_status");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailVerificationStatus[]|EmailVerificationStatus|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EmailVerificationStatus|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
