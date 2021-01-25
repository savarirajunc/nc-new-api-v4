<?php
use Phalcon\Tag;
class GuidedLearningIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
        $guidedlearningidgen = new GuidedLearningIdGenerator();
	    $guidedlearningidgen->created_for=$created_for;
	    $guidedlearningidgen->created_at=date('Y-m-d H:i:s');
 	    $guidedlearningidgen->save();
            return $guidedlearningidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
