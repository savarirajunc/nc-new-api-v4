<?php
use Phalcon\Tag;
class ExamsIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $examsidgen = new ExamsIdGenerator();
	    $examsidgen->created_for=$created_for;
	    $examsidgen->created_at=date('Y-m-d H:i:s');
 	    $examsidgen->save();
            return $examsidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
