<?php



class ClinicAddress extends \Phalcon\Mvc\Model
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
    public $clinic_name;

    /**
     *
     * @var string
     */
    public $practicing_time;

    /**
     *
     * @var string
     */
    public $practicing_time_to;

    /**
     *
     * @var string
     */
    public $street_address_1;

    /**
     *
     * @var string
     */
    public $street_address_2;

    /**
     *
     * @var string
     */
    public $city;

    /**
     *
     * @var string
     */
    public $state;

    /**
     *
     * @var string
     */
    public $country;

    /**
     *
     * @var integer
     */
    public $pin_code;

    /**
     *
     * @var string
     */
    public $secretary_name;

    /**
     *
     * @var string
     */
    public $secretary_email;

    /**
     *
     * @var string
     */
    public $secretary_mobile_no;

    /**
     *
     * @var string
     */
    public $any_software_for_appointment;

    /**
     *
     * @var integer
     */
    public $user_id;

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
        $this->setSource("clinic_address");
        $this->belongsTo('user_id', '\Users', 'id', ['alias' => 'Users']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClinicAddress[]|ClinicAddress|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ClinicAddress|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
