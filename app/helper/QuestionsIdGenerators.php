<?php
use Phalcon\Tag;
class QuestionsIdGenerators extends Tag
{

    /**
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $questionidgen = new QuestionsIdGenerator();
	    $questionidgen->created_for=$created_for;
	    $questionidgen->created_at=date('Y-m-d H:i:s');
 	    $questionidgen->save();
            return $questionidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
