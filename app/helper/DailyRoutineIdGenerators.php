<?php
use Phalcon\Tag;
class DailyRoutineIdGenerators extends Tag
{

    /**
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $dailyroutineidgen = new DailyroutingIdGenerator();
	    $dailyroutineidgen->created_for=$created_for;
	    $dailyroutineidgen->created_at=date('Y-m-d H:i:s');
 	    $dailyroutineidgen->save();
            return $dailyroutineidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
