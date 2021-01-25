<?php
use Phalcon\Tag;
class GamesIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $gamesidgen = new GamesIdGenerator();
	    $gamesidgen->created_for=$created_for;
	    $gamesidgen->created_at=date('Y-m-d H:i:s');
 	    $gamesidgen->save();
            return $gamesidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
