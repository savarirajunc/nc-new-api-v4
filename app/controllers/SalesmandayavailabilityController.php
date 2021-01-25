<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
require BASE_PATH . '/vendor/Crypto.php';
require BASE_PATH . '/vendor/mailin.php';
require BASE_PATH . '/vendor/class.phpmailer.php';

require BASE_PATH . '/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class SalesmandayavailabilityController extends \Phalcon\Mvc\Controller
{
//create SalesmanDayAvailability
    public function create()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();



            foreach ($input_data->salesmanavailableInfo as  $value) 
            {

			        if (empty($value->id))
			        {
			            $colloction = new SalesmanDayAvailability();
			        }
			        else
			        {
			            $colloction = SalesmanDayAvailability::findFirstByid($value->id);
			        }

			        $colloction->user_id = $input_data->user_id;
			        $colloction->choose_date = $value->choose_date;
			        $colloction->start_time = $value->start_time;
			        $colloction->end_time = $value->end_time;

			        if (!$colloction->save())
				        {
				        	 return $this
			                ->response
			                ->setJsonContent(['status' => false, 'data' => 'Failed']);
							            
				        }
            	
            }

          			 return $this
				      ->response
				      ->setJsonContent(['status' => true, 'data' => 'succesfully']);
      
    }


    //create SalesCenterAvailability
    public function createsalescenteravailability()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();



            foreach ($input_data->salescenterInfo as  $value) 
            {

			        if (empty($value->id))
			        {
			            $colloction = new SalesCenterAvailability();
			        }
			        else
			        {
			            $colloction = SalesCenterAvailability::findFirstByid($value->id);
			        }

			        $colloction->center_id  = $input_data->center_id;
			        $colloction->date = $input_data->date;
					$colloction->start_time = $value->start_time;
			        $colloction->end_time = $value->end_time;

			        if (!$colloction->save())
				        {
				        	 return $this
			                ->response
			                ->setJsonContent(['status' => false, 'data' => $colloction]);
							            
				        }
            	
            }

          			 return $this
				      ->response
				      ->setJsonContent(['status' => true, 'data' => $colloction]);
      
    }

    //create salesmanpostcodes
    public function createsalesmanpostcodes()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

      

        foreach ($input_data->postcodeInfo as $value) 
        {

        	  		if (empty($value->id))
			        {
			            $colloction = new SalesmanPostCodes();
			        }
			        else
			        {
			            $colloction = SalesmanPostCodes::findFirstByid($value->id);
			        }

			         $colloction->user_id  = $input_data->user_id;
			        $colloction->post_codes = $value->post_codes;
			        $colloction->create_at  = date('Y-m-d');

			        if (!$colloction->save())
			        {
			        	return $this
			                ->response
			                ->setJsonContent(['status' => false, 'data' => 'Failed']);
			           
			        }
			      
		}

		 return $this
			                ->response
			                ->setJsonContent(['status' => true, 'data' => 'succesfully']);
       
    }

