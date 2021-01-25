<?php
use Phalcon\Tag;
class ParentsIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $parentsidgen = new ParentsIdGenerator();
	    $parentsidgen->created_for=$created_for;
	    $parentsidgen->created_at=date('Y-m-d H:i:s');
 	    $parentsidgen->save();
            return $parentsidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
