<?php
use Phalcon\Tag;
class UsersIdGenerators extends Tag
{

    /**
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $usersidgen = new UsersIdGenerator();
	    $usersidgen->created_for=$created_for;
	    $usersidgen->created_at=date('Y-m-d H:i:s');
 	    $usersidgen->save();
            return $usersidgen->id;	
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