//create sales center
    public function createsalescenter()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();




            $salescenter=SalesCenter::findFirstByid($input_data-> id);

            if(!$salescenter)
            {
             $salescenter=new SalesCenter();
            }

            $salescenter->address_1 = $input_data->address_1;
            $salescenter->address_2    = $input_data->address_2 ;
            $salescenter->city  = $input_data->city;
            $salescenter->state = $input_data->state;
            $salescenter->country  = $input_data->country;
            $salescenter->post_code  = $input_data->post_code;
            $salescenter->center_type  = $input_data->center_type;

           if($salescenter->save())
            {
             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $salescenter]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  $salescenter]);

            }



}




    public function getbyid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanDayAvailability.id',
            'SalesmanDayAvailability.user_id',
            'SalesmanDayAvailability.choose_date',
            'SalesmanDayAvailability.start_time',
            'SalesmanDayAvailability.end_time',
        ))
            ->from('SalesmanDayAvailability')
            ->inwhere('SalesmanDayAvailability.id', array(
            $input_data->id
        ))
        //->inwhere ( 'SalesmanDayAvailability.user_id', array ($input_data->user_id) )
        
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {
            $avaiable = array();
            foreach ($collection as $value)
            {

                $date1 = strtotime($value->start_time);
                $date2 = strtotime($value->end_time);
                $diff = abs($date2 - $date1);
                $data['id'] = $value->id;
                $data['user_id'] = $value->user_id;
                $data['choose_date'] = $value->choose_date;
                $data['start_time'] = $value->start_time;
                $data['end_time'] = $value->end_time;
                $data['diff'] = $diff / 60;

                $avaiable[] = $data;

            }

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $avaiable]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }
	
	public function getsalescenterdetails(){
		$input_data = $this->request->getJsonRawBody();
		// return $this->response->setJsonContent(['status' => true, 'data' => $input_data]);
		 $collection = $this->modelsManager->createBuilder()->columns(array(
            'SalesCenter.id',
			'SalesCenter.open_time',
            'SalesCenter.close_time',
            'SalesCenter.email',
            'SalesCenter.mobile',
            'SalesCenter.address_1',
            'SalesCenter.address_2',
            'SalesCenter.center_type',
            'Cities.name as city',
			'Countries.sortname as country',
            'States.name as state',
        ))->from('SalesCenter')
		->leftjoin('Countries','Countries.id = SalesCenter.country')
		->leftjoin('Cities','Cities.id = SalesCenter.city')
		->leftjoin('States','States.id = SalesCenter.state')
		->inwhere('SalesCenter.city', array(
            $input_data->city
        ))->inwhere('SalesCenter.state', array(
            $input_data->state
        ))->inwhere('SalesCenter.country', array(
            $input_data->country
        ))->getQuery()->execute();
		$getarray = array();
		if(count($collection) > 0){
			foreach($collection as $value){
				$gettimeday = SalesCenterOpenClose::findBycenter_id($value -> id);
				$data['daytime'] = $gettimeday;
				$data['id'] = $value -> id;
				$data['email'] = $value -> email;
				$data['mobile'] = $value -> mobile;
				$data['address_1'] = $value -> address_1;
				$data['address_2'] = $value -> address_2;
				$data['center_type'] = $value -> center_type;
				$data['city'] = $value -> city;
				$data['country'] = $value -> country;
				$data['state'] = $value -> state;
				$getarray[] = $data;
			}
			return $this->response->setJsonContent(['status' => true, 'data' => $getarray]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'data' => "data not available"]);
		}
	}
	
	public function getsalescenterwithpostcode(){
		$input_data = $this->request->getJsonRawBody();
		 $collection = $this->modelsManager->createBuilder()->columns(array(
            'SalesCenter.id',
            'SalesCenter.open_time',
            'SalesCenter.close_time',
			'SalesCenter.email',
            'SalesCenter.mobile',
            'SalesCenter.address_1',
            'SalesCenter.address_2',
            'SalesCenter.center_type',
            'Cities.name as city',
			'Countries.sortname as country',
            'States.name as state',
        ))->from('SalesCenter')
		->leftjoin('Countries','Countries.id = SalesCenter.country')
		->leftjoin('Cities','Cities.id = SalesCenter.city')
		->leftjoin('States','States.id = SalesCenter.state')
		->inwhere('SalesCenter.city', array(
            $input_data->city
        ))->inwhere('SalesCenter.state', array(
            $input_data->state
        ))->inwhere('SalesCenter.country', array(
            $input_data->country
        ))->inwhere('SalesCenter.post_code', array(
            $input_data->post_code
        ))->getQuery()->execute();
		if(count($collection) > 0){
			return $this->response->setJsonContent(['status' => true, 'data' => $collection]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'data' => "data not available"]);
		}
	}
	
	public function getcentermap(){
		$input_data = $this->request->getJsonRawBody();
		$centerinfo = $this->modelsManager->createBuilder()->columns(array(
            'SalesCenter.id',
            'SalesCenter.open_time',
            'SalesCenter.close_time',
            'SalesCenter.center_overview',
			'SalesCenter.email',
            'SalesCenter.mobile',
            'SalesCenter.address_1',
            'SalesCenter.address_2',
            'SalesCenter.center_type',
            'Cities.name as city',
			'Countries.sortname as country',
            'States.name as state',
        ))->from('SalesCenter')
		->leftjoin('Countries','Countries.id = SalesCenter.country')
		->leftjoin('Cities','Cities.id = SalesCenter.city')
		->leftjoin('States','States.id = SalesCenter.state')
		->inwhere('SalesCenter.id', array(
            $input_data->id
        ))->getQuery()->execute();
		
		$uservalue = $this->modelsManager->createBuilder()->columns(array(
			'SalesCenterShowMap.user_id as user_ids',
			'SalesCenterShowMap.id as center_id',
			'Users.first_name as first_name',
			'Users.last_name as last_name',
			'Users.last_name as last_name',
			'Users.email as email',
			'Users.photo as photo',
			'Users.mobile as mobile',
			'Users.user_type as user_type',
		))->from("SalesCenterShowMap")
		->leftjoin('SalesCenter', 'SalesCenter.id = SalesCenterShowMap.center_id')
		->leftjoin('Users', 'Users.id = SalesCenterShowMap.user_id')
		->inwhere('SalesCenterShowMap.center_id', array(
			$input_data -> id
		))->getQuery()->execute();
		if(count($uservalue) > 0){
			return $this->response->setJsonContent(['status' => true, 'data' => $centerinfo, 'userinfo' => $uservalue]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'data' => "data not available"]);
		}
	}

    public function availabilitycheck()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
		$centerinfo = SalesCenter::findFirstByid($input_data -> id );
		
		$date = date('Y-m-d');
		$d = cal_days_in_month(CAL_GREGORIAN,$input_data -> month,$input_data -> year);
		//$dayvalue = 0;
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$i = $input_data -> day;
		$datearray = array();
		for($i = $input_data -> day; $i <= $d; $i++){
			if($input_data -> month <= 9){
				$monthset = '0' . $input_data -> month;
			} else {
				$monthset = $input_data -> month;
			}
			if($i <= 9){
				$dayset = '0'. $i;
			} else {
				$dayset = $i;
			}
			$choose_date = $input_data -> year . '-' . $monthset . '-' . $dayset;
			$uservalue = $this->modelsManager->createBuilder()->columns(array(
				'SalesCenterMap.user_id as user_ids',
			))->from("SalesCenterMap")
			->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
			->inwhere('SalesCenterMap.center_id', array(
				$input_data -> id
			))
			->inwhere('Users.user_type', array(
				"coordinator","dev_enroll_officer"
			))->getQuery()->execute();
			$checkstatus = 0;
			$userarray = array();
			foreach($uservalue as $value){
				$meetingtime = SalesMeetingAvailability::findBycenter_id($input_data -> id);
				foreach($meetingtime as $meetingvalue){
					$school = $this->modelsManager->createBuilder()->columns(array(
						'SalesmanAppointment.day_id',
					))->from("SalesmanAppointment")
					->inwhere('SalesmanAppointment.status', array(
						1
					))->inwhere('SalesmanAppointment.officer_id', array(
								$value -> user_ids
					))->inwhere('SalesmanAppointment.day_id', array(
								$meetingvalue -> id
					))->inwhere('SalesmanAppointment.choose_date', array(
								$choose_date
					))->getQuery()->execute();
					if(count($school) <= 0){
						$checkstatus = 1;
					}
				}
				$userinfo['user_id'] = $value -> user_ids;
				$userinfo['checkstatus'] = $checkstatus;
				$userinfo['$school'] = $school;
				$userarray[] = $userinfo;
			}
			if($checkstatus == 1){
				$datavalue['status'] = false;
			} else {
				$datavalue['status'] = true;
			}
			
			$datavalue['id'] = $i;
			$datavalue['userinfo'] = $userarray;
			$datavalue['choose_date'] = $choose_date;
			$datearray[] = $datavalue;
		}
		
		 return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $datearray, 'days' => $datearray]);
		
        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanAddress.user_id',
            'SalesmanDayAvailability.id',
            'SalesmanDayAvailability.choose_date',
        ))
            ->from('SalesmanAddress')
            ->leftjoin('SalesmanDayAvailability', 'SalesmanDayAvailability.user_id=SalesmanAddress.user_id')
            ->leftjoin('SalesmanPostCodes', 'SalesmanPostCodes.user_id=SalesmanAddress.user_id')
            ->inwhere('SalesmanAddress.city', array(
            $centerinfo->city
        ))
            ->inwhere('SalesmanAddress.state', array(
            $centerinfo->state
        ))
            ->inwhere('SalesmanAddress.country', array(
            $centerinfo->country
        ))
            ->inwhere('SalesmanPostCodes.post_codes', array(
            $centerinfo->post_code
        ))
            ->groupby('SalesmanDayAvailability.choose_date')
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {
            $avaiable = array();
            foreach ($collection as $value)
            {
				 $time = date('H:s:i');
				$collection2 = $this->modelsManager->createBuilder()->columns(array(
					'SalesmanAddress.user_id',
					'SalesmanDayAvailability.id',
					'SalesmanDayAvailability.choose_date',
				))->from('SalesmanAddress')
				->leftjoin('SalesmanDayAvailability', 'SalesmanDayAvailability.user_id=SalesmanAddress.user_id')
				->leftjoin('SalesmanPostCodes', 'SalesmanPostCodes.user_id=SalesmanAddress.user_id')
				->where('SalesmanDayAvailability.start_time >= "' . $time .'"')
				->inwhere('SalesmanAddress.city', array(
					$centerinfo->city
				))->inwhere('SalesmanAddress.state', array(
					$centerinfo->state
				))->inwhere('SalesmanAddress.country', array(
					$centerinfo->country
				))->inwhere('SalesmanPostCodes.post_codes', array(
					$centerinfo->post_code
				))->inwhere('SalesmanDayAvailability.choose_date', array(
					$value->choose_date
				))->getQuery()->execute();
				if(count($collection2) <= 0){
					$data['status'] = true;
				} else {
					 $checkdata = 0;
					foreach($collection2 as $value2){
						$school = $this->modelsManager->createBuilder()->columns(array(
							'SalesmanAppointment.day_id',
						))->from("SalesmanAppointment")
						->inwhere('SalesmanAppointment.status', array(
							1
						))->inwhere('SalesmanAppointment.day_id', array(
							$value2 -> id
						))->getQuery()->execute();
						if(count($school) > 0){
							$checkdata = 1;
						}
					}
					if($checkdata == 1){
						$data['status'] = true;
					} else {
						$data['status'] = false;
					}	
				} 
				
				$data['user_id'] = $value->user_id;
                $data['choose_date'] = $value->choose_date;

                $avaiable[] = $data;

            }
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $datearray, 'days' => $datearray]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available"]);
        }
    }

    public function availabilitycheckusebyid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanDayAvailability.id',
            'SalesmanDayAvailability.user_id',
            'SalesmanDayAvailability.choose_date',
            'SalesmanDayAvailability.start_time',
            'SalesmanDayAvailability.end_time',
        ))
            ->from('SalesmanDayAvailability')
            ->inwhere('SalesmanDayAvailability.choose_date', array(
            $input_data->choose_date
        ))
        //->inwhere ( 'SalesmanDayAvailability.user_id', array ($input_data->user_id) )
        
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {
            $avaiable = array();
            foreach ($collection as $value)
            {
                $data['id'] = $value->id;
                $data['user_id'] = $value->user_id;
                $data['choose_date'] = $value->choose_date;
                $data['start_time'] = $value->start_time;
                $data['end_time'] = $value->end_time;

                $school = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'SalesmanAppointment.day_id',

                ))
                    ->from("SalesmanAppointment")
                    ->inwhere('SalesmanAppointment.status', array(
                    1
                ))
                    ->inwhere('SalesmanAppointment.day_id', array(
                    $value->id
                ))

                    ->getQuery()
                    ->execute();

                if (count($school) > 0)
                {
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;

                }

                $avaiable[] = $data;

            }
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $avaiable]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available"]);
        }
    }

    public function availabilitycheckbyuserid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanAppointment.id',
            'SalesmanAppointment.officer_id',
            'SalesmanAppointment.choose_date',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',
        ))
            ->from('SalesmanAppointment')
			->leftjoin('SalesMeetingAvailability','SalesMeetingAvailability.id = SalesmanAppointment.day_id')
        /*   ->inwhere ( 'SalesmanDayAvailability.choose_date', array ($input_data->choose_date) )*/
            ->inwhere('SalesmanAppointment.officer_id', array(
            $input_data->user_id
        ))
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {
            $avaiable = array();
            foreach ($collection as $value)
            {
                $data['id'] = $value->id;
                $data['user_id'] = $value->user_id;
                $data['choose_date'] = $value->choose_date;
                $data['start_time'] = $value->start_time;
                $data['end_time'] = $value->end_time;

                $school = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'SalesmanAppointment.day_id',

                ))
                    ->from("SalesmanAppointment")
                    ->inwhere('SalesmanAppointment.status', array(
                    1
                ))
                    ->inwhere('SalesmanAppointment.day_id', array(
                    $value->id
                ))

                    ->getQuery()
                    ->execute();

                if (count($school) > 0)
                {
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;

                }

                $avaiable[] = $data;

            }
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $avaiable]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available"]);
        }
    }

    public function availabilitycheckuseremailid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesMeetingAvailability.id',
            'SalesmanAppointment.officer_id',
            'SalesmanAppointment.choose_date',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',
            'SalesMeetingAvailability.center_id',
            'SalesmanAppointment.status',
            'SalesmanAppointment.id as appointmentid',
        ))
            ->from('SalesmanAppointment')
            ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
            ->inwhere('SalesmanAppointment.email', array(
            $input_data->email
        ))
            ->inwhere('SalesmanAppointment.status', array(
            1
        ))
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $collection]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available"]);
        }
    }

    public function availabilitycheckusebyidanddate()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
			$time = date('H:s:i');
				if($input_data->choose_date == date('Y-m-d')){
					$meetingtime = $this->modelsManager->createBuilder()->columns(array(
						'SalesMeetingAvailability.id',
						'SalesMeetingAvailability.start_time',
						'SalesMeetingAvailability.end_time',
					))->from("SalesMeetingAvailability")
					->where('SalesMeetingAvailability.start_time >= "' . $time .'"')
					->inwhere('SalesMeetingAvailability.center_id', array(
						$input_data -> id
					))->getQuery()->execute();
				} else {
					$meetingtime = SalesMeetingAvailability::findBycenter_id($input_data -> id);
				}
			$avaiable = array();
			foreach($meetingtime as $meetingvalue){
				$uservalue = $this->modelsManager->createBuilder()->columns(array(
					'SalesCenterMap.user_id as user_ids',
				))->from("SalesCenterMap")
				->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
				->inwhere('SalesCenterMap.center_id', array(
					$input_data -> id
				))
				->inwhere('Users.user_type', array(
					"coordinator","dev_enroll_officer"
				))->getQuery()->execute();
				$checkstatus = 0;
				$user = '';
				$userarray = array();
				foreach($uservalue as $value){
					$school = $this->modelsManager->createBuilder()->columns(array(
						'SalesmanAppointment.day_id',
					))->from("SalesmanAppointment")
					->inwhere('SalesmanAppointment.status', array(
						1
					))->inwhere('SalesmanAppointment.officer_id', array(
								$value -> user_ids
					))->inwhere('SalesmanAppointment.day_id', array(
								$meetingvalue -> id
					))->inwhere('SalesmanAppointment.choose_date', array(
								$input_data->choose_date
					))->getQuery()->execute();
					if(count($school) <= 0){
						if($checkstatus == 0){
							$checkstatus = 1;
							$user = $value -> user_ids;
						}
					} 
				}
				if($checkstatus == 1){
					$data['status'] = false;
				} else {
					$data['status'] = true;
				}
				$data['id'] = $meetingvalue->id;
                $data['user_id'] = $user;
                $data['choose_date'] = $input_data->choose_date;
                $data['start_time'] = $meetingvalue->start_time;
                $data['end_time'] = $meetingvalue->end_time;
				$avaiable[] =  $data;
			}
		 return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $avaiable]);
		$time = date('H:s:i');
		if($input_data->choose_date == date('Y-m-d')){
			$collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanDayAvailability.id',
            'SalesmanDayAvailability.user_id',
            'SalesmanDayAvailability.choose_date',
            'SalesmanDayAvailability.start_time',
            'SalesmanDayAvailability.end_time',
        ))
            ->from('SalesmanDayAvailability')
			->where('SalesmanDayAvailability.start_time >= "' . $time .'"')
            ->inwhere('SalesmanDayAvailability.choose_date', array(
            $input_data->choose_date
        ))
            ->inwhere('SalesmanDayAvailability.user_id', array(
            $input_data->user_id
        ))
            ->getQuery()
            ->execute();
		} else {
			$collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanDayAvailability.id',
            'SalesmanDayAvailability.user_id',
            'SalesmanDayAvailability.choose_date',
            'SalesmanDayAvailability.start_time',
            'SalesmanDayAvailability.end_time',
        ))
            ->from('SalesmanDayAvailability')
            ->inwhere('SalesmanDayAvailability.choose_date', array(
            $input_data->choose_date
        ))
            ->inwhere('SalesmanDayAvailability.user_id', array(
            $input_data->user_id
        ))
            ->getQuery()
            ->execute();
		}
        

        if (count($collection) > 0)
        {
            $avaiable = array();
            foreach ($collection as $value)
            {
                $data['id'] = $value->id;
                $data['user_id'] = $value->user_id;
                $data['choose_date'] = $value->choose_date;
                $data['start_time'] = $value->start_time;
                $data['end_time'] = $value->end_time;

                $school = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'SalesmanAppointment.day_id',

                ))
                    ->from("SalesmanAppointment")
                    ->inwhere('SalesmanAppointment.status', array(
                    1
                ))
                    ->inwhere('SalesmanAppointment.day_id', array(
                    $value->id
                ))

                    ->getQuery()
                    ->execute();

                if (count($school) > 0)
                {
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;

                }

                $avaiable[] = $data;

            }
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $avaiable]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available", 'time' => $time]);
        }
    }
	
	public function updateappointment(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = SalesmanAppointment::findFirstByid($input_data -> id);
		$colloction->first_name = $input_data->first_name;
        $colloction->last_name = $input_data->last_name;
        $colloction->email = $input_data->email;
        $colloction->mno = $input_data->mno;
        $colloction->parent_role = $input_data->parent_role;
        $colloction->company = $input_data->company;
        $colloction->country = $input_data->country;
        $colloction->state = $input_data->state;
        $colloction->city = $input_data->city;
        $colloction->dob = $input_data->dob;
        $colloction->gender = $input_data->gender;
        $colloction->grade = $input_data->grade;
        $colloction->choose_date = $input_data->choose_date;
        $colloction->who_intrest = $input_data->who_intrest;
		if(!$colloction -> save()){
			return $this->response->setJsonContent(['status' => false, 'massege' => 'The update not don', 'data' =>  $colloction]);
		} else {
			return $this->response->setJsonContent(['status' => true, 'data' => 'Email send successfully', ]);
		}
	}

    public function appointment()
    {
        $input_data = $this->request->getJsonRawBody();

        $colloction = new SalesmanAppointment();

        $colloction->first_name = $input_data->first_name;
        $colloction->last_name = $input_data->last_name;
        $colloction->email = $input_data->email;
        $colloction->mno = $input_data->mno;
        $colloction->parent_role = $input_data->parent_role;
        $colloction->information_session_type = $input_data->information_session_type;
        $colloction->company = $input_data->company;
        $colloction->country = $input_data->country;
        $colloction->state = $input_data->state;
        $colloction->city = $input_data->city;
        $colloction->dob = $input_data->dob;
        $colloction->gender = $input_data->gender;
        $colloction->grade = $input_data->grade;
        $colloction->officer_id = $input_data->user_id;
        $colloction->choose_date = $input_data->choose_date;
        $colloction->who_intrest = $input_data->who_intrest;
        $colloction->day_id = $input_data->day_id;
        $colloction->status = 1;

        if ($colloction->save())
        {
				$timevalue = SalesMeetingAvailability::findFirstByid($input_data->day_id);
				$centerInfo = SalesCenterMap::findFirstByuser_id($input_data->user_id);
                $timestamp = strtotime($input_data->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				
				$date = $timevalue->start_time; 
				$time = date('h:i A ', strtotime($date));
				
                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($input_data->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your Nidara-Children Virtual Information Session Appointment Confirmation';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>YOUR NIDARA-CHILDREN VIRTUAL INFORMATION SESSION APPOINTMENT CONFIRMATION</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $input_data->first_name . ' ,</p> 

        <p>Your NC Virtual Information Session has been confirmed as below:</p>

        <p> Date : ' . $new_date . ' </p>

        <p> Time : ' . $time . ' </p>

        <p>We will send you a separate virtual meeting invite in your email.</p>
        <p>To Modify Your Appointment, click the button below:</p>

        <div class="click-but">
        <div class="but">
          <a href="' . $this
                    ->config->appointmenturl . 'check-available-date/'. $centerInfo -> center_id .'?email=' . $input_data->email . '"> <span>MODIFY MY APPOINTMENT</span> </a>
        </div>
      </div>

        <p>To Cancel Your Appointment, click the button below:</p> 
         <div class="click-but">
        <div class="but">
          <a href="' . $this
                    ->config->appointmenturl . 'cancellation?id=' . $colloction->id . '"> <span>CANCEL MY APPOINTMENT</span> </a>
        </div>
      </div>


        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>
';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    // return $this->response->setJsonContent ( [
                    //  'status' => true,
                    //  'message' => 'Message hase be sent.'
                    // ] );
                    

                    
                }
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => 'Email send successfully', ]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }

    public function bulkappointment()
    {
        $input_data = $this->request->getJsonRawBody();

        


        foreach ($input_data->userInfo as $value) 
        {
        	# code...
        $colloction = new SalesmanAppointment();
        $colloction->first_name = $value->first_name;
        $colloction->last_name = $value->last_name;
        $colloction->email = $value->email;
        $colloction->mno = $value->mno;
        $colloction->grade = $value->grade;
        $colloction->parent_role = $input_data->parent_role;
        $colloction->information_session_type = $input_data->information_session_type;
        $colloction->company = $input_data->company;
        $colloction->country = $input_data->country;
        $colloction->state = $input_data->state;
        $colloction->city = $input_data->city;
        $colloction->dob = $input_data->dob;
        $colloction->gender = $input_data->gender;
        $colloction->officer_id = $input_data->user_id;
        $colloction->choose_date = $input_data->choose_date;
        $colloction->who_intrest = $input_data->who_intrest;
        $colloction->day_id = $input_data->day_id;
        $colloction->status = 1;

        if ($colloction->save())
        {
				$timevalue = SalesMeetingAvailability::findFirstByid($input_data->day_id);
				$centerInfo = SalesCenterMap::findFirstByuser_id($input_data->user_id);
                $timestamp = strtotime($input_data->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				
				$date = $timevalue->start_time; 
				$time = date('h:i A ', strtotime($date));
				
                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($value->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your Nidara-Children Group Virtual Information Session Appointment Confirmation';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html>
						<head>
						<meta http-equiv="content-type" content="text/html; charset=utf-8" />
						<style type="text/css">
						.ReadMsgBody {width: 100%;}
						.ExternalClass {width: 100%;}

						body {
						  color: #666666;
						}
						    .page-title {
						      text-align: center;
						      width: 100%;
						      float: left;
						      color: #83d0c9;
						    }
						           body,td{
						            font-family:verdana,geneva;
						            font-size:12px;
						           }
						           body{
						            background:#fff;
						            padding:20px;
						           }
						           .top-img{
						            width:100%;
						            text-align:center;
						            padding-bottom:0;
						            font-size:10px;
						           }
						           .sub-mail-cont{
						            width:100%;
						           }
						           .sub-mail-vr{
						            width:580px;
						            margin:auto;
						            float:none;
						           }
						           .main-page-mail{
						            width:100%;
						            float:left;
						            padding:20px;
						            border:1px solid #999;
						           }
						           .sub-mail-but{
						            width:100%;
						            text-align:center;
						            padding-top:30px;
						            float:left;
						           }
						           a.sub-but{
						            text-decoration:none;
						            color:#333;
						            padding:10px 50px;
						            border:1px solid;
						           }
						           .sub-but-cont{
						            width:100%;
						            padding-top:20px;
						            float:left;
						           }
						           .footer{
						            width:100%;
						            text-align:center;
						            font-size:10px;
						            padding-top:20px;
						            float:left;
						           }
						           .footer ul{
						            list-style:none;
						            float:left;
						            margin:15px 10px;
						            width:100%;
						            padding:0;
						           }
						           .footer ul li{
						            display:inline-flex;
						            padding-left:5px;
						           }
						           p{
						            line-height:18px;
						           }
						           .small{
						            font-size:11px;
						           }
						           .main-title{
						            text-align:center;
						            color:#aed7d3;
						            float:left;
						            width:100%;
						           }
						           .main-title h3{
						            font-weight:500;
						           }
						           .first-name{
						            text-transform:capitalize;
						           }
						           .product-img{
						            width:20%;
						            float:left;
						            padding-right:20px;
						           }
						           .product-img img{
						            width:100%;
						           }
						           .product-cont{
						            width:75%;
						            float:left;
						           }
						           .product-details{
						            width:100%;
						            float:left;
						           }
						       .page-content {
						        width: 100%;
						        float: left;
						        color: #666666;
						      }
						      .click-but {
						        width: 100%;
						        float: left;
						        text-align: center;
						      }
						      .click-but .but{
						        width:250px;
						        display:block;
						        margin:auto;
						      }
						      .click-but .but a {
						        text-decoration: none;
						        padding: 10px;
						        color: #fff;
						      }
						      .click-but .but {
						        display: block;
						        margin: auto;
						        width: 265px;
						        padding: 15px;
						        background: #333333;
						        font-size: 15px;
						        float: left;
						      }
						      .click-but .but:hover{
						        background: #83d0c9;
						      }
						         
						span.yshortcuts { color:#000; background-color:none; border:none;}
						span.yshortcuts:hover,
						span.yshortcuts:active,
						span.yshortcuts:focus {color:#000; background-color:none; border:none;}
						</style>
						</head>
						<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
						,geneva; font-size: 12px; background: #fff; padding: 20px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

						          
						          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
						            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
						           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
						             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
						           </div>
						       <div class="page-title">
						      <h3>YOUR NIDARA-CHILDREN GROUP VIRTUAL INFORMATION SESSION APPOINTMENT CONFIRMATION</h3>
						       </div>
						      <div class="page-content">
						        
						        <p>Dear ' . $value->first_name . ' ,</p> 

						        <p>Your NC Virtual Information Session has been confirmed as below:</p>

						        <p> Date : ' . $new_date . ' </p>

						        <p> Time : ' . $time . ' </p>

						        <p>We will send you a separate virtual meeting invite in your email.</p>
						        <p>To Modify Your Appointment, click the button below:</p>

						        <div class="click-but">
						        <div class="but">
						          <a href="' . $this
						                    ->config->appointmenturl . 'check-available-date/'. $centerInfo -> center_id .'?email=' . $value->email . '"> <span>MODIFY MY APPOINTMENT</span> </a>
						        </div>
						      </div>

						        <p>To Cancel Your Appointment, click the button below:</p> 
						         <div class="click-but">
						        <div class="but">
						          <a href="' . $this
						                    ->config->appointmenturl . 'cancellation?id=' . $colloction->id . '"> <span>CANCEL MY APPOINTMENT</span> </a>
						        </div>
						      </div>


						        <p>We look forward to helping you give your child the best start in life.</p>

						        

						      </div>
						      
						            
						            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
						           <p style="line-height: 18px;">Best regards,</p>
						           <p style="line-height: 18px;">
						            </p>
						            <p style="line-height: 18px;">Nidara-Children</p>
						          </div>
						          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
						            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
						        <li style="display: inline-flex; padding-left: 5px;">
						         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
						         </li>
						         <li style="display: inline-flex; padding-left: 5px;">
						         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
						         </li>
						         <li style="display: inline-flex; padding-left: 5px;">
						         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
						         </li>
						         <li style="display: inline-flex; padding-left: 5px;">
						         <a class="email" href="' . $this
						                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
						         </li>
						           </ul>
						      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
						        <br /><span>You are receiving this email because you opted in at our website.
						            </span>
						            <br /><span>
						      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
						      </span>
						          </div>
						           </div>
						         </div>
						         
						</td></tr></table>
						</body>
						</html>
						';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
            
        }
        else
        {
        	return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>$colloction]);
        }
        }

        return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => 'Email send successfully', ]);
        
    }

    public function appointmentstatusupdate()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $colloction = SalesmanAppointment::findFirstByid($input_data->id);

        $colloction->status = 0;

        if ($colloction->save())
        {

            $school = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesmanAppointment.id',
                'SalesmanAppointment.first_name',
                'SalesmanAppointment.last_name',
                'SalesmanAppointment.email',
                'SalesmanAppointment.mno',
                'SalesmanAppointment.choose_date',
                'SalesMeetingAvailability.start_time',

            ))
                ->from("SalesmanAppointment")
                ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
                ->inwhere('SalesmanAppointment.status', array(
                0
            ))
                ->inwhere('SalesmanAppointment.id', array(
                $input_data->id
            ))
                ->getQuery()
                ->execute();

            foreach ($school as $value)
            {
                $timestamp = strtotime($value->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				$date = $value->start_time; 
				$time = date('h:i A ', strtotime($date));
                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($value->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your NC Virtual Information Session Appointment Cancellation Confirmation';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>YOUR NIDARA-CHILDREN VIRTUAL INFORMATION SESSION </h3>
      <h3>APPOINTMENT CANCELLATION CONFIRMATION</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $value->first_name . ' ,</p> 
<br>

        <p>Your NC Virtual Information Session has been cancelled for the date and timing as below:</p>

        <p> Date : ' . $new_date . ' </p>

        <p> Time : ' . $time . ' </p>
<br>
        <p>To Book a New Appointment, click the button below:</p>
<br>
        <div class="click-but">
        <div class="but">
          <a href="' . $this
                    ->config->appointmenturl . '"> <span>BOOK AN APPOINTMENT</span> </a>
        </div>
      </div>



        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);

                }
            }
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }
	
	public function getappointmentcount(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = $this->modelsManager->createBuilder()->columns(array(
            'SalesmanAppointment.id',
		))->from("SalesmanAppointment")
		->inwhere('SalesmanAppointment.officer_id', array(
            $input_data -> user_id
		))->inwhere('SalesmanAppointment.meeting_status', array(
            0
		))->inwhere('SalesmanAppointment.status', array(
            1
		))
		->groupby("SalesmanAppointment.day_id")
		->groupby("SalesmanAppointment.choose_date")
		->getQuery()
		->execute();
		if(count($colloction) > 0){
			return $this->response->setJsonContent(['status' => true, 'data' => count($colloction)]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'message' => 'All meeting or set meeting link']);
		}
		
	}
	
	
	public function getcenterappointmentcount(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = $this->modelsManager->createBuilder()->columns(array(
            'SalesCenterAppointment.id',
		))->from("SalesCenterAppointment")
		->inwhere('SalesCenterAppointment.dev_off_id', array(
            $input_data -> user_id
		))->inwhere('SalesCenterAppointment.meeting_status', array(
            0
		))->inwhere('SalesCenterAppointment.status', array(
            1
		))->getQuery()->execute();
		if(count($colloction) > 0){
			return $this->response->setJsonContent(['status' => true, 'data' => count($colloction)]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'message' => 'All meeting or set meeting link']);
		}
		
	}
	
		public function getcenterappointmentlist(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = $this->modelsManager->createBuilder()->columns(array(
           'SalesCenterAppointment.id',
                'Users.first_name',
                'Users.last_name',
				'NidaraKidProfile.first_name as child_first_name',
                'NidaraKidProfile.last_name as child_last_name',
                'Users.email',
                'Users.mobile',
                'SalesCenterAppointment.choose_date',
                'SalesMeetingAvailability.start_time',
                'SalesCenterAppointment.meeting_link',
		))->from("SalesCenterAppointment")
		->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
		->leftjoin('Users', 'Users.id = SalesCenterAppointment.coustomer_id')
		->leftjoin('NidaraKidProfile', 'NidaraKidProfile.id = SalesCenterAppointment.child_id')
		->inwhere('SalesCenterAppointment.dev_off_id', array(
            $input_data -> user_id
		))->inwhere('SalesCenterAppointment.meeting_status', array(
            0
		))->inwhere('SalesCenterAppointment.status', array(
            1
		))->getQuery()->execute();
		$dataarray = array();
		if(count($colloction) > 0){
			foreach($colloction as $value){
				$data['id'] = $value -> id;
				$data['first_name'] = $value -> first_name;
				$data['last_name'] = $value -> last_name;
				$data['child_first_name'] = $value -> child_first_name;
				$data['child_last_name'] = $value -> child_last_name;
				$data['email'] = $value -> email;
				$data['mobile'] = $value -> mno;
				$data['choose_date'] = $value -> choose_date;
				$data['start_time'] = $value -> start_time;
				$data['meeting_link'] = $value -> meeting_link;
				$dataarray[] = $data;
			}
			return $this->response->setJsonContent(['status' => true, 'data' => $dataarray]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'message' => 'All meeting or set meeting link']);
		}
	}
	
	public function getappointmentlist(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = $this->modelsManager->createBuilder()->columns(array(
                'SalesmanAppointment.choose_date',
                'SalesmanAppointment.day_id',
                'SalesMeetingAvailability.start_time',
                'SalesmanAppointment.meeting_link',
                'SalesmanAppointment.information_session_type',
		))->from("SalesmanAppointment")
		->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
		->inwhere('SalesmanAppointment.officer_id', array(
            $input_data -> user_id
		))->inwhere('SalesmanAppointment.meeting_status', array(
            0
		))->inwhere('SalesmanAppointment.status', array(
            1
		))
		->groupby("SalesmanAppointment.day_id")
		->groupby("SalesmanAppointment.choose_date")
		->getQuery()->execute();
		$dataarray = array();
		if(count($colloction) > 0){
			foreach($colloction as $value){
				$colloction2 = $this->modelsManager->createBuilder()->columns(array(
					'SalesmanAppointment.id',
					'SalesmanAppointment.first_name',
					'SalesmanAppointment.last_name',
					'SalesmanAppointment.email',
					'SalesmanAppointment.mno',
				))->from("SalesmanAppointment")
				->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
				->inwhere('SalesmanAppointment.officer_id', array(
					$input_data -> user_id
				))->inwhere('SalesmanAppointment.meeting_status', array(
					0
				))->inwhere('SalesmanAppointment.status', array(
					1
				))->inwhere('SalesmanAppointment.choose_date', array(
					$value -> choose_date
				))->inwhere('SalesmanAppointment.day_id', array(
					$value -> day_id
				))->getQuery()->execute();
				$userinfo = array();
				foreach($colloction2 as $value2){
					$data2['id'] =  $value2 -> id;
					$data2['first_name'] =  $value2 -> first_name;
					$data2['last_name'] =  $value2 -> last_name;
					$data2['email'] =  $value2 -> email;
					$userinfo[] = $data2;
				}
				$data['choose_date'] = $value -> choose_date;
				$data['user_id'] = $input_data -> user_id;
				$data['day_id'] = $value -> day_id;
				$data['userinfo'] = $userinfo;
				$data['start_time'] = $value -> start_time;
				$data['meeting_link'] = $value -> meeting_link;
				$data['information_session_type'] = $value -> information_session_type;
				$dataarray[] = $data;
			}
			return $this->response->setJsonContent(['status' => true, 'data' => $dataarray]);
		} else {
			return $this->response->setJsonContent(['status' => false, 'message' => 'All meeting or set meeting link']);
		}
	}
	
	public function sevemeetingnotes(){
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data -> nots)){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please check input filed', 'data' =>$input_data]);
		}
		$colloction = SalesmanAppointment::findFirstByid($input_data->id);
		$colloction -> nots = $input_data -> nots;
		$colloction -> status = 2;
		if(!$colloction -> save()){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please check input filed', 'data' => $colloction]);
		} else {
			return $this->response->setJsonContent(['status' => true, 'message' => 'The meeting notes update succesfully']);
		}
	}
	
		public function sevecentermeetinglink(){
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data -> meeting_link)){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please check input filed', 'data' =>$input_data]);
		}
		$colloction = SalesCenterAppointment::findFirstByid($input_data->id);
		$colloction -> meeting_link = $input_data -> meeting_link;
		$colloction -> meeting_status = 1;
		$datatime = SalesMeetingAvailability::findFirstByid($colloction ->sales_center_available_id);
		$userinfo = Users::findFirstByid($colloction -> coustomer_id);
		if(!$colloction -> save()){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please check input filed', 'data' => $colloction]);
		} else {
				$timestamp = strtotime($colloction->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				
				$date = $datatime->start_time; 
				$time = date('h:i A ', strtotime($date));
				$mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($userinfo->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Nidara-Children Orientation Session Meeting Link';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>YOUR NIDARA-CHILDREN ORIENTATION SESSION MEETING LINK</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $userinfo->first_name . ' ,</p> 

        <p>Your NC Orientation Information Session meeting link is below as per details given:</p>

        <p> Date : ' . $new_date . ' </p>

        <p> Time : ' . $time . ' </p>
		
		<p> Meeting Link : ' . $colloction -> meeting_link . ' </p>

        <p>To Modify Your Orientation Appointment, please contact our NC Enrollment Officer.</p>
        


        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);

                }
		}
	}
	
	
	public function sevemeetinglink(){
		$input_data = $this->request->getJsonRawBody();
		if(empty($input_data -> meeting_link)){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please check input filed', 'data' =>$input_data]);
		}
		$colloction2 = $this->modelsManager->createBuilder()->columns(array(
			'SalesmanAppointment.id',
			'SalesmanAppointment.first_name',
			'SalesmanAppointment.last_name',
			'SalesmanAppointment.email',
			'SalesmanAppointment.mno',
			'SalesmanAppointment.information_session_type',
		))->from("SalesmanAppointment")
		->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
		->inwhere('SalesmanAppointment.officer_id', array(
			$input_data -> user_id
		))->inwhere('SalesmanAppointment.meeting_status', array(
			0
		))->inwhere('SalesmanAppointment.status', array(
			1
		))->inwhere('SalesmanAppointment.choose_date', array(
			$input_data -> choose_date
		))->inwhere('SalesmanAppointment.day_id', array(
			$input_data -> day_id
		))->getQuery()->execute();
		foreach($colloction2 as $value){
		$colloction = SalesmanAppointment::findFirstByid($value->id);
		$colloction -> meeting_link = $input_data -> meeting_link;
		$colloction -> meeting_status = 1;
		$datatime = SalesMeetingAvailability::findFirstByid($colloction ->day_id);
		if(!$colloction -> save()){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please check input filed', 'data' => $colloction]);
		} else {
			if($value -> information_session_type == 1){
				$emailSubject = 'Nidara-Children Virtual Information Session Meeting Link';
				$emailtile = 'YOUR NIDARA-CHILDREN VIRTUAL INFORMATION SESSION MEETING LINK';
			} else {
				$emailSubject = 'Nidara-Children Virtual Group Information Session Meeting Link';
				$emailtile = 'YOUR NIDARA-CHILDREN GROUP VIRTUAL INFORMATION SESSION MEETING LINK';
			}
				$timestamp = strtotime($colloction->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				
				$date = $datatime->start_time; 
				$time = date('h:i A ', strtotime($date));
				$mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($colloction->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = $emailSubject;
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>' . $emailtile . '</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $value->first_name . ' ,</p> 

        <p>Your NC Orientation Information Session meeting link is below as per details given:</p>

        <p> Date : ' . $new_date . ' </p>

        <p> Time : ' . $time . ' </p>
		
		<p> Meeting Link : ' . $colloction -> meeting_link . ' </p>

        <p>To Modify Your Orientation Appointment, please contact our NC Enrollment Officer.</p>
        


        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
               /*  else
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);

                } */
		}
		}
		 return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);
	}

    public function appointmenttimeupdate()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $colloction = SalesmanAppointment::findFirstByid($input_data->id);

        $colloction->day_id = $input_data->day_id;
        $colloction->choose_date = $input_data->choose_date;

        if ($colloction->save())
        {
            $school = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesmanAppointment.id',
                'SalesmanAppointment.first_name',
                'SalesmanAppointment.last_name',
                'SalesmanAppointment.email',
                'SalesmanAppointment.mno',
                'SalesmanAppointment.choose_date',
                'SalesMeetingAvailability.start_time',

            ))
                ->from("SalesmanAppointment")
                ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
                ->inwhere('SalesmanAppointment.status', array(
                1
            ))
                ->inwhere('SalesmanAppointment.id', array(
                $input_data->id
            ))
                ->getQuery()
                ->execute();

            foreach ($school as $value)
            {
                $timestamp = strtotime($value->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				
				$date = $value->start_time; 
				$time = date('h:i A ', strtotime($date));
				
                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($value->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your NC Virtual Information Session Appointment Modification Confirmation';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>YOUR NIDARA-CHILDREN VIRTUAL INFORMATION SESSION </h3>
      <h3>APPOINTMENT MODIFCATION CONFIRMATION</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $value->first_name . ' ,</p> 

        <p>Your NC Virtual Information Session has been confirmed as below:</p>

        <p> Date : ' . $new_date . ' </p>

        <p> Time : ' . $time . ' </p>

        <p>We will send you a separate virtual meeting invite in your email.</p>
        <p>To Modify Your Appointment, click the button below:</p>

        <div class="click-but">
        <div class="but">
          <a href="' . $this
                    ->config->appointmenturl . '?email=' . $value->email . '"> <span>MODIFY MY APPOINTMENT</span> </a>
        </div>
      </div>

        <p>To Cancel Your Appointment, click the button below:</p> 
         <div class="click-but">
        <div class="but">
          <a href="' . $this
                    ->config->appointmenturl . 'cancellation?id=' . $value->id . '"> <span>CANCEL MY APPOINTMENT</span> </a>
        </div>
      </div>


        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);

                }
            }
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }

    public function appointmentdetailsbyuseridtest()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesMeetingAvailability.id',
            'SalesmanAppointment.officer_id',
            'SalesmanAppointment.choose_date',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',
            'SalesmanAppointment.id as apointmentid',
            'SalesmanAppointment.first_name',
            'SalesmanAppointment.last_name',
            'SalesmanAppointment.email',
            'SalesmanAppointment.mno',
            'SalesmanAppointment.parent_role',
            'SalesmanAppointment.company',
            'SalesmanAppointment.state',
            'SalesmanAppointment.city',
            'SalesmanAppointment.dob',
            'SalesmanAppointment.gender',
            'SalesmanAppointment.grade',
            'SalesmanAppointment.who_intrest',
            'SalesmanAppointment.created_at',
            'SalesmanAppointment.day_id',
            'SalesmanAppointment.status',
        ))
            ->from('SalesmanAppointment')

            ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
            ->inwhere('SalesmanAppointment.officer_id', array(
            $input_data->user_id
        ))
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $collection]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'data not available']);
        }

    }

    public function appointmentdetailsbychoosedate()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesMeetingAvailability.id',
            'SalesmanAppointment.officer_id',
            'SalesmanAppointment.choose_date',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',
            'SalesmanAppointment.id as apointmentid',
            'SalesmanAppointment.first_name',
            'SalesmanAppointment.last_name',
            'SalesmanAppointment.email',
            'SalesmanAppointment.mno',
            'SalesmanAppointment.parent_role',
            'SalesmanAppointment.company',
            'SalesmanAppointment.state',
            'SalesmanAppointment.city',
            'SalesmanAppointment.dob',
            'SalesmanAppointment.gender',
            'SalesmanAppointment.grade',
            'SalesmanAppointment.who_intrest',
            'SalesmanAppointment.created_at',
            'SalesmanAppointment.day_id',
            'SalesmanAppointment.status',
        ))
            ->from('SalesmanAppointment')

            ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
            ->inwhere('SalesmanAppointment.officer_id', array(
            $input_data->user_id
        ))
            ->inwhere('SalesmanAppointment.choose_date', array(
            $input_data->choose_date
        ))
            ->getQuery()
            ->execute();

        if (count($collection) > 0)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $collection]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'data not available']);
        }

    }

    public function getappointment()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
        /// $colloction =  SalesmanAppointment::findFirstByid($input_data->id);
        $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesmanAppointment.id as apointmentid',
			'SalesmanAppointment.id',
            'SalesmanAppointment.first_name',
            'SalesmanAppointment.last_name',
            'SalesmanAppointment.email',
            'SalesmanAppointment.mno',
            'SalesmanAppointment.parent_role',
            'SalesmanAppointment.company',
            'States.name as statename',
            'Cities.name as cityname',
            'Countries.name as countryname',
            'SalesmanAppointment.country',
            'SalesmanAppointment.state',
            'SalesmanAppointment.city',
            'SalesmanAppointment.dob',
            'SalesmanAppointment.gender',
            'SalesmanAppointment.grade',
            'SalesmanAppointment.who_intrest',
            'SalesmanAppointment.created_at',
            'SalesmanAppointment.day_id',
            'SalesmanAppointment.status',
            'SalesmanAppointment.nots',
            'SalesmanAppointment.meeting_link',
            'SalesmanAppointment.meeting_status',
            'SalesmanAppointment.choose_date',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',
			'Grade.grade_name'
        ))
            ->from("SalesmanAppointment")
			->leftjoin('Grade', 'Grade.id = SalesmanAppointment.grade')
            ->leftjoin('States', 'States.id = SalesmanAppointment.state')
            ->leftjoin('Countries', 'Countries.id = SalesmanAppointment.country')
            ->leftjoin('Cities', 'Cities.id = SalesmanAppointment.city')
            ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesmanAppointment.day_id')
            ->inwhere('SalesmanAppointment.id', array(
            $input_data->id
        ))

            ->getQuery()
            ->execute();
        if ($colloction)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }

    public function appointmentdetailsbyuserid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesMeetingAvailability.id',
            'SalesCenterMap.user_id',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',
        ))->from('SalesMeetingAvailability')
		->leftjoin('SalesCenterMap','SalesCenterMap.center_id = SalesMeetingAvailability.center_id')
        ->inwhere('SalesCenterMap.user_id', array(
            $input_data->user_id
        ))->getQuery()->execute();

        if (count($collection) > 0)
        {
            $avaiable = array();
            foreach ($collection as $value)
            {
                $school = $this
                    ->modelsManager
                    ->createBuilder()
                    ->columns(array(
                    'SalesmanAppointment.id as apointmentid',
                    'SalesmanAppointment.first_name',
                    'SalesmanAppointment.last_name',
                    'SalesmanAppointment.email',
                    'SalesmanAppointment.mno',
                    'SalesmanAppointment.parent_role',
                    'SalesmanAppointment.company',
                    'SalesmanAppointment.state',
                    'SalesmanAppointment.city',
                    'SalesmanAppointment.dob',
                    'SalesmanAppointment.gender',
                    'SalesmanAppointment.grade',
                    'SalesmanAppointment.who_intrest',
                    'SalesmanAppointment.created_at',
                    'SalesmanAppointment.day_id',
                    'SalesmanAppointment.status',

                ))
                    ->from("SalesmanAppointment")
                    ->inwhere('SalesmanAppointment.day_id', array(
						$value->id
					))
					->inwhere('SalesmanAppointment.officer_id', array(
						 $input_data->user_id
					))
					->inwhere('SalesmanAppointment.choose_date', array(
						$input_data->choose_date
					))

                    ->getQuery()
                    ->execute();

                if (count($school) > 0)
                {
                    foreach ($school as $key)
                    {

                        if ($key->status == 1)
                        {
                            $data['status'] = "Scheduled";
                        }
                        else
                        {
                            $data['status'] = "Canceled";
                        }

                        $data['apointmentid'] = $key->apointmentid;
                        $data['first_name'] = $key->first_name;
                        $data['last_name'] = $key->last_name;
                        $data['email'] = $key->email;
                        $data['mno'] = $key->mno;
                        $data['parent_role'] = $key->parent_role;
                        $data['company'] = $key->company;
                        $data['state'] = $key->state;
                        $data['city'] = $key->city;
                        $data['dob'] = $key->dob;
                        $data['gender'] = $key->gender;
                        $data['grade'] = $key->grade;
                        $data['who_intrest'] = $key->who_intrest;
                        $data['created_at'] = $key->created_at;
                        $data['day_id'] = $key->day_id;

                    }
                }
                else
                {
                    $data['status'] = "Free";

                    $data['apointmentid'] = "";
                    $data['first_name'] = "";
                    $data['last_name'] = "";
                    $data['email'] = "";
                    $data['mno'] = "";
                    $data['parent_role'] = "";
                    $data['company'] = "";
                    $data['state'] = "";
                    $data['city'] = "";
                    $data['dob'] = "";
                    $data['gender'] = "";
                    $data['grade'] = "";
                    $data['who_intrest'] = "";
                    $data['created_at'] = "";
                    $data['day_id'] = "";

                }
				$data['id'] = $value->id;
                $data['user_id'] = $value->user_id;
                $data['choose_date'] = $input_data->choose_date;
                $data['start_time'] = $value->start_time;
                $data['end_time'] = $value->end_time;

                $avaiable[] = $data;

            }
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $avaiable]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available"]);
        }
    }

    public function getcenteravailability()
    {

        $input_data = $this->request->getJsonRawBody();
		$center = SalesCenterMap::findFirstByuser_id($input_data->user_id);
        /// $colloction =  SalesmanAppointment::findFirstByid($input_data->id);
		if($input_data -> choose_date == date('Y-m-d')){
			$time = date('H:s:i');
			 $colloction = $this->modelsManager->createBuilder()->columns(array(
				'SalesMeetingAvailability.start_time',
				'SalesMeetingAvailability.end_time',
				'SalesMeetingAvailability.id as availablity_id',
			))->from("SalesMeetingAvailability")
			->leftjoin('SalesCenter', 'SalesMeetingAvailability.center_id = SalesCenter.id')
			->where('SalesMeetingAvailability.start_time >= "' . $time .'"')
			->inwhere('SalesCenter.id', array(
				$center->center_id
			))->getQuery()->execute();
		} else {
			 $colloction = $this->modelsManager->createBuilder()->columns(array(
				'SalesMeetingAvailability.start_time',
				'SalesMeetingAvailability.end_time',
				'SalesMeetingAvailability.id as availablity_id',
			))->from("SalesMeetingAvailability")
			->leftjoin('SalesCenter', 'SalesMeetingAvailability.center_id = SalesCenter.id')
			->inwhere('SalesCenter.id', array(
				$center->center_id
			))->getQuery()->execute();
		}
       
        $availablityvalue = array();
        foreach ($colloction as $key)
        {
			if(empty($input_data->select_user)){
				# code...
				
				 $uservalue = $this->modelsManager->createBuilder()->columns(array(
					'SalesCenterMap.user_id as user_ids',
				))->from("SalesCenterMap")
				->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
				->inwhere('SalesCenterMap.center_id', array(
					$center->center_id
				))
				->inwhere('Users.user_type', array(
					"development_officer","dev_enroll_officer"
				))->getQuery()->execute();
				$getInfo = array();
				if(count($uservalue) <= 0){
					$data['checkinfo'] = $uservalue;
					$data['status'] = false;
				} else {
					$checkvalue = 0;
					$userid = 0;
					$dataarray = array();
					foreach($uservalue as $value){
						$colloction1 = $this->modelsManager->createBuilder()->columns(array(
							'SalesCenterAppointment.status',
							'SalesCenterAppointment.id',
							'SalesCenterAppointment.dev_off_id',
						))->from("SalesCenterAppointment")
						->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
						->leftjoin('Users', 'Users.id = SalesCenterAppointment.dev_off_id')
						->inwhere('SalesCenterAppointment.sales_center_available_id', array(
							$key->availablity_id
						))->inwhere('Users.id', array(
							$value -> user_ids
						))->getQuery()->execute();
						if(count($colloction1) <= 0){
							$checkvalue = 1;
							if($userid == 0){
								$userid = $value -> user_ids;
							}
						} else { 
							foreach($colloction1 as $value2){
								if($value2-> status == 0){
									if($userid == 0){
										$userid = $value -> user_ids;
									}
									$checkvalue = 1;
								}
							}
						}
						$uservaluedata['user_id'] = $value -> user_ids;
						$uservaluedata['checkvalue'] = $checkvalue;
						$uservaluedata['colloction1'] = $colloction1;
						$dataarray[] = $uservaluedata;
					}
					if($checkvalue == 1){
						$data['status'] = false;
						if($userid != 0){
							$data['user_id'] = $userid;
						} else {
							$data['user_id'] = '';
						}
					} else {
						$data['user_id'] = '';
						$data['status'] = true;
					}
					
					$data['checkvalue'] = $checkvalue;
				}
				
			} else {
				$checkvalue = 0;
				$colloction1 = $this->modelsManager->createBuilder()->columns(array(
					'SalesCenterAppointment.status',
					'SalesCenterAppointment.id',
					'SalesCenterAppointment.dev_off_id',
				))->from("SalesCenterAppointment")
				->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
				->leftjoin('Users', 'Users.id = SalesCenterAppointment.dev_off_id')
				->inwhere('SalesCenterAppointment.sales_center_available_id', array(
					$key->availablity_id
				))->inwhere('Users.id', array(
					$input_data->select_user
				))->getQuery()->execute();
				if(count($colloction1) <= 0){
					$checkvalue = 1;
				} else { 
					foreach($colloction1 as $value2){
						if($value2-> status == 0){
							$checkvalue = 1;
						}
					}
				}
				
				if($checkvalue == 1){
					$data['status'] = false;
				} else {
					$data['status'] = true;
				}
				$data['user_id'] = $input_data->select_user;	
				$data['checkvalue'] = $checkvalue;
			}
			$data['checkinfo'] = $dataarray;
            $data['date'] = $key->choose_date;
            $data['start_time'] = $key->start_time;
            $data['end_time'] = $key->end_time;
            $data['availablity_id'] = $key->availablity_id;
            $availablityvalue[] = $data;

        }

        if ($colloction)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $availablityvalue]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }
	
	
	public function rescheduledmeeting(){
		$input_data = $this->request->getJsonRawBody();
		$appointmentinfo = SalesmanAppointment::findFirstByid($input_data -> id);
		$appointmentinfo -> status = 0;
		if(!$appointmentinfo -> save()){
			return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
		} else{
			//$userinfo = Users::findFirstByid($appointmentinfo -> coustomer_id);
			$gettime = SalesMeetingAvailability::findFirstByid($appointmentinfo -> day_id);
			$timestamp = strtotime($appointmentinfo->choose_date);
                $new_date = date("d-m-Y", $timestamp);
				$date = $gettime->start_time; 
				$time = date('h:i A ', strtotime($date));
                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($appointmentinfo->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your NC Virtual Information Session Appointment Cancellation';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>YOUR NIDARA-CHILDREN VIRTUAL INFORMATION SESSION </h3>
      <h3>APPOINTMENT CANCELLATION CONFIRMATION</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $appointmentinfo->first_name . ' ,</p> 
<br>

        <p>Your NC Virtual Information Session had to be cancelled due to unforeseen circumstances.  We apologize for the inconvenience caused. Please book a new appointment by clicking on the button below.
</p>

        <p> Date : ' . $new_date . ' </p>

        <p> Time : ' . $time . ' </p>
<br>
        <p>To Book a New Appointment, click the button below:</p>
<br>
        <div class="click-but">
        <div class="but">
          <a href="' . $this
                    ->config->appointmenturl . '"> <span>BOOK AN APPOINTMENT</span> </a>
        </div>
      </div>



        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);

                }
		}
	}
	
	public function sendemailconfirmation(){
		 $input_data = $this->request->getJsonRawBody();
		 $userifo = Users::findFirstByid($input_data -> user_id);
				$colloction1 = $this->modelsManager->createBuilder()->columns(array(
					'NidaraKidProfile.first_name',
					'SalesCenterAppointment.id',
					'SalesMeetingAvailability.start_time',
					'SalesCenterAppointment.choose_date as date'
				))->from("SalesCenterAppointment")
				->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
				->leftjoin('NidaraKidProfile', 'SalesCenterAppointment.child_id = NidaraKidProfile.id')
				->inwhere('SalesCenterAppointment.coustomer_id', array(
					$input_data -> user_id
				))->getQuery()->execute();
				$emailContat = "<div>";
				foreach($colloction1 as $value){
					$timestamp = strtotime($value->date);
					$new_date = date("d-m-Y", $timestamp);
					$emailContat .= "<p> Child Name: " . $value -> first_name  ." </p> <p> Date : " . $new_date . " </p> <p> Time : " . $value->start_time . " </p>";
				}
				$emailContat .= "</div>";
				

                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($userifo->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your NC Orientation Session Confirmation';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html>
				<head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8" />
				<style type="text/css">
				.ReadMsgBody {width: 100%;}
				.ExternalClass {width: 100%;}

				body {
				  color: #666666;
				}
					.page-title {
					  text-align: center;
					  width: 100%;
					  float: left;
					  color: #83d0c9;
					}
						   body,td{
							font-family:verdana,geneva;
							font-size:12px;
						   }
						   body{
							background:#fff;
							padding:20px;
						   }
						   .top-img{
							width:100%;
							text-align:center;
							padding-bottom:0;
							font-size:10px;
						   }
						   .sub-mail-cont{
							width:100%;
						   }
						   .sub-mail-vr{
							width:580px;
							margin:auto;
							float:none;
						   }
						   .main-page-mail{
							width:100%;
							float:left;
							padding:20px;
							border:1px solid #999;
						   }
						   .sub-mail-but{
							width:100%;
							text-align:center;
							padding-top:30px;
							float:left;
						   }
						   a.sub-but{
							text-decoration:none;
							color:#333;
							padding:10px 50px;
							border:1px solid;
						   }
						   .sub-but-cont{
							width:100%;
							padding-top:20px;
							float:left;
						   }
						   .footer{
							width:100%;
							text-align:center;
							font-size:10px;
							padding-top:20px;
							float:left;
						   }
						   .footer ul{
							list-style:none;
							float:left;
							margin:15px 10px;
							width:100%;
							padding:0;
						   }
						   .footer ul li{
							display:inline-flex;
							padding-left:5px;
						   }
						   p{
							line-height:18px;
						   }
						   .small{
							font-size:11px;
						   }
						   .main-title{
							text-align:center;
							color:#aed7d3;
							float:left;
							width:100%;
						   }
						   .main-title h3{
							font-weight:500;
						   }
						   .first-name{
							text-transform:capitalize;
						   }
						   .product-img{
							width:20%;
							float:left;
							padding-right:20px;
						   }
						   .product-img img{
							width:100%;
						   }
						   .product-cont{
							width:75%;
							float:left;
						   }
						   .product-details{
							width:100%;
							float:left;
						   }
					   .page-content {
						width: 100%;
						float: left;
						color: #666666;
					  }
					  .click-but {
						width: 100%;
						float: left;
						text-align: center;
					  }
					  .click-but .but{
						width:250px;
						display:block;
						margin:auto;
					  }
					  .click-but .but a {
						text-decoration: none;
						padding: 10px;
						color: #fff;
					  }
					  .click-but .but {
						display: block;
						margin: auto;
						width: 265px;
						padding: 15px;
						background: #333333;
						font-size: 15px;
						float: left;
					  }
					  .click-but .but:hover{
						background: #83d0c9;
					  }
						 
				span.yshortcuts { color:#000; background-color:none; border:none;}
				span.yshortcuts:hover,
				span.yshortcuts:active,
				span.yshortcuts:focus {color:#000; background-color:none; border:none;}
				</style>
				</head>
				<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
				,geneva; font-size: 12px; background: #fff; padding: 20px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

						  
						  <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
							<div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
						   <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
							 <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
						   </div>
					   <div class="page-title">
					  <h3>YOUR NIDARA-CHILDREN VIRTUAL ORIENTATION SESSION APPOINTMENT CONFIRMATION</h3>
					   </div>
					  <div class="page-content">
						
						<p>Dear ' . $userifo -> first_name . ' ,</p> 
				<br>

						<p>Your NC Orientation Information Session has been confirmed as below:</p>
						' . $emailContat .'

						
				<br>
						<p>We will send you a separate virtual meeting invite in your email.</p>
				<br>
						
				<p>To Modify Your Orientation Appointment, please contact our NC Enrollment Officer.</p>
			<br>


						<p>We look forward to helping you give your child the best start in life.</p>

						

					  </div>
					  
							
							<div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
						   <p style="line-height: 18px;">Best regards,</p>
						   <p style="line-height: 18px;">
							</p>
							<p style="line-height: 18px;">Nidara-Children</p>
						  </div>
						  <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
							<ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
						<li style="display: inline-flex; padding-left: 5px;">
						 <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
						 </li>
						 <li style="display: inline-flex; padding-left: 5px;">
						 <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
						 </li>
						 <li style="display: inline-flex; padding-left: 5px;">
						 <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
						 </li>
						 <li style="display: inline-flex; padding-left: 5px;">
						 <a class="email" href="' . $this
									->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
						 </li>
						   </ul>
					  <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
						<br /><span>You are receiving this email because you opted in at our website.
							</span>
							<br /><span>
					  <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
					  </span>
						  </div>
						   </div>
						 </div>
						 
				</td></tr></table>
				</body>
				</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => true, 'message' => 'Message hase be sent.']);

                }
	}

    public function getcenteravailabilitygroupbydate()
    {
		
		$input_data = $this
            ->request
            ->getJsonRawBody();
		//$centerinfo = SalesCenter::findFirstByuser_id($input_data -> user_id );
		
		$date = date('Y-m-d');
		$d = cal_days_in_month(CAL_GREGORIAN,$input_data -> month,$input_data -> year);
		//$dayvalue = 0;
		$year = date('Y');
		$month = date('m');
		$day = date('d');
		$i = $input_data -> day;
		$datearray = array();
		for($i = $input_data -> day; $i <= $d; $i++){
			if($input_data -> month <= 9){
				$monthset = '0' . $input_data -> month;
			} else {
				$monthset = $input_data -> month;
			}
			if($i <= 9){
				$dayset = '0'. $i;
			} else {
				$dayset = $i;
			}
			$choose_date = $input_data -> year . '-' . $monthset . '-' . $dayset;
			$uservalue = $this->modelsManager->createBuilder()->columns(array(
				'SalesCenterMap.user_id as user_ids',
			))->from("SalesCenterMap")
			->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
			->inwhere('SalesCenterMap.user_id', array(
				$input_data -> user_id
			))
			->inwhere('Users.user_type', array(
				"development_officer","dev_enroll_officer"
			))->getQuery()->execute();
			$checkstatus = 0;
			$userarray = array();
			foreach($uservalue as $value){
				$meetingtime = SalesMeetingAvailability::findBycenter_id($input_data -> id);
				foreach($meetingtime as $meetingvalue){
					$school = $this->modelsManager->createBuilder()->columns(array(
						'SalesCenterAppointment.day_id',
					))->from("SalesCenterAppointment")
					->inwhere('SalesCenterAppointment.status', array(
						1
					))->inwhere('SalesCenterAppointment.dev_off_id', array(
								$value -> user_ids
					))->inwhere('SalesCenterAppointment.sales_center_available_id', array(
								$meetingvalue -> id
					))->inwhere('SalesCenterAppointment.choose_date', array(
								$choose_date
					))->getQuery()->execute();
					if(count($school) <= 0){
						$checkstatus = 1;
					}
				}
				$userinfo['user_id'] = $value -> user_ids;
				$userinfo['checkstatus'] = $checkstatus;
				$userinfo['$school'] = $school;
				$userarray[] = $userinfo;
			}
			if($checkstatus == 1){
				$datavalue['status'] = false;
			} else {
				$datavalue['status'] = true;
			}
			
			$datavalue['id'] = $i;
			$datavalue['userinfo'] = $userarray;
			$datavalue['choose_date'] = $choose_date;
			$datearray[] = $datavalue;
		}
		return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $datearray]);

        /// $colloction =  SalesmanAppointment::findFirstByid($input_data->id);
       /*  $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesCenterAvailability.date as choose_date',
            'SalesCenterAvailability.id',
        ))
            ->from("SalesCenterAvailability")
            ->leftjoin('SalesCenterMap', 'SalesCenterAvailability.center_id = SalesCenterMap.center_id')
            ->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
            ->inwhere('SalesCenterMap.user_id', array(
            $input_data->user_id
        ))
            ->inwhere('Users.user_type', array(
            "coordinator","dev_enroll_officer"
        ))
            ->groupby('SalesCenterAvailability.date')
            ->getQuery()
            ->execute(); */
       /* foreach ($colloction as $key)
        {
            # code...
            

            $colloction1 = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesCenterAppointment.id',
                'SalesCenterAppointment.status',
            ))
                ->from("SalesCenterAppointment")
                ->leftjoin('SalesCenterAvailability', 'SalesCenterAvailability.center_id = SalesCenterAppointment.sales_center_available_id')
            //->inwhere('SalesCenterAppointment.sales_center_available_id', array($key->id))
            
                ->inwhere('SalesCenterAppointment.status', array(
                1
            ))
                ->inwhere('SalesCenterAvailability.date', array(
                $key->choose_date
            ))

                ->getQuery()
                ->execute();

            $countcolloction = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesCenterAvailability.date as choose_date',
                'SalesCenterAvailability.id',
            ))
                ->from("SalesCenterAvailability")
                ->leftjoin('SalesCenterMap', 'SalesCenterAvailability.center_id = SalesCenterMap.center_id')
                ->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
                ->inwhere('SalesCenterMap.user_id', array(
                $input_data->user_id
            ))
                ->inwhere('Users.user_type', array(
                "coordinator"
            ))
                ->inwhere('SalesCenterAvailability.date', array(
                $key->choose_date
            ))
                ->getQuery()
                ->execute();


                return $this
                ->response
                ->setJsonContent(['status' => $colloction1, 'data' => $countcolloction]);

            $totstatus = 0;
            if (count($colloction1) > 0)
            {
                foreach ($colloction1 as $value)
                {
                    $totstatus = $value->status + $totstatus;
                }

                if (count($countcolloction) == $totstatus)
                {
                    $data['status'] = true;
                }
                else
                {
                    $data['status'] = false;
                }

            }
            else
            {
                $data['status'] = false;
            }

            $data['date'] = $key->choose_date;

            $availablityvalue[] = $data;

        }
           */
		   
       /*  if ($colloction)
        {
			$datacheckarray = array();
			foreach($colloction as $key){
				$countcolloction = $this->modelsManager->createBuilder()->columns(array(
					 'SalesCenterAppointment.id',
					 'SalesCenterAppointment.status',
				))->from("SalesCenterMap")
				->leftjoin('SalesCenterAvailability', 'SalesCenterAvailability.center_id = SalesCenterMap.id')
                ->leftjoin('SalesCenterAppointment', 'SalesCenterAvailability.id = SalesCenterAppointment.sales_center_available_id')
				->inwhere('SalesCenterMap.user_id', array(
					$input_data->user_id
				))->inwhere('SalesCenterAppointment.sales_center_available_id', array(
					 $key->id
				))->getQuery()->execute();
				foreach($countcolloction as $value){
					$datavalue['id'] = $value -> id;
					$datavalue['status'] = $value -> status;
					$datacheckarray[] = $datavalue;
				}
				$data['id'] = $key -> id;
				$data['choose_date'] = $key -> choose_date;
				$data['datacheckarray'] = $datacheckarray;
				$availablityvalue[] = $data;
			}
           
        } */
		/* if(){
			 
		}
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        } */
    }

    public function createsalescenterappointment()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

			foreach ($input_data->createInfo as  $value) {
				# code...

					$collection = new SalesCenterAppointment();

					$collection->dev_off_id = $value->dev_off_id;
					$collection->choose_date = $value->choose_date;
					$collection->coustomer_id = $value->coustomer_id;
					$collection->child_id = $value->child_id;
					$collection->sales_center_available_id = $value->sales_center_available_id;
					$collection->status = 1;


					if (!$collection->save())

					{
						return $this
							->response
							->setJsonContent(['status' => false, 'data' => $input_data]);

					}

			}
        
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => "Saved Successfully"]);
      
       

    }

    public function getappointmentbydevelopmentofficer()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();
        /// $colloction =  SalesmanAppointment::findFirstByid($input_data->id);
        $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesCenterAvailability.id',
            'SalesCenterAvailability.start_time',
            'SalesCenterAvailability.end_time',

        ))
            ->from("SalesCenterMap")
            ->leftjoin('SalesCenterAvailability', 'SalesCenterAvailability.id = SalesCenterMap.center_id')
            ->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
            ->inwhere('SalesCenterMap.user_id', array(
            $input_data->user_id
        ))
            ->inwhere('Users.user_type', array(
            "development_officer","dev_enroll_officer"
        ))
            ->getQuery()
            ->execute();

        $availablityvalue = array();

        foreach ($colloction as $key)
        {
            # code...
            $colloction1 = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesCenterAppointment.id as apointmentid',
                'SalesCenterAppointment.dev_off_id',
                'SalesCenterAppointment.coustomer_id',
                'Users.first_name',
                'Users.last_name',
                'Users.email',

            ))
                ->from("SalesCenterAppointment")
                ->leftjoin('Users', 'Users.id = SalesCenterAppointment.coustomer_id')
                ->inwhere('SalesCenterAppointment.sales_center_available_id', array(
                $key->id
            ))
                ->inwhere('SalesCenterAppointment.status', array(
                1
            ))
                ->getQuery()
                ->execute();

            foreach ($colloction1 as $appointmentvalue)
            {

                $data['dev_off_id'] = $appointmentvalue->dev_off_id;
                $data['coustomer_id'] = $appointmentvalue->coustomer_id;
                $data['first_name'] = $appointmentvalue->first_name;
                $data['last_name'] = $appointmentvalue->last_name;
                $data['email'] = $appointmentvalue->email;
                $data['salescenteravailability_id'] = $key->id;
                $data['appointment_id'] = $appointmentvalue->apointmentid;
                $data['start_time'] = $key->start_time;
                $data['end_time'] = $key->end_time;

                $availablityvalue[] = $data;

                # code...
                
            }

        }

        if ($colloction)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $availablityvalue]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Failed']);
        }
    }

    public function salescenterappointmentsavenotes()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = SalesCenterAppointment::findFirstByid($input_data->id);



        $collection->note = $input_data->note;

        if ($collection->save())
        {


            $availablity=SalesMeetingAvailability::findFirstByid($collection->sales_center_available_id);



         if($availablity)
         {

             $meetingid = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesMeetingAvailability.id ',
    
            ))
                ->from("SalesMeetingAvailability")
                 ->inwhere('SalesMeetingAvailability.start_time', array(
                $availablity->start_time
            ))
                 ->inwhere('SalesMeetingAvailability.center_id', array(
                $availablity->center_id
            ))
                
                ->getQuery()
                ->execute();


            $collectionval = new SalesMeetingAppointment();
            

            $collectionval->dev_off_id = $collection->dev_off_id;
            $collectionval->meeting_no = 1;
            $collectionval->coustomer_id = $collection->coustomer_id;
            $collectionval->child_id = $collection->child_id;
            $collectionval->apo_date = $collection->choose_date;
            $collectionval->note = $collection->note;
            $collectionval->meeting_link = $collection->meeting_link;
            $collectionval->meeting_status = $collection->meeting_status;
            $collectionval->apo_time = $meetingid[0]->id;
            $collectionval->status = 1;

            
            
            if ($collectionval->save())
            {
               return $this
                ->response
                ->setJsonContent(['status' => true, 'massege' => "Success", 'data' => $collection ]);
            }
            else
            {

               return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => $collectionval ]);  
            }


        }
            
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "Failed"]);

        }

    }

    public function createmeetingappointment()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

           

        foreach ($input_data->meetingInfo as $meetingdata)
        {

            
            $collection = SalesMeetingAppointment::findFirstByid($meetingdata->id);



            if(!$collection)
            {
            $collection = new SalesMeetingAppointment();
            }

            $collection->dev_off_id = $meetingdata->dev_off_id;
            $collection->meeting_no = $meetingdata->meeting_no;
            $collection->coustomer_id = $meetingdata->coustomer_id;
            $collection->child_id = $meetingdata->child_id;
            $collection->apo_date = $meetingdata->apo_date;
            $collection->apo_time = $meetingdata->apo_time;
            $collection->status = 1;

            
            
            if (!$collection->save())
            {
                return $this
                    ->response
                    ->setJsonContent(['status' => $collection, 'data' => "faild"]);
            }
        }

        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => "Success"]);

    }

    public function getsalesmeetingavailablity()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesMeetingAvailability.id',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time',

        ))
            ->from("SalesCenterMap")
            ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterMap.center_id')
            ->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
            ->inwhere('SalesCenterMap.user_id', array(
            $input_data->user_id
        ))
            ->inwhere('Users.user_type', array(
            "development_officer","dev_enroll_officer"
        ))
            ->getQuery()
            ->execute();

        if ($colloction)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "Data Not Available"]);

        }

    }

    public function viewkidprofile()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();



       // $kid = NidaraKidProfile::findFirstByid($input_data->nidara_kid_profile_id);
            $kid = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.id as child_id',
            'NidaraKidProfile.first_name',
            'NidaraKidProfile.middle_name',
            'NidaraKidProfile.last_name',
            'NidaraKidProfile.date_of_birth',
            'NidaraKidProfile.age',
            'NidaraKidProfile.gender',
            'NidaraKidProfile.height',
            'NidaraKidProfile.weight',
            'NidaraKidProfile.grade', 
            'NidaraKidProfile.choose_time',
            'NidaraKidProfile.child_photo',
            'Users.first_name as parent_name',
            'SalesCenterAppointment.status',
            'SalesCenterAppointment.meeting_link',
            'SalesCenterAppointment.meeting_status',
            'SalesCenterAppointment.id as appointment_id',
            'SalesCenterAppointment.id as id',
            'SalesCenterAppointment.choose_date as choose_date',
            'SalesMeetingAvailability.start_time as start_time',
            'SalesMeetingAvailability.id as centeravailablity_id',

        ))
            ->from("NidaraKidProfile")
           ->leftjoin('SalesCenterAppointment', 'NidaraKidProfile.id = SalesCenterAppointment.child_id')
           ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
           ->leftjoin('Users', 'Users.id = SalesCenterAppointment.coustomer_id')
            ->inwhere('NidaraKidProfile.id', array(
            $input_data->nidara_kid_profile_id
        ))
              ->getQuery()
            ->execute();

        if (count($kid)>0):
            return $this
                ->response
                ->setJsonContent(['status' => 'true', 'data' => $kid]);
        else:
            return $this
                ->response
                ->setJsonContent(['status' => 'false', 'Message' => 'Faield']);
        endif;
    }
	
	public function getappointmentsearch(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = $this->modelsManager->createBuilder()->columns(array(
            'SalesmanAppointment.id',
            'SalesmanAppointment.first_name',
            'SalesmanAppointment.last_name',
            'SalesmanAppointment.email',
            'SalesmanAppointment.mno as mobile',
            'SalesmanAppointment.choose_date as choose_date',
		))->from("SalesmanAppointment")
		->where('SalesmanAppointment.first_name like "%'.$input_data->search.'%" or
		CONCAT(SalesmanAppointment.first_name," ",SalesmanAppointment.last_name) like "%'.$input_data->search.'%" or SalesmanAppointment.last_name like "%'.$input_data->search.'%" or SalesmanAppointment.email="'.$input_data->search.'" or SalesmanAppointment.mno="'.$input_data->search.'"')
		->getQuery()->execute();
		if(count($colloction) <= 0){
			return $this->response->setJsonContent(['status' => false, 'Message' => 'The parent not get']);
		} else {
			return $this->response->setJsonContent(['status' => true, 'data' => $colloction]);
		}
	}


    public function parentdetails()
    {

$totalval=array();

        $input_data = $this
            ->request
            ->getJsonRawBody();

            $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NcSalesmanParentMap.id',
            'Users.id as parrent_id',
            'Users.first_name',
            'Users.last_name',
            'Users.email',
            'Users.mobile',

        ))
            ->from("NcSalesmanParentMap")
            ->leftjoin('Users', 'NcSalesmanParentMap.user_id = Users.id')
			->where('Users.first_name like "%'.$input_data->search.'%" or Users.last_name like "%'.$input_data->search.'%" or CONCAT(Users.first_name," ",Users.last_name) like "%'.$input_data->search.'%" or
				Users.email="'.$input_data->search.'" or Users.mobile="'.$input_data->search.'"')
            ->inwhere('NcSalesmanParentMap.salesman_id', array(
            $input_data->user_id
        ))
            ->getQuery()
            ->execute();

		if(count($colloction) <= 0){
			return $this->response->setJsonContent(['status' => false, 'Message' => 'The parent not get']);
		}
		else {
            foreach ($colloction as $key) {

                  $colloction1 = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.id',
            'NidaraKidProfile.first_name',
            'NidaraKidProfile.last_name',
          

        ))
            ->from("NidaraKidProfile")
            ->leftjoin('KidParentsMap', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->inwhere('KidParentsMap.users_id', array(
            $key->parrent_id
        ))
            ->getQuery()
            ->execute();
$kidvalues=array();



foreach ($colloction1 as $value) {

$data['kid_first_name']=$value->first_name;
$data['kid_last_name']=$value->last_name;

    # code...

$kidvalues[]=$data;
}

$data2['parrent_id']=$key->parrent_id;
$data2['parrent_first_name']=$key->first_name;
$data2['parrent_last_name']=$key->last_name;
$data2['parrent_email']=$key->email;
$data2['parrent_mobile']=$key->mobile;
$data2['kid']=$kidvalues;

$totalval[]=$data2;
            }
		}

        
        
            return $this->response->setJsonContent(['status' => 'true', 'data' => $totalval]);
        
    }

	public function getKidinfo(){
		$input_data = $this->request->getJsonRawBody();
		 $colloction1 = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.id',
            'NidaraKidProfile.first_name',
            'NidaraKidProfile.last_name',
        ))
            ->from("KidParentsMap")
            ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->inwhere('KidParentsMap.users_id', array(
            $input_data -> user_id
        ))
            ->getQuery()
            ->execute();
			$getArray = array();
			foreach($colloction1 as $value){
				$date['id'] = $value -> id;
				$date['child_id'] = $value -> id;
				$date['first_name'] = $value -> first_name;
				$date['coustomer_id'] = $input_data -> user_id;
				$date['sales_center_available_id'] = '';
				$date['dev_off_id'] = '';
				$getArray[] = $date;
			}
			return $this->response->setJsonContent(['status' => 'true', 'data' => $getArray]);
	}
	
	public function getkidinfobyuserid(){
		$input_data = $this->request->getJsonRawBody();
		$userInfo = $this->modelsManager->createBuilder()->columns(array(
			'Users.id',
			'Users.first_name',
			'Users.email',
			'Users.mobile',
			'SalesmanAppointment.nots',
			'SalesmanAppointment.meeting_link',
		))->from("Users")
		->leftjoin('SalesmanAppointment', 'SalesmanAppointment.email = Users.email')
        ->inwhere('Users.id', array(
			$input_data -> user_id
		))->getQuery()->execute();
		// $userInfo = Users::findFirstByid($input_data -> user_id);
		 $colloction1 = $this->modelsManager->createBuilder()->columns(array(
				'NidaraKidProfile.id',
				'NidaraKidProfile.first_name',
				'NidaraKidProfile.last_name',
				'NidaraKidProfile.grade',
				'NidaraKidProfile.modified_at',
				'NidaraKidProfile.status',
				'Grade.grade_name'
			))->from("KidParentsMap")
            ->leftjoin('NidaraKidProfile', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
			->leftjoin('Grade', 'Grade.id = NidaraKidProfile.grade')
            ->inwhere('KidParentsMap.users_id', array(
				$input_data -> user_id
			))->getQuery()->execute();
			$getArray = array();
			foreach($colloction1 as $value){
				if($value -> status <= 2){
					$colloction2 = $this->modelsManager->createBuilder()->columns(array(
						'NCProductPricing.product_price'
					))->from("ProductSelectProgram")
					->leftjoin('NCProductPricing', 'NCProductPricing.id = ProductSelectProgram.product_id')
					->inwhere('ProductSelectProgram.kid_id', array(
						$value -> id
					))->getQuery()->execute();
					$date['enrollstatus'] = date($value -> modified_at);
					$date['amount'] = $colloction2 -> product_price;
				} else {
					$date['enrollstatus'] = 'Not enrolled';
					$date['amount'] = 'N/A';
				}
				$date['id'] = $value -> id;
				$date['child_id'] = $value -> id;
				$date['first_name'] = $value -> first_name;
				$date['grade_name'] = $value -> grade_name;
				$date['modified_at'] = $value -> modified_at;
				$date['status'] = $value -> status;
				$date['grade'] = $value -> grade;
				$getArray[] = $date;
			}
			return $this->response->setJsonContent(['status' => 'true', 'data' => $getArray, 'userinfo' => $userInfo]);
	}


    public function parentdetailswithfilter()
    {
$totalval=array();
        $input_data = $this
            ->request
            ->getJsonRawBody();

            $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'SalesCenterAppointment.coustomer_id',
            'SalesCenterAppointment.child_id',
            'Users.id as parrent_id',
            'Users.first_name',
            'Users.last_name',
            'Users.email',
            'Users.mobile',

        ))
            ->from("Users")
            ->leftjoin('SalesCenterAppointment', 'SalesCenterAppointment.coustomer_id = Users.id')
            ->where('Users.first_name like "%'.$input_data->search.'%" or Users.last_name like "%'.$input_data->search.'%" or CONCAT(Users.first_name," ",Users.last_name) like "%'.$input_data->search.'%" or
				Users.email="'.$input_data->search.'" or Users.mobile="'.$input_data->search.'"')
            ->groupby('SalesCenterAppointment.coustomer_id')
            ->getQuery()
            ->execute();
			if(count($colloction) <= 0){
				return $this
                ->response
                ->setJsonContent(['status' => false, 'massege' => 'No data']);
			}
            foreach ($colloction as $key) {
if( $key->coustomer_id !=null)
{
                  $colloction1 = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.id',
            'NidaraKidProfile.first_name',
            'NidaraKidProfile.last_name',
          

        ))
            ->from("NidaraKidProfile")
            ->leftjoin('KidParentsMap', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->inwhere('KidParentsMap.users_id', array(
            $key->parrent_id
        ))
            ->getQuery()
            ->execute();

if(count($colloction1) <= 0){
				return $this
                ->response
                ->setJsonContent(['status' => false, 'massege' => 'No data']);
			}
$kidvalues=array();



foreach ($colloction1 as $value) {

$data['kid_first_name']=$value->first_name;
$data['kid_last_name']=$value->last_name;

    # code...

$kidvalues[]=$data;
}

$data2['parrent_id']=$key->parrent_id;
$data2['child_id']=$key->child_id;
$data2['parrent_first_name']=$key->first_name;
$data2['parrent_last_name']=$key->last_name;
$data2['parrent_email']=$key->email;
$data2['parrent_mobile']=$key->mobile;
$data2['kid']=$kidvalues;

$totalval[]=$data2;
            }
}

        
        if(count($colloction) > 0){
			if(count($totalval) <= 0){
				return $this
                ->response
                ->setJsonContent(['status' => false, 'massege' => 'No data']);
			}
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $totalval]);
		} else {
			return $this
                ->response
                ->setJsonContent(['status' => false, 'massege' => 'No data']);
		}
        
    }


 public function getdateuseuserid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
            
            $colloction1 = $this->modelsManager->createBuilder()->columns(array(
                    'SalesCenterAvailability.date'
                ))->from("SalesCenterAppointment")
                ->leftjoin('SalesCenterAvailability', 'SalesCenterAvailability.id = SalesCenterAppointment.sales_center_available_id')
                ->inwhere('SalesCenterAppointment.dev_off_id', array(
                    $input_data -> user_id
                ))
                ->inwhere('SalesCenterAppointment.status', array(
                    1
                ))
                ->groupby('SalesCenterAvailability.date')
                ->getQuery()->execute();

 
        if (count($colloction1)>0)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction1]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Data Not Found']);
        }
    }   

 public function getdateuseuseriddate()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
            
          /*  $colloction1 = $this->modelsManager->createBuilder()->columns(array(
                    'SalesCenterAvailability.date',
                    'SalesCenterAvailability.start_time',
                    'NidaraKidProfile.first_name',
                    'NidaraKidProfile.id as child_id',
                    'SalesCenterAppointment.coustomer_id',
                    'Users.first_name as customer_name'
                ))->from("SalesCenterAppointment")
                ->leftjoin('SalesCenterAvailability', 'SalesCenterAvailability.id = SalesCenterAppointment.sales_center_available_id')
                ->leftjoin('NidaraKidProfile', 'SalesCenterAppointment.child_id = NidaraKidProfile.id')
                ->leftjoin('Users', 'Users.id = SalesCenterAppointment.coustomer_id')
                ->inwhere('SalesCenterAppointment.dev_off_id', array(
                    $input_data -> user_id
                ))
                ->inwhere('SalesCenterAvailability.date', array(
                    $input_data -> choose_date
                ))
                ->inwhere('SalesCenterAppointment.status', array(
                    1
                ))
                ->getQuery()->execute();*/

                $colloction1 = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAvailability.id',
                    'SalesCenterAppointment.choose_date as choose_date',
                    'SalesMeetingAvailability.start_time',
                ))->from("SalesCenterMap")
                ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.center_id = SalesCenterMap.center_id')
				->leftjoin('SalesCenterAppointment', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
              //  ->leftjoin('NidaraKidProfile', 'SalesCenterAppointment.child_id = NidaraKidProfile.id')
              //  ->leftjoin('Users', 'Users.id = SalesCenterAppointment.coustomer_id')
                ->inwhere('SalesCenterMap.user_id', array(
                    $input_data -> user_id
                ))
                ->inwhere('SalesCenterAppointment.choose_date', array(
                    $input_data -> choose_date
                ))

                ->getQuery()->execute();

$data=array_push();


                
                foreach ($colloction1 as $key ) {
                    # code...

                    $colloctionfinal = $this->modelsManager->createBuilder()->columns(array(
                    'SalesCenterAppointment.choose_date as choose_date',
                    'SalesMeetingAvailability.start_time',
                    'NidaraKidProfile.first_name',
                    'NidaraKidProfile.id as child_id',
                    'SalesCenterAppointment.coustomer_id',
                    'SalesCenterAppointment.status',
                  //  'Users.first_name as customer_name'
                ))->from("SalesCenterAppointment")
                ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesCenterAppointment.sales_center_available_id')
                ->leftjoin('NidaraKidProfile', 'SalesCenterAppointment.child_id = NidaraKidProfile.id')
                //->leftjoin('Users', 'Users.id = SalesCenterAppointment.coustomer_id')
                ->inwhere('SalesCenterAppointment.sales_center_available_id', array(
                    $key -> id
                ))

                ->getQuery()->execute();


                if(count($colloctionfinal) > 0)
                {
                    foreach ( $colloctionfinal as $value) 
                    {
                        # code...
                        $apdata['id']=$key->id;
                        $apdata['choose_date']=$value->choose_date;
                        $apdata['start_time']=$value->start_time;
                        $apdata['first_name']=$value->first_name;
                        $apdata['child_id']=$value->child_id;
                        $apdata['coustomer_id']=$value->coustomer_id;

                        if($value->status==1)
                        {
                        $apdata['status']="Scheduled";
                        }
                        else
                        {
                          $apdata['status']="Canceled";  
                        }

                        $data[]=$apdata;

                    }


                }
                else
                {
                         $apdata['id']=$key->id;
                         $apdata['choose_date']=$key->choose_date;
                        $apdata['start_time']=$key->start_time;
                        $apdata['first_name']="";
                        $apdata['child_id']="";
                        $apdata['coustomer_id']="";
                        $apdata['status']="Free";

                        $data[]=$apdata;



                }


                }

 
        if (count($data)>0)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $data]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Data Not Found']);
        }
    }


     public function getmeetingdateuseuserid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
            
            $colloction1 = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.apo_date'
                ))->from("SalesMeetingAppointment")
                ->inwhere('SalesMeetingAppointment.dev_off_id', array(
                    $input_data -> user_id
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                    1
                ))
                ->groupby('SalesMeetingAppointment.apo_date')
                ->getQuery()->execute();

 
        if (count($colloction1)>0)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction1]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Data Not Found']);
        }
    } 


     public function getmeetinguseiddate()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();
            
            $colloction1 = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.id',
                    'NidaraKidProfile.first_name',
                    'Users.first_name as customer_name',
                    'SalesMeetingAppointment.coustomer_id',
                    'SalesMeetingAppointment.meeting_no',
                    'SalesMeetingAppointment.apo_date',
                     'SalesMeetingAvailability.id as meeting_time_id',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAvailability.end_time'
                ))->from("SalesMeetingAppointment")
                 ->leftjoin('NidaraKidProfile', 'SalesMeetingAppointment.child_id = NidaraKidProfile.id')
                ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                 ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAvailability.id = SalesMeetingAppointment.apo_time')
                ->inwhere('SalesMeetingAppointment.dev_off_id', array(
                    $input_data -> user_id
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                    1
                ))
                ->inwhere('SalesMeetingAppointment.apo_date', array(
                     $input_data -> choose_date
                ))
                ->getQuery()->execute();

 
        if (count($colloction1)>0)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction1]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Data Not Found']);
        }
    } 


     public function getcenteravailabilitybyuserid()
    {


         $input_data = $this
            ->request
            ->getJsonRawBody();
        $uservalue = $this->modelsManager->createBuilder()->columns(array(
                    'SalesCenterAppointment.choose_date as choose_date',
                ))->from("SalesCenterAppointment")
                ->inwhere('SalesCenterAppointment.dev_off_id', array(
                   $input_data-> user_id
                ))
                ->inwhere('SalesCenterAppointment.status', array(
                   1
                ))
                ->getQuery()->execute();

                if(count($uservalue) > 0)
                {
                    return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $uservalue ]);
                }
                else
                {
                    return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "Data Not Found" ]);
                }
    }

    public function getmeetingavailabledate()
    {


         $input_data = $this
            ->request
            ->getJsonRawBody();
        $uservalue = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.apo_date as choose_date',
                ))->from("SalesMeetingAppointment")
               
                ->inwhere('SalesMeetingAppointment.dev_off_id', array(
                   $input_data-> user_id
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                   1
                ))
                ->groupby('SalesMeetingAppointment.apo_date')
                ->getQuery()->execute();

                if(count($uservalue) > 0)
                {
                    return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $uservalue ]);
                }
                else
                {
                    return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "Data Not Found" ]);
                }
    }


    public function getmeetingavailabledatebydate()
    {


         $input_data = $this
            ->request
            ->getJsonRawBody();
        

         $centermap=$this->modelsManager->createBuilder()->columns(array(
                    'SalesCenterMap.center_id',
                ))->from("SalesCenterMap")
                ->inwhere('SalesCenterMap.user_id', array(
                   $input_data-> user_id
                ))
                ->getQuery()->execute();  


         $available=$this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAvailability.id',
                    'SalesMeetingAvailability.start_time',
                    'SalesMeetingAvailability.end_time',
                ))->from("SalesMeetingAvailability")
                ->inwhere('SalesMeetingAvailability.center_id', array(
                   $centermap[0]->center_id
                ))
                ->getQuery()->execute();         


