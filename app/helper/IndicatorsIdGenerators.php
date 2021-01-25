<?php
use Phalcon\Tag;
class IndicatorsIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
        $indicatorsidgen = new IndicatorsIdGenerator();
	    $indicatorsidgen->created_for=$created_for;
	    $indicatorsidgen->created_at=date('Y-m-d H:i:s');
 	    $indicatorsidgen->save();
            return $indicatorsidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
