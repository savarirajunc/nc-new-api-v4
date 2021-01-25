<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
class ProductSelectProgramController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	/**
	 * Fetch all Record from database :-
	 */

/*
getproductinfobypid : this service used to get the Product Information
Input : {"user_id":2434}
Tables :
1.NidaraKidProfile
2.NCProduct
*/

public function productsave()
	{

$input_data = $this->request->getJsonRawBody ();
		

$saveproductval = $this->modelsManager->createBuilder ()->columns ( array (
            'ProductSelectProgram.id as pid',
              ) )->from ('ProductSelectProgram')
           ->inwhere('ProductSelectProgram.users_id',array($input_data -> users_id))
           ->inwhere('ProductSelectProgram.kid_id',array($input_data -> kid_id))
                ->getQuery ()->execute ();



if(count($saveproductval) == 0 )
{
$saveproduct=new ProductSelectProgram();
}
else
{
$saveproduct=ProductSelectProgram::findFirstByid($saveproductval[0] -> pid);

}


$saveproduct-> users_id = $input_data -> users_id;
$saveproduct-> kid_id = $input_data -> kid_id;
$saveproduct-> select_program = $input_data -> select_program;

if($saveproduct -> save())
{
return $this->response->setJsonContent ( [ 
					'status' => true,
					'productInfo' =>"Data Saved Successfully",
			] );

}
else
{
return $this->response->setJsonContent ( [ 
					'status' => false,
					'productInfo' =>$saveproduct,
			] );


}




	}
	

}