$data=array();
                foreach ($available as $value) {

                    $uservalue = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.apo_date as choose_date',
                    'SalesMeetingAppointment.apo_time',
                    'NidaraKidProfile.first_name',
                    'Users.first_name as parent_name',
                    'SalesMeetingAppointment.apo_date as choose_date',
                    'SalesMeetingAppointment.status',
                    'SalesMeetingAppointment.id as meetingid',
                ))->from("SalesMeetingAppointment")
                ->leftjoin('NidaraKidProfile', 'SalesMeetingAppointment.child_id = NidaraKidProfile.id')
                 ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                ->inwhere('SalesMeetingAppointment.dev_off_id', array(
                   $input_data-> user_id
                ))
                ->inwhere('SalesMeetingAppointment.apo_date', array(
                   $input_data-> choose_date
                ))
                ->inwhere('SalesMeetingAppointment.apo_time', array(
                   $value-> id
                ))
                ->getQuery()->execute();


                if(count($uservalue )>0)
                {
                foreach ($uservalue  as $key) 
                {

                    $vals['choose_date']=$input_data-> choose_date;
                    $vals['apo_time']=$key->apo_time;
                    $vals['first_name']=$key->first_name;
                    $vals['parent_name']=$key->parent_name;
                     $vals['start_time']=$value->start_time;
                      $vals['end_time']=$value->end_time;
                      $vals['meeting_id']=$key->meetingid;

                    if($key->status == 1)
                    {
                   $vals['status']="Scheduled";
                        }
                        else
                        {
                          $vals['status']="Canceled";  
                        }


                        $data[]=$vals;
                   
                }
            }
            else
            {
                    $vals['choose_date']=$input_data-> choose_date;
                    $vals['apo_time']=$value->id;
                    $vals['first_name']="";
                    $vals['parent_name']="";
                    $vals['status']="Free";
                    $vals['start_time']=$value->start_time;
                      $vals['end_time']=$value->end_time;
                       $vals['meeting_id']="";

                      $data[]=$vals;
            }
                    
                }

                
                    return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $data ]);
                
               
    }



   public function kidmeetingdetails()
    {

$input_data = $this
            ->request
            ->getJsonRawBody();



            //$meetingDetails=SalesMeetingAppointment::findFirstByid($input_data->id);




            /*if($meetingDetails)
            {*/
                     $kid = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.id',
            'NidaraKidProfile.first_name',
            'NidaraKidProfile.middle_name',
            'NidaraKidProfile.last_name',
            'NidaraKidProfile.date_of_birth',
            'NidaraKidProfile.age',
            'NidaraKidProfile.gender',
            'NidaraKidProfile.height',
            'NidaraKidProfile.weight',
            'NidaraKidProfile.grade', 
            'NidaraKidProfile.choose_time',
            'NidaraKidProfile.child_photo',
            'Users.first_name as parent_name',
            'SalesMeetingAppointment.apo_date as choose_date',
            'Users.first_name as parent_name',
            'SalesMeetingAvailability.start_time',
            'SalesMeetingAppointment.status',
            'SalesMeetingAppointment.meeting_link as meeting_link',
            'SalesMeetingAppointment.meeting_status as meeting_status',
            'SalesMeetingAppointment.note as note',

        ))
            ->from("NidaraKidProfile")
            ->leftjoin('SalesMeetingAppointment', 'NidaraKidProfile.id = SalesMeetingAppointment.child_id')
           ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
           ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAppointment.apo_time = SalesMeetingAvailability.id')
            ->inwhere('SalesMeetingAppointment.id', array(
           $input_data->id
        ))

           /* ->inwhere('NidaraKidProfile.id', array(
            $meetingDetails ->child_id
        ))*/

              ->getQuery()
            ->execute();


            if(count($kid)>0)
            {

                return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>$kid ]);

            }
            else
            {
                 return $this
                ->response
                ->setJsonContent(['status' => false, 'data' =>"Data Not Found" ]);
            }

          



    }


    public function meetingsavenotes()
    {

        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = SalesMeetingAppointment::findFirstByid($input_data->id);



        $collection->note = $input_data->note;
        $collection->status = 2;

             if ($collection->save())
             {

                return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>"Data Saved" ]);

            }
            else
            {
                 return $this
                ->response
                ->setJsonContent(['status' => false, 'data' =>"Data Not Saved" ]);
            }
     }


  public function getparentdetails()
    {
  $input_data = $this
            ->request
            ->getJsonRawBody();
      
$dev_off="";
      $childdata=array();  

       /* $uservalue = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.child_id',
                    'SalesMeetingAppointment.apo_time',
                    'NidaraKidProfile.first_name',
                    'Users.id as parent_id',
                    'Users.first_name as parent_name',
                    'SalesMeetingAppointment.apo_date as choose_date',
                    'SalesMeetingAppointment.status',
                    'SalesMeetingAppointment.id',
                    'SalesMeetingAppointment.meeting_no',
                ))->from("SalesMeetingAppointment")
                ->leftjoin('NidaraKidProfile', 'SalesMeetingAppointment.child_id = NidaraKidProfile.id')
                 ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                ->inwhere('SalesMeetingAppointment.coustomer_id', array(
                   $input_data-> user_id
                ))
                ->groupby('SalesMeetingAppointment.child_id')

                ->getQuery()->execute();*/

               $uservalue = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'NidaraKidProfile.id as child_id',
            'NidaraKidProfile.first_name',
            'NidaraKidProfile.last_name',
             'Users.id as parent_id',
             'Users.first_name as parent_name',
          

        ))
            ->from("NidaraKidProfile")
            ->leftjoin('KidParentsMap', 'KidParentsMap.nidara_kid_profile_id = NidaraKidProfile.id')
            ->leftjoin('Users', 'Users.id = KidParentsMap.users_id')
            ->inwhere('KidParentsMap.users_id', array(
            $input_data-> user_id
        ))
            ->getQuery()
            ->execute();

