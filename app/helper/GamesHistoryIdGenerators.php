<?php
use Phalcon\Tag;
class GamesHistoryIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $gameshistoryidgen = new GamesHistoryIdGenerator();
	    $gameshistoryidgen->created_for=$created_for;
	    $gameshistoryidgen->created_at=date('Y-m-d H:i:s');
 	    $gameshistoryidgen->save();
            return $gameshistoryidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
