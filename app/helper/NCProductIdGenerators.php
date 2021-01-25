<?php
use Phalcon\Tag;
class NCProductIdGenerators extends Tag
{

    /**
     * @param String $createdFor            
     * @return NULL
     */
    public function getNewId($created_for)
    {
        try {
            $ncproductidgen = new NCProductIdGenerator();
	    $ncproductidgen->created_for=$created_for;
	    $ncproductidgen->created_data=date('Y-m-d H:i:s');
 	    $ncproductidgen->save();
            return $ncproductidgen->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