/* return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>  $uservalue ]);

*/
$fulldata=array();
foreach ($uservalue  as $key) {
    # code...
$childdata=array();  
for($i=1;$i<=4;$i++)
{
    $uservaluefinal = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.child_id',
                    'SalesMeetingAppointment.dev_off_id',
                    'SalesMeetingAppointment.apo_time',
                    'NidaraKidProfile.first_name',
                    'Users.first_name as parent_name',
                    'Users.id as parent_id',
                    'SalesMeetingAppointment.apo_date as choose_date',
                    'SalesMeetingAppointment.status',
                    'SalesMeetingAppointment.id as appointment_id',
                    'SalesMeetingAppointment.meeting_no',
                    'SalesMeetingAvailability.start_time',
                ))->from("SalesMeetingAppointment")
              ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAppointment.apo_time = SalesMeetingAvailability.id')

                ->leftjoin('NidaraKidProfile', 'SalesMeetingAppointment.child_id = NidaraKidProfile.id')
                 ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                ->inwhere('SalesMeetingAppointment.child_id', array(
                   $key-> child_id
                ))
                ->inwhere('SalesMeetingAppointment.coustomer_id', array(
                  $input_data-> user_id
                ))
                ->inwhere('SalesMeetingAppointment.meeting_no', array(
                   $i
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                   1,2
                ))

                ->getQuery()->execute();

              

                if(count($uservaluefinal) > 0)
                {

                foreach ($uservaluefinal as $values) {

                    $dev_off=$values->dev_off_id;
                    $data['dev_off_id']= $values->dev_off_id;
                    $data['id']=$values->appointment_id;
                    $data['meeting_no']=$i;
                     $data['apo_time']=$values->apo_time;
                      $data['apo_date']=$values->choose_date;
                      $data['child_id']=$key-> child_id;
                      $data['coustomer_id']=$key-> parent_id;
                      $data['status']= $values->status;
                      $data['start_time']= $values->start_time;
                      $date=date_create($values->choose_date);
                      $data['choose_date']=date_format($date,"l, F d, Y");
                    # code...

$secdate=date($values->choose_date);
$curdate=date("Y-m-d");

$time=date($values->start_time);

$curtime=date("h:i:s");

if(($secdate < $curdate)&& ($values->status!=2))
{
$data['scheduled']= "rescheduled";
}
else if(($secdate == $curdate) && ($time > $curtime) &&  ($values->status!=2))
{
$data['scheduled']= "rescheduled";
}
else if($values->status==2)
{
    $data['scheduled']= "completed";
}
else
{
    $data['scheduled']= "scheduled";

}





                    $childdata[]=$data;
                }
                }
                else
                {
                     $data['dev_off_id']= $dev_off;
                    $data['id']="";
                    $data['meeting_no']=$i;
                     $data['apo_time']="";
                      $data['apo_date']="";
                      $data['child_id']=$key-> child_id;
                      $data['coustomer_id']=$key-> parent_id;
                      $data['status']="";
                      $data['start_time']= "";

                      $data['choose_date']="";
                      $data['scheduled']= "";

                    # code...

                    $childdata[]=$data;

                }






}    

