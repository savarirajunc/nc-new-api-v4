<?php
use Phalcon\Tag;
class KidsIdGenerators extends Tag
{

    /**
     *
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $idgen = new KidsIdGenerator();
	    $idgen->created_for=$created_for;
	    $idgen->created_at=date('Y-m-d H:i:s');
 	    $idgen->save();
            return $idgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
