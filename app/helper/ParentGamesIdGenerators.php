<?php
use Phalcon\Tag;
class ParentGamesIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
        $parentgamesidgen = new ParentGamesIdGenerator();
	    $parentgamesidgen->created_for=$created_for;
	    $parentgamesidgen->created_at=date('Y-m-d H:i:s');
 	    $parentgamesidgen->save();
            return $parentgamesidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