$studata['meetingInfo']=$childdata;
$studata['first_name']=$key->first_name;
$studata['parent_name']=$key->parent_name;
$studata['child_id']=$key->child_id;


$fulldata[]=$studata;
}


 return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>  $fulldata ]);



}


public function emailformeeting()
{
        $input_data = $this
            ->request
            ->getJsonRawBody();

       
            $school = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesMeetingAppointment.child_id',
                'Users.first_name as parent_name',
                'Users.email',
                'NidaraKidProfile.first_name',

            ))
                ->from("SalesMeetingAppointment")
                 ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                 ->leftjoin('NidaraKidProfile', 'SalesMeetingAppointment.child_id = NidaraKidProfile.id')
               
                ->inwhere('SalesMeetingAppointment.status', array(
                1
            ))
                ->inwhere('SalesMeetingAppointment.coustomer_id', array(
                $input_data->user_id
            ))
                ->groupby( 'SalesMeetingAppointment.child_id')
                
                ->getQuery()
                ->execute();

          $allmeeting=array();   

            foreach ($school as $value)
            {



                $kidval = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'SalesMeetingAppointment.child_id',
                'SalesMeetingAppointment.meeting_no',
                'SalesMeetingAppointment.id',
                 'SalesMeetingAppointment.apo_date as choose_date',
                'SalesMeetingAvailability.start_time',

            ))
                ->from("SalesMeetingAppointment")
         ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAppointment.apo_time = SalesMeetingAvailability.id')

               
                ->inwhere('SalesMeetingAppointment.status', array(
                1
            ))
                 ->inwhere('SalesMeetingAppointment.child_id', array(
               $value->child_id
            ))
                 ->inwhere('SalesMeetingAppointment.coustomer_id', array(
                $input_data->user_id
            ))
                 ->orderBy('SalesMeetingAppointment.meeting_no')
                 ->getQuery()
                 ->execute();




                 if(count($kidval) > 0)
                 {

                    $email=$value->email;
                    $first_name=$value->parent_name;

                    $child_name=$value->first_name;

$meeting=array();

$divitionstart='<div>';
                 foreach ( $kidval as $key) 
                 {
                        $timestamp = strtotime($key->choose_date);
                        $new_date = date("d-m-Y", $timestamp);


                      $meeting[]='<p>Meeting : '.$key->meeting_no.' Date : '.$new_date.'. Time : '.$key->start_time.'</p>';  

                 }






$divitionend='</div>';

$allmeeting[]=$divitionstart.'<p>Child Name : '.$child_name.'</p>'.implode(" ",$meeting).$divitionend;

$meeting[]=[];


                }
                else
                {

                            return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => implode(" ",$allmeeting)]);
                }

            }



                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Parent Meeting Session Confirmation';
                 $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>YOUR NIDARA-CHILDREN PARENT MEETING APPOINTMENT CONFIRMATION</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $first_name . ' ,</p> 

        <p>Your NC Parent Meeting Information Session has been confirmed as below:</p>

        '.implode(" ",$allmeeting).'

        <p>We will send you a separate virtual meeting invite in your email.</p>
        <p>To Modify Your Parent Meeting Appointment, please contact our NC Enrollment Officer.
