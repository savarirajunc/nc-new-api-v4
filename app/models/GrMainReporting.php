<?php



class GrMainReporting extends \Phalcon\Mvc\Model
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
    public $gr_framwork_id;

    /**
     *
     * @var integer
     */
    public $gr_type_id;

    /**
     *
     * @var integer
     */
    public $subject_id;

    /**
     *
     * @var integer
     */
    public $gr_frame_type;

    /**
     *
     * @var string
     */
    public $add_grade;

    /**
     *
     * @var string
     */
    public $add_grade_range_min;

    /**
     *
     * @var string
     */
    public $add_grade_range_max;

    /**
     *
     * @var string
     */
    public $add_proficiency_level;

    /**
     *
     * @var string
     */
    public $add_definition;

    /**
     *
     * @var string
     */
    public $add_does_id_mean;

    /**
     *
     * @var string
     */
    public $add_nidara_recommendation;

    /**
     *
     * @var string
     */
    public $add_color;

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
        $this->setSource("gr_main_reporting");
        $this->belongsTo('gr_framwork_id', '\Grade', 'id', ['alias' => 'Grade']);
        $this->belongsTo('gr_type_id', '\CoreFrameworks', 'id', ['alias' => 'CoreFrameworks']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return GrMainReporting[]|GrMainReporting|\Phalcon\Mvc\Model\ResultSetInterface
     */
    
    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return GrMainReporting|\Phalcon\Mvc\Model\ResultInterface
     */
    

}
