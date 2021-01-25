<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require '../vendor/autoload.php';
class GametagginghealthmasterController extends \Phalcon\Mvc\Controller {
	public function index() {
	}
	
	public function viewall() {
		$gettagging = GameTaggingHealthMaster::find ();
		if ($gettagging) :
			return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$gettagging
			]);
		 else :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Failed',
				        "data"=>array() 
			] );
		endif;
	}
	
	public function getfullslidmasters(){
		$gettagging = GameTaggingHealthMaster::find ();
		$taggingarray = array();
		foreach($gettagging as $mainvalue){
			$datacapture = GameTaggingDataCapture::find ();
			$datacapturearray = array();
			foreach($datacapture as $datacapturevalue){
				$datacapture_data['id'] = $datacapturevalue->id;
				$datacapture_data['data_capture'] = $datacapturevalue->data_capture;
				$datacapture_data['status'] = $datacapturevalue->status;
				$datacapturearray[] = $datacapture_data;
			}
			$slidemaster = GameTaggingSlideCategory::find ();
			$slidemasterarray = array();
			foreach($slidemaster as $slidemastervalue){
				$slidemaster_data['id'] = $slidemastervalue->id;
				$slidemaster_data['category_name'] = $slidemastervalue->category_name;
				$slidemasterarray[] = $slidemaster_data;
			}
			$gettagging_data['id'] = $mainvalue->id;
			$gettagging_data['heath_name'] = $mainvalue->heath_name;
			$gettagging_data['heath_definition'] = $mainvalue->heath_definition;
			$gettagging_data['datacapture'] = $datacapturearray;
			$gettagging_data['slidcategory'] = $slidemasterarray;
			$taggingarray[] = $gettagging_data;
		}
		return $this->response->setJsonContent ([ 
					'status' => true,
					'data' =>$taggingarray
			]);
	}
	public function saveexcelfile(){
		$input_data = $this->request->getJsonRawBody();
		$content = file_get_contents($input_data->files);
		file_put_contents($input_data->name, $content);
		return $this->response->setJsonContent ([ 
			'status' => true,
			'data' =>$input_data->name,
			'message' => 'file is succefully imported'
		]);
	}
	public function excel(){
				$input_data = $this->request->getJsonRawBody();
				if(empty($input_data->name)){
					$getexcel = GameTaggingExcel::findFirstBygame_id($input_data->game_id);
					$getName = $getexcel->excel_sheet;
				}
				else{
					$getName = $input_data->name ;
				}
				$tmpfname = 'excel/'.$getName;
				
				$excelReader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpfname);
				$excelReader->setReadDataOnly(true);
				$excelObj = $excelReader->load($tmpfname);
				$worksheet = $excelObj->getSheet(0);//
				$lastRow = $worksheet->getHighestRow();
				$lastCol = $worksheet->getHighestColumn();
				
				$data = array();
				$data2 = array();
				$lastCol++;						
				for ($row = 1; $row <= $lastRow; $row++) {
					$data1 = array();
					for($x = 'A'; $x != $lastCol; $x++){
						$y =  $x.''.$row;
						$data1[] = [$x => $worksheet->getCell($y)->getValue()];
					}
					$data[]['data'] = $data1;
				}
		return $this->response->setJsonContent ([ 
			'status' => true,
			'data' =>$data,
			'files' => $getName
			
		]);
	}

	
}