</p>

        


        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>
';
               
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }
                else
                {
                    // return $this->response->setJsonContent ( [
                    //  'status' => true,
                    //  'message' => 'Message hase be sent.'
                    // ] );
                    

                    
                }
            
             return $this
                        ->response
                        ->setJsonContent(['status' => true, 'data' => 'Mail Send']);
      
}


public function getmeetingbyid()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        
            $colloction = SalesMeetingAppointment::findFirstByid($input_data->id);
        
      
        if ($colloction)
        {
            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction ]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => 'Data Not Found']);
        }
    }



public function getavailablitybychild()
{
    $input_data = $this
            ->request
            ->getJsonRawBody();

        $uservaluefinal = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.child_id',
                    'SalesMeetingAppointment.dev_off_id',
                    'SalesMeetingAppointment.apo_time',
                    'NidaraKidProfile.first_name',
                    'Users.first_name as parent_name',
                    'Users.id as parent_id',
                    'SalesMeetingAppointment.apo_date as choose_date',
                    'SalesMeetingAppointment.status',
                    'SalesMeetingAppointment.id as appointment_id',
                    'SalesMeetingAppointment.meeting_no',
                    'SalesMeetingAvailability.start_time',
                ))->from("SalesMeetingAppointment")
              ->leftjoin('SalesMeetingAvailability', 'SalesMeetingAppointment.apo_time = SalesMeetingAvailability.id')

                ->leftjoin('NidaraKidProfile', 'SalesMeetingAppointment.child_id = NidaraKidProfile.id')
                 ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                ->inwhere('SalesMeetingAppointment.child_id', array(
                   $input_data-> child_id
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                   1,2
                ))

                ->getQuery()->execute();


                $childinfoval=$this->modelsManager->createBuilder()->columns(array(
                    
                    'NidaraKidProfile.first_name',
                    'NidaraKidProfile.last_name',

                    'Grade.grade_name',

                    
                ))->from("NidaraKidProfile")
              ->leftjoin('Grade', 'Grade.id = NidaraKidProfile.grade')

             ->inwhere('NidaraKidProfile.id', array(
                   $input_data-> child_id
                ))
                ->getQuery()->execute();


                $childinfo['child_info']=$childinfoval;

                $childinfo['meeting_info']= $uservaluefinal;
              

                if(count($uservaluefinal) > 0)
                {

                    return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>$childinfo ]);

                }
                else
                {
                     return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "Data Not Found" ]);
                }
}

    public function viewreport()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

            if($input_data-> meeting_no != 1)
            {
             $mno=$input_data-> meeting_no-1;
          

             $meetinginfo = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.child_id',
                    'SalesMeetingAppointment.apo_date as choose_date',
                    
                ))->from("SalesMeetingAppointment")
                ->inwhere('SalesMeetingAppointment.child_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                   1
                ))
                ->inwhere('SalesMeetingAppointment.meeting_no', array(
                   $mno,$input_data-> meeting_no
                ))
                ->orderBy('SalesMeetingAppointment.meeting_no')
                ->getQuery()->execute();

                }



        $gamesCount = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as core_framework_name',
            'Subject.id as subject_id',
            'Subject.subject_name as subject_name',
        ))
            ->from('BaselineGames')
            ->leftjoin('NidaraKidProfile', 'BaselineGames.grade_id = NidaraKidProfile.grade')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->inwhere("NidaraKidProfile.id", array(
            $input_data->nidara_kid_profile_id
        ))
            ->getQuery()
            ->execute();
        foreach ($gamesCount as $core_data)
        {

            if($input_data-> meeting_no ==1)
            {

            $getgameid = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'BaselineGamesStatus.id',
            ))
                ->from('BaselineGamesStatus')
                ->where('BaselineGamesStatus.create_date <= CURRENT_DATE()')
                ->inwhere("BaselineGamesStatus.game_id", array(
                $core_data->id
            ))
                ->inwhere("BaselineGamesStatus.kid_id", array(
                $input_data->nidara_kid_profile_id
            ))
                ->getQuery()
                ->execute();

             }
             else
             {

            $getgameid = $this
                ->modelsManager
                ->createBuilder()
                ->columns(array(
                'BaselineGamesStatus.id',
            ))
                ->from('BaselineGamesStatus')
                 ->where("BaselineGamesStatus.create_date <= '".$meetinginfo[1]->choose_date."' AND BaselineGamesStatus.create_date >='".$meetinginfo[0]->choose_date."'")
                ->inwhere("BaselineGamesStatus.game_id", array(
                $core_data->id
            ))
                ->inwhere("BaselineGamesStatus.kid_id", array(
                $input_data->nidara_kid_profile_id
            ))
                ->getQuery()
                ->execute();

             }   
            if (count($getgameid) > 0)
            {
                $core_data->show = false;
                $core_data->daily_tips = 'Game is Completed';
                $core_data->grade_color = '#008000';
            }
            else
            {
                $core_data->show = true;
                $core_data->daily_tips = 'Game not Completed';
            }
            $core_framework_name = strtolower(str_replace(' ', '_', $core_data->core_framework_name));
            $core_array[] = $core_data->core_framework_name;
            $core_frm_array[$core_framework_name][] = $core_data;
        }

        $core_frame = CoreFrameworks::find();
        foreach ($core_frame as $core)
        {
            if (!in_array($core->name, $core_array))
            {
                $core->name = strtolower(str_replace(' ', '_', $core->name));
                $core_frm_array[$core->name] = array();
            }
        }
        $kid = NidaraKidProfile::findFirstByid($input_data->child_id);
        if (!empty($kid))
        {
            $core_frm_array['kid_name'] = $kid->first_name;
            $core_frm_array['child_photo'] = $kid->child_photo;
        }
        $core_frm_array['today_date'] = date('l, F d, Y');
        return $this
            ->response
            ->setJsonContent(['status' => true, 'data' => $core_frm_array]);

    }

