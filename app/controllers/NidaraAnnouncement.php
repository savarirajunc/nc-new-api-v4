<?php

class NidaraAnnouncement extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
	 
	 public $start_date;
    
    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
	 
	 
    public $end_date;
	
	/**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
	 
	 public $title;
    
    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
	 
	 
    public $messages;
    
    
	/**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $created_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("nidara_private_school");
		
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'nidara_announcement';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyScheduler[]|DailyScheduler|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DailyScheduler|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
