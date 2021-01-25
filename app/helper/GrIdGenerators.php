<?php
use Phalcon\Tag;
class GrIdGenerators extends Tag
{

    /**
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
        $gradingreporting = new GrIdGenerator();
	    $gradingreporting->created_for=$created_for;
	    $gradingreporting->created_at=date('Y-m-d H:i:s');
 	    $gradingreporting->save();
            return $gradingreporting->id;	
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