public function viewreportold()
    {
        $baseurl = $this
            ->config->colorurl;

        $input_data = $this
            ->request
            ->getJsonRawBody();

            if($input_data-> meeting_no ==1)
            {
        


         $gamesCountcoreedu = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as name',
            'Subject.subject_name as subject_name',
            'BaselineGamesAnswer.id',
            'BaselineGamesAnswer.answer',
            'BaselineGamesAnswer.game_id',
            'BaselineGamesStatus.create_date',
        ))
            ->from('BaselineGames')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->leftjoin('BaselineGamesAnswer', 'BaselineGames.id = BaselineGamesAnswer.game_id')
            ->leftjoin('BaselineGamesStatus', 'BaselineGamesStatus.game_id = BaselineGamesAnswer.game_id')
            /*->inwhere("BaselineGames.grade_id", array(
            $input_data->grade_id
        ))*/
            ->where('BaselineGamesStatus.create_date <= CURRENT_DATE()')
             ->inwhere("BaselineGamesStatus.status", array(
            1
        ))
             ->inwhere("CoreFrameworks.id", array(
            1
        ))
              ->inwhere("BaselineGamesStatus.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
               ->inwhere("BaselineGamesAnswer.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
             ->  groupby('BaselineGames.id')
         /*   ->inwhere("BaselineGames.framework_id", array(
            $input_data->framework_id
        ))
            ->inwhere("BaselineGames.subject_id", array(
            $input_data->subject_id
        ))*/
            ->getQuery()
            ->execute();


            $gamesCountcorehel = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as name',
            'Subject.subject_name as subject_name',
            'BaselineGamesAnswer.id',
            'BaselineGamesAnswer.answer',
            'BaselineGamesAnswer.game_id',
            'BaselineGamesStatus.create_date',
        ))
            ->from('BaselineGames')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->leftjoin('BaselineGamesAnswer', 'BaselineGames.id = BaselineGamesAnswer.game_id')
            ->leftjoin('BaselineGamesStatus', 'BaselineGamesStatus.game_id = BaselineGamesAnswer.game_id')
            /*->inwhere("BaselineGames.grade_id", array(
            $input_data->grade_id
        ))*/
            ->where('BaselineGamesStatus.create_date <= CURRENT_DATE()')
             ->inwhere("BaselineGamesStatus.status", array(
            1
        ))
             ->inwhere("CoreFrameworks.id", array(
            2
        ))
              ->inwhere("BaselineGamesStatus.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
               ->inwhere("BaselineGamesAnswer.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
             ->  groupby('BaselineGames.id')
         /*   ->inwhere("BaselineGames.framework_id", array(
            $input_data->framework_id
        ))
            ->inwhere("BaselineGames.subject_id", array(
            $input_data->subject_id
        ))*/
            ->getQuery()
            ->execute();



        }
        else
        {

            $mno=$input_data-> meeting_no-1;
             $meetinginfo = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.child_id',
                    'SalesMeetingAppointment.apo_date as choose_date',
                    
                ))->from("SalesMeetingAppointment")
                ->inwhere('SalesMeetingAppointment.child_id', array(
                    $input_data->nidara_kid_profile_id
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                   1
                ))
                ->inwhere('SalesMeetingAppointment.meeting_no', array(
                   $mno,$input_data-> meeting_no
                ))
                ->orderBy('SalesMeetingAppointment.meeting_no')
                ->getQuery()->execute();


         $gamesCountcoreedu = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as name',
            'Subject.subject_name as subject_name',
            'BaselineGamesAnswer.id',
            'BaselineGamesAnswer.answer',
            'BaselineGamesAnswer.game_id',
            'BaselineGamesStatus.create_date',
        ))
            ->from('BaselineGames')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->leftjoin('BaselineGamesAnswer', 'BaselineGames.id = BaselineGamesAnswer.game_id')
            ->leftjoin('BaselineGamesStatus', 'BaselineGamesStatus.game_id = BaselineGamesAnswer.game_id')
            ->where("BaselineGamesStatus.create_date <= '".$meetinginfo[1]->choose_date."' AND BaselineGamesStatus.create_date >='".$meetinginfo[0]->choose_date."'")
             ->inwhere("BaselineGamesStatus.status", array(
            1
        ))
             ->inwhere("CoreFrameworks.id", array(
            1
        ))
              ->inwhere("BaselineGamesStatus.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
               ->inwhere("BaselineGamesAnswer.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
             ->  groupby('BaselineGames.id')
         /*   ->inwhere("BaselineGames.framework_id", array(
            $input_data->framework_id
        ))
            ->inwhere("BaselineGames.subject_id", array(
            $input_data->subject_id
        ))*/
            ->getQuery()
            ->execute();




         $gamesCountcorehel = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'BaselineGames.id',
            'BaselineGames.grade_id',
            'BaselineGames.framework_id',
            'BaselineGames.subject_id',
            'BaselineGames.game_name',
            'BaselineGames.game_path',
            'BaselineGames.created_at',
            'Grade.grade_name as grade_name',
            'CoreFrameworks.name as name',
            'Subject.subject_name as subject_name',
            'BaselineGamesAnswer.id',
            'BaselineGamesAnswer.answer',
            'BaselineGamesAnswer.game_id',
            'BaselineGamesStatus.create_date',
        ))
            ->from('BaselineGames')
            ->leftjoin('CoreFrameworks', 'BaselineGames.framework_id = CoreFrameworks.id')
            ->leftjoin('Grade', 'BaselineGames.grade_id = Grade.id')
            ->leftjoin('Subject', 'BaselineGames.subject_id = Subject.id')
            ->leftjoin('BaselineGamesAnswer', 'BaselineGames.id = BaselineGamesAnswer.game_id')
            ->leftjoin('BaselineGamesStatus', 'BaselineGamesStatus.game_id = BaselineGamesAnswer.game_id')
            ->where("BaselineGamesStatus.create_date <= '".$meetinginfo[1]->choose_date."' AND BaselineGamesStatus.create_date >='".$meetinginfo[0]->choose_date."'")
             ->inwhere("BaselineGamesStatus.status", array(
            1
        ))
             ->inwhere("CoreFrameworks.id", array(
            2
        ))
              ->inwhere("BaselineGamesStatus.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
               ->inwhere("BaselineGamesAnswer.kid_id", array(
            $input_data->nidara_kid_profile_id
        ))
             ->  groupby('BaselineGames.id')
         /*   ->inwhere("BaselineGames.framework_id", array(
            $input_data->framework_id
        ))
            ->inwhere("BaselineGames.subject_id", array(
            $input_data->subject_id
        ))*/
            ->getQuery()
            ->execute();

        }
$holedata=array();
        $data['core_education']=$gamesCountcoreedu;
        $data['core_health']=$gamesCountcorehel;
$holedata[]=$data;




                if(count($gamesCountcoreedu)>0 ||  count($gamesCountcorehel)>0)
                {

                    return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $holedata]);

                }
                else
                {
                     return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "Data Not Found" ]);
                }



    }


    public function kiddetails()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();
      
$dev_off="";
      $childdata=array();  


$uservaluett=$this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.child_id',
                    'Users.first_name as parent_name',
                    'Users.id as parent_id',
                    'Users.first_name as parent_first_name',
                    'Users.last_name as parent_last_name',
                    'Users.mobile as parent_mobile',
                    'Users.email as parent_email',
                    
                ))->from("SalesMeetingAppointment")
                 ->leftjoin('Users', 'Users.id = SalesMeetingAppointment.coustomer_id')
                
                ->inwhere('SalesMeetingAppointment.coustomer_id', array(
                  $input_data-> user_id
                ))
                ->inwhere('SalesMeetingAppointment.meeting_no', array(
                   1
                ))
                ->inwhere('SalesMeetingAppointment.status', array(
                   1
                ))
                ->groupby('SalesMeetingAppointment.child_id')

                ->getQuery()->execute();




$uservalue=Users::findFirstByid($input_data-> user_id);

$fulldata=array();

$childval=array();
foreach ($uservaluett  as $key) {

$cval=NidaraKidProfile::findFirstByid($key ->child_id);
$childval[]=$cval;


}


 return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>  $uservalue, 'kid_info' =>  $childval]);



}


    public function getparentbyid()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();




$uservalue=Users::findFirstByid($input_data-> user_id);



if($uservalue)
{
 return $this
                ->response
                ->setJsonContent(['status' => true, 'data' =>  $uservalue]);
}
else
{
     return $this
                ->response
                ->setJsonContent(['status' => false, 'data' =>  "Data Not Found"]);

}



}


public function createmessage()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();




            $uservalue=SuperadminMessage::findFirstByid($input_data-> id);

            if(!$uservalue)
            {
             $uservalue=new SuperadminMessage();
            }

            $uservalue->user_id = $input_data->user_id;
            $uservalue->role    = $input_data->role ;
            $uservalue->message  = $input_data->message;
            $uservalue->enable_from = $input_data->enable_from;
            $uservalue->enable_to  = $input_data->enable_to ;

           if($uservalue->save())
            {
             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $uservalue]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  "Data Not Found"]);

            }



}

public function getcreatemessage()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();
            $uservalue=$this->modelsManager->createBuilder()->columns(array(
                    'SuperadminMessage.id',
                    'SuperadminMessage.user_id',
                    'SuperadminMessage.message',
                    'SuperadminMessage.enable_from',
                    'SuperadminMessage.enable_to',                
                ))->from("SuperadminMessage")
                ->where("SuperadminMessage.enable_from <= CURRENT_DATE() and SuperadminMessage.enable_to >= CURRENT_DATE()")
                ->inwhere('SuperadminMessage.role', array(
                   "all",$input_data->role
                ))

                ->getQuery()->execute();


           if(count($uservalue) >0)
            {
             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $uservalue]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  "Data Not Found"]);

            }



}



    public function getaddress()
    {
        $input_data = $this
            ->request
            ->getJsonRawBody();

        $collection = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(

            'SalesmanAddress.city',
            'SalesmanAddress.state',
            'SalesmanAddress.country',
            'SalesmanAddress.post_code',
            'SalesCenterMap.center_id',
        ))->from('SalesmanAddress')
		->leftjoin('SalesCenterMap','SalesCenterMap.user_id = SalesmanAddress.user_id')
        ->inwhere('SalesmanAddress.user_id', array(
            $input_data->user_id
        ))->getQuery()->execute();

        if (count($collection) > 0)
        {

            return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $collection]);
        }
        else
        {
            return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not available"]);
        }
    }
	
	public function getsupportquestion(){
		$getinfo = ParentSupportQuestion::find();
		if(!$getinfo){
			return $this->response->setJsonContent(['status' => false, 'data' =>"no data"]);
		} else {
			return $this->response->setJsonContent(['status' => true, 'data' => $getinfo]);
		}
	}

    public function createuserquery()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

            $colloction1 = $this->modelsManager->createBuilder()->columns(array(
                    'SalesMeetingAppointment.dev_off_id'
                ))->from("SalesMeetingAppointment")
                ->inwhere('SalesMeetingAppointment.coustomer_id', array(
                    $input_data->parent_id
                ))
                ->groupby('SalesMeetingAppointment.coustomer_id')
                ->getQuery()->execute();
                if(count($colloction1)<=0)
                {
                	return $this
                ->response
                ->setJsonContent(['status' => false, 'data' =>"Dev_Off_Id Not available in SalesMeetingAppointment"]);

                }

            if(empty($input_data->id))
            {
            	$parentquery=new SalesmanParentQuery();
            }
            else
            {
            	$parentquery= SalesmanParentQuery::findFirstByid($input_data->id);
            }
 				
 				

            $parentquery->dev_off_id=$colloction1[0]->dev_off_id;
            $parentquery->parent_id=$input_data->parent_id;
            $parentquery->question = $input_data->question_id;
            $parentquery->query_string=$input_data->query_string;
            $parentquery->status= 1;
            $parentquery->created_at= date('Y-m-d');

           
				
			if($parentquery->save())
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => "Saved"]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => $parentquery]);
			}		


    }

    public function userqueryresponse()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $parentquery= SalesmanParentQuery::findFirstByid($input_data->id);
            

           
            $parentquery->query_answer=$input_data->query_answer;
            $parentquery->close_time=date("Y-m-d h:m:s");
            $parentquery->status=0;

           
				
			if($parentquery->save())
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => "Saved"]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => $parentquery]);
			}		


    }
	
	public function getquestioninfo(){
		$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $parentquery= $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(

            'SalesmanParentQuery.id',
            'SalesmanParentQuery.dev_off_id',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.question as question_id',
            'ParentSupportQuestion.question',
            'SalesmanParentQuery.query_string',
            'SalesmanParentQuery.query_answer',
            'SalesmanParentQuery.status',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.created_at',
        ))->from('SalesmanParentQuery')
		->leftjoin('ParentSupportQuestion','ParentSupportQuestion.id = SalesmanParentQuery.question')
        ->inwhere('SalesmanParentQuery.parent_id', array(
            $input_data -> parent_id
        ))
		->inwhere('SalesmanParentQuery.created_at', array(
            $input_data -> choose_date
        ))
        ->getQuery()->execute();
		
		if(count($parentquery)>0)
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $parentquery]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not found"]);
			}
	}

    public function getactivequerybydevoffid()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $parentquery= $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(

            'SalesmanParentQuery.id',
            'SalesmanParentQuery.dev_off_id',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.query_string',
            'SalesmanParentQuery.query_answer',
            'SalesmanParentQuery.status',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.created_at',
        ))->from('SalesmanParentQuery')
        ->inwhere('SalesmanParentQuery.dev_off_id', array(
            $input_data->dev_off_id
        ))
        ->inwhere('SalesmanParentQuery.status', array(
           1
        ))
        ->getQuery()->execute();
             		
			if(count($parentquery)>0)
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $parentquery]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not found"]);
			}		


    }

    public function getallquerybydevoffid()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $parentquery= $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(

            'SalesmanParentQuery.id',
            'SalesmanParentQuery.dev_off_id',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.query_string',
            'SalesmanParentQuery.query_answer',
            'SalesmanParentQuery.status',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.created_at',
        ))->from('SalesmanParentQuery')
        ->inwhere('SalesmanParentQuery.dev_off_id', array(
            $input_data->dev_off_id
        ))
        ->orderBy('SalesmanParentQuery.created_at')
        ->getQuery()->execute();
             		
			if(count($parentquery)>0)
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $parentquery]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not found"]);
			}		


    }


     public function getactivequerybyparentid()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $parentquery= $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(

            'SalesmanParentQuery.id',
            'SalesmanParentQuery.dev_off_id',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.query_string',
            'SalesmanParentQuery.query_answer',
            'SalesmanParentQuery.status',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.created_at',
        ))->from('SalesmanParentQuery')
        ->inwhere('SalesmanParentQuery.parent_id', array(
            $input_data->parent_id
        ))
        ->inwhere('SalesmanParentQuery.status', array(
           1
        ))
        ->getQuery()->execute();
             		
			if(count($parentquery)>0)
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $parentquery]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not found"]);
			}		


    }

    public function getallquerybyparentid()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $parentquery= $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(

            'SalesmanParentQuery.id',
            'SalesmanParentQuery.dev_off_id',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.query_string',
            'SalesmanParentQuery.query_answer',
            'SalesmanParentQuery.status',
            'SalesmanParentQuery.parent_id',
            'SalesmanParentQuery.created_at',
        ))->from('SalesmanParentQuery')
        ->inwhere('SalesmanParentQuery.parent_id', array(
            $input_data->parent_id
        ))
        ->orderBy('SalesmanParentQuery.created_at')
        ->getQuery()->execute();
             		
			if(count($parentquery)>0)
			{
				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $parentquery]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not found"]);
			}		


    }



    public function getuserdetailsbycenterid()
    {
    	$input_data = $this
            ->request
            ->getJsonRawBody();

           
       $colloction = $this
            ->modelsManager
            ->createBuilder()
            ->columns(array(
            'Users.id',
            'Users.parent_type',
            'Users.user_type',
            'Users.first_name',
            'Users.last_name',
            'Users.email',
            'Users.mobile',
            'Users.photo',
            'Users.occupation',
            'Users.company_name',
            'Users.created_at',
            'Users.created_by',
            'Users.modified_at',
            'Users.country_of_residence',
            'Users.country_of_citizenship',
            'Users.act_status',
			'Users.status',
			'UsersAddress.address_1',
			'UsersAddress.address_2',
			'UsersAddress.city',
			'UsersAddress.state',
			'UsersAddress.country',
			'UsersAddress.post_code',
			'UsersAddress.created_at',


      ))
            ->from("SalesCenterMap")
           // ->innerjoin('SalesCenterAppointment', 'SalesCenterAppointment.dev_off_id = SalesCenterMap.user_id')
 			 ->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
 			 ->leftjoin('UsersAddress', 'Users.id = UsersAddress.user_id')
            ->inwhere('SalesCenterMap.center_id', array(
            $input_data->center_id
        ))
       
          
            ->getQuery()
            ->execute();

         $centerdetails=$this->modelsManager->createBuilder()->columns(array(  'SalesCenter.id as center_id',
            'SalesCenter.address_1',
            'SalesCenter.address_2',
            'SalesCenter.center_type',
            'SalesCenter.open_time',
            'SalesCenter.close_time',
            'SalesCenter.email as center_email',
            'SalesCenter.mobile as center_mobile',
            'SalesCenter.a_mobile as center_a_mobile',
            'SalesCenter.center_overview',
            'SalesCenter.center_type',
            'Cities.name as city_name',
            'Countries.name as country_name',
            'States.name as state_name',
		))->from("SalesCenter")
		->leftjoin('Cities', 'SalesCenter.city = Cities.id')
		
		->leftjoin('Countries', 'SalesCenter.country = Countries.id')
		
		->leftjoin('States', 'SalesCenter.state = States.id')
		
		->inwhere('SalesCenter.id', array(
				$input_data->center_id
			))
		->getQuery()->execute(); 


             		
			if(count($colloction)>0)
			{

				return $this
                ->response
                ->setJsonContent(['status' => true, 'data' => $colloction,'centerInfo' => $centerdetails]);
			}	
			else
			{
				return $this
                ->response
                ->setJsonContent(['status' => false, 'data' => "data not found"]);
			}		


    }

    	public function getusersearch(){
		$input_data = $this->request->getJsonRawBody();
		$colloction = $this->modelsManager->createBuilder()->columns(array(
            'Users.id',
            'Users.first_name',
            'Users.last_name',
            'Users.email',
            'Users.mobile',
            'SalesCenter.id as center_id',
            'SalesCenter.address_1',
            'SalesCenter.address_2',
            'SalesCenter.center_type',
            'SalesCenter.open_time',
            'SalesCenter.close_time',
            'SalesCenter.email as center_email',
            'SalesCenter.mobile as center_mobile',
            'SalesCenter.a_mobile as center_a_mobile',
            'SalesCenter.center_overview',
            'Cities.name as city_name',
            'Countries.name as country_name',
            'States.name as state_name',
		))->from("Users")
		->leftjoin('SalesCenterMap', 'Users.id = SalesCenterMap.user_id')
		->leftjoin('SalesCenter', 'SalesCenter.id = SalesCenterMap.center_id')
		
		->leftjoin('Cities', 'SalesCenter.city = Cities.id')
		
		->leftjoin('Countries', 'SalesCenter.country = Countries.id')
		
		->leftjoin('States', 'SalesCenter.state = States.id')
		
		->where('Users.first_name like "%'.$input_data->search.'%" or Users.last_name like "%'.$input_data->search.'%" or CONCAT(Users.first_name," ",Users.last_name) like "%'.$input_data->search.'%" or
				Users.email="'.$input_data->search.'" or Users.mobile="'.$input_data->search.'"')
		->inwhere('Users.user_type', array(
				"coordinator","dev_enroll_officer","development_officer"
			))
		->getQuery()->execute();
		if(count($colloction) <= 0){
			return $this->response->setJsonContent(['status' => false, 'Message' => 'The parent not get']);
		} else {
			return $this->response->setJsonContent(['status' => true, 'data' => $colloction]);
		}
	}


    public function createsalesmanrequestform()
    {
        # code...
        $input_data = $this
            ->request
            ->getJsonRawBody();

     $salesmanrequestform=SalesmanRequestForm::findFirstByid($input_data-> id);

     $stage=Grade::findFirstByid($input_data->stage);

     $cityval = $this->modelsManager->createBuilder()->columns(array(
            'Cities.name as city',
			
        ))->from('Cities')
		->inwhere('Cities.id', array(
            $input_data->city
        ))->getQuery()->execute();

        $stateval = $this->modelsManager->createBuilder()->columns(array(
           
            'States.name as state',
        ))->from('States')
		->inwhere('States.id', array(
            $input_data->state
        ))->getQuery()->execute();

    $countryval = $this->modelsManager->createBuilder()->columns(array(
      
			'Countries.name as country',
           
        ))->from('Countries')
		->inwhere('Countries.id', array(
            $input_data->country
        ))->getQuery()->execute();   



$collection = $this->modelsManager->createBuilder()->columns(array(
            'SalesCenter.email',
        ))->from('SalesCenter')
		->leftjoin('Countries','Countries.id = SalesCenter.country')
		->leftjoin('Cities','Cities.id = SalesCenter.city')
		->leftjoin('States','States.id = SalesCenter.state')
		->inwhere('SalesCenter.city', array(
            $input_data->city
        ))->inwhere('SalesCenter.state', array(
            $input_data->state
        ))->inwhere('SalesCenter.country', array(
            $input_data->country
        ))
        ->inwhere('SalesCenter.post_code', array(
            $input_data->pincode
        ))
        ->getQuery()->execute();

        if(count($collection)>0)
        {
        	$salesmailid=$collection[0]->email;
        }
        else
        {
        	$salesmailid="customersupport@nidarachildren.com";
        }


            if(!$salesmanrequestform)
            {
             $salesmanrequestform=new SalesmanRequestForm();
            }

            $salesmanrequestform->first_name = $input_data->first_name;
            $salesmanrequestform->last_name = $input_data->last_name;
            $salesmanrequestform->mobile = $input_data->mobile;
            $salesmanrequestform->email = $input_data->email;
            $salesmanrequestform->city = $input_data->city;
            $salesmanrequestform->state = $input_data->state;
            $salesmanrequestform->country = $input_data->country;
            $salesmanrequestform->stage = $input_data->stage;
            $salesmanrequestform->pincode =$input_data->pincode;
            if(!empty($input_data->termsagree))
            {
            	$salesmanrequestform->termsagree = $input_data->termsagree;
            }

           if($salesmanrequestform->save())
            {
            	 
				
                $mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($input_data->email, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'THANK YOU FOR YOUR INQUIRY';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>THANK YOU FOR YOUR INQUIRY</h3>
       </div>
      <div class="page-content">
        
        <p>Dear ' . $input_data->first_name . ' ,</p> 

        <p>We have received your inquiry .  We will get back to you as soon as possible:  A copy of the information you entered is below:</p>

        <p>First Name : ' . $input_data->first_name . ' </p> 
        <p>Last Name : ' . $input_data->last_name . ' </p> 
        <p>Mobile Number : ' . $input_data->mobile . ' </p> 
        <p>Email Address : ' . $input_data->email. ' </p> 
        <p>Child Age & Stage : ' .$stage->grade_name . ' </p> 
        <p>Country : ' . $countryval[0]->country . ' </p> 
        <p>State : ' . $stateval[0]->state . ' </p> 
        <p>City : ' . $cityval[0]->city . ' </p> 
        <p>Pin Code/ Zip Code : ' . $input_data->pincode . '</p> 
        <p>Data Protection Policy : Agreed to Data Protection Policy on the website</p> 
        


        <p>We look forward to helping you give your child the best start in life.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }

$mail = new PHPMailer;
                //$mail->SMTPDebug = 3;                               // Enable verbose debug output
                $mail->isSMTP(); // Set mailer to use SMTP
                $mail->Host = 'smtp-relay.sendinblue.com'; // Specify main and backup SMTP servers
                $mail->SMTPAuth = true; // Enable SMTP authentication
                $mail->Username = 'contact@haselfre.com'; // SMTP username
                $mail->Password = 'DW6a42NFsPUCgcjA'; // SMTP password
                $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587; // TCP port to connect to
                $mail->setFrom('customersupport@nidarachildren.com', 'Nidara-Children');
                $mail->addAddress($salesmailid, ''); // Add a recipient
                // Name is optional
                $mail->addReplyTo('customersupport@nidarachildren.com', 'Information');
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'PROSPECTIVE CUSTOMER INQUIRY';
                $mail->Body = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css">
.ReadMsgBody {width: 100%;}
.ExternalClass {width: 100%;}

body {
  color: #666666;
}
    .page-title {
      text-align: center;
      width: 100%;
      float: left;
      color: #83d0c9;
    }
           body,td{
            font-family:verdana,geneva;
            font-size:12px;
           }
           body{
            background:#fff;
            padding:20px;
           }
           .top-img{
            width:100%;
            text-align:center;
            padding-bottom:0;
            font-size:10px;
           }
           .sub-mail-cont{
            width:100%;
           }
           .sub-mail-vr{
            width:580px;
            margin:auto;
            float:none;
           }
           .main-page-mail{
            width:100%;
            float:left;
            padding:20px;
            border:1px solid #999;
           }
           .sub-mail-but{
            width:100%;
            text-align:center;
            padding-top:30px;
            float:left;
           }
           a.sub-but{
            text-decoration:none;
            color:#333;
            padding:10px 50px;
            border:1px solid;
           }
           .sub-but-cont{
            width:100%;
            padding-top:20px;
            float:left;
           }
           .footer{
            width:100%;
            text-align:center;
            font-size:10px;
            padding-top:20px;
            float:left;
           }
           .footer ul{
            list-style:none;
            float:left;
            margin:15px 10px;
            width:100%;
            padding:0;
           }
           .footer ul li{
            display:inline-flex;
            padding-left:5px;
           }
           p{
            line-height:18px;
           }
           .small{
            font-size:11px;
           }
           .main-title{
            text-align:center;
            color:#aed7d3;
            float:left;
            width:100%;
           }
           .main-title h3{
            font-weight:500;
           }
           .first-name{
            text-transform:capitalize;
           }
           .product-img{
            width:20%;
            float:left;
            padding-right:20px;
           }
           .product-img img{
            width:100%;
           }
           .product-cont{
            width:75%;
            float:left;
           }
           .product-details{
            width:100%;
            float:left;
           }
       .page-content {
        width: 100%;
        float: left;
        color: #666666;
      }
      .click-but {
        width: 100%;
        float: left;
        text-align: center;
      }
      .click-but .but{
        width:250px;
        display:block;
        margin:auto;
      }
      .click-but .but a {
        text-decoration: none;
        padding: 10px;
        color: #fff;
      }
      .click-but .but {
        display: block;
        margin: auto;
        width: 265px;
        padding: 15px;
        background: #333333;
        font-size: 15px;
        float: left;
      }
      .click-but .but:hover{
        background: #83d0c9;
      }
         
span.yshortcuts { color:#000; background-color:none; border:none;}
span.yshortcuts:hover,
span.yshortcuts:active,
span.yshortcuts:focus {color:#000; background-color:none; border:none;}
</style>
</head>
<body bgcolor="#fff" style="font-family: verdanaWELCOME TO NIDARA-CHILDREN
,geneva; font-size: 12px; background: #fff; padding: 20px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="" bgcolor="#fff"><tr><td>

          
          <div class="sub-mail-vr" style="width: 580px; margin: auto; float: none;">
            <div class="main-page-mail" style="width: 100%; float: left; padding: 20px; border: 1px solid #999;">
           <div class="top-img" style="width: 100%; text-align: center; padding-bottom: 0; font-size: 10px;">
             <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/09/logo-old.jpg" alt="170x150_logo.jpg" style="width:30%" /><p style="line-height: 18px;">GIVE YOUR CHILD THE BEST START IN LIFE</p>
           </div>
       <div class="page-title">
      <h3>PROSPECTIVE CUSTOMER INQUIRY</h3>
       </div>
      <div class="page-content">
        
        <p>Hello, </p> 

        <p>You have received a prospective customer inquiry :  A copy of the information entered is below:</p>

        <p>First Name : ' . $input_data->first_name . '</p> 
        <p>Last Name : ' . $input_data->last_name . '</p> 
        <p>Mobile Number : ' . $input_data->mobile . '</p> 
        <p>Email Address : ' . $input_data->email. '</p> 
        <p>Child Age & Stage : ' .$stage->grade_name . '</p> 
        <p>Country : ' . $countryval[0]->country . '</p> 
        <p>State : ' . $stateval[0]->state . '</p> 
        <p>City : ' . $cityval[0]->city . '</p> 
        <p>Pin Code/ Zip Code : ' . $input_data->pincode . '</p> 
        <p>Data Protection Policy : Agreed to Data Protection Policy on the website</p> 
        


        <p>Please follow the Nidara-Children guidelines to help the prospective customer as per the needs of the customer.</p>

        

      </div>
      
            
            <div class="sub-but-cont" style="width: 100%; padding-top: 20px; float: left;">
           <p style="line-height: 18px;">Best regards,</p>
           <p style="line-height: 18px;">
            </p>
            <p style="line-height: 18px;">Nidara-Children</p>
          </div>
          <div class="footer" style="width: 100%; text-align: center; font-size: 10px; padding-top: 20px; float: left;">
            <ul style="list-style: none; float: left; margin: 15px 10px; width: 100%; padding: 0;">
        <li style="display: inline-flex; padding-left: 5px;">
         <a class="fb" href="https://www.facebook.com/NidaraChildren/" target="_blank"><img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/facebook-mint-unsmushed-1.png" alt="facebook-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="twt" href="https://twitter.com/nidarachildren" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/twitter-mint.png" alt="twitter-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="ins" href="https://www.instagram.com/nidarachildren/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/instagram-mint-1.png" alt="instagram-mint.png" /></a>
         </li>
         <li style="display: inline-flex; padding-left: 5px;">
         <a class="email" href="' . $this
                    ->config->weburl . '/contact-us/" target="_blank"> <img src="https://blog.nidarachildren.com/wp-content/uploads/2020/01/mail-mint.png" alt="mail-mint.png" /></a>
         </li>
           </ul>
      <span>Copyright &copy; Nidara-Children. All rights reserved.</span>
        <br /><span>You are receiving this email because you opted in at our website.
            </span>
            <br /><span>
      <a href="https://faq.nidarachildren.com/wp-content/themes/nidara/Newsletter-contact/Nidara_Newslatter.vcf">Add us to your address book</a> | <a href="https://nidarachildren.us17.list-manage.com/unsubscribe?u=e2c0982dd8b7d1a16f74d886d&id=fae67dd82a&e=*%7CUNIQID%7C*">Unsubscribe from this list</a>
      </span>
          </div>
           </div>
         </div>
         
</td></tr></table>
</body>
</html>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                if (!$mail->send())
                {
                    return $this
                        ->response
                        ->setJsonContent(['status' => false, 'data' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                }

                



             return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $salesmanrequestform]);
            }
            else
            {
                 return $this
                            ->response
                            ->setJsonContent(['status' => false, 'data' =>  $salesmanrequestform]);

            }



}  	


public function getuserlist(){
	 $input_data = $this->request->getJsonRawBody();
	
		$uservalue = $this->modelsManager->createBuilder()->columns(array(
				'SalesCenterMap.user_id as user_id',
				'Users.first_name as first_name',
				'Users.last_name as last_name',
		))->from("SalesCenterMap")
		->leftjoin('Users', 'Users.id = SalesCenterMap.user_id')
		->getQuery()->execute();
		 return $this
                            ->response
                            ->setJsonContent(['status' => true, 'data' =>  $uservalue]);
}


}

