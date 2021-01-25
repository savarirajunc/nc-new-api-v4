<?php

use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require '../vendor/autoload.php';
class GamesdatabaseController extends \Phalcon\Mvc\Controller {

    public function index() {
        
    }

    /**
     * Fetch all Record from database :-
     */
    public function viewall() {
         $subject = GamesDatabase::find();
        if ($subject):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif; 
    }
	
	public function gamesanswers() {
        $subject3 = GamesAnswers::find();
        if ($subject3):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject3		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }

     public function gameviewall() {
        $subject2 = GamesCoreframeMap::find();
        if ($subject2):
            return Json_encode($subject2);
        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif;
    }


    /*
     * Fetch Record from database based on ID :-
     */

    public function getbyid($id = null) {

        $input_data = $this->request->getJsonRawBody();
        $id = isset($input_data->id) ? $input_data->id : '';
        if (empty($id)){
            return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid input parameter']);
		}
        else{
            $collection = GamesDatabase::findFirstByid($id);
			return $this->response->setJsonContent(['status' => true, 'data' => $collection]);
		}
    }

    /**
     * This function using to create GamesDatabase information
     */
    public function create() {

        $input_data = $this->request->getJsonRawBody();

        /**
         * This object using valitaion 
         */
        $validation = new Validation();
		$messages = $validation->validate($input_data);
		$excel =  $input_data->file_name;
		if(!$excel){
			return $this->response->setJsonContent(['status' => false, 'message' => 'Please give the excel file']);
		}
        if (count($messages)){
            foreach ($messages as $message) :
                $result[] = $message->getMessage();
            endforeach;
            return $this->response->setJsonContent($result);
		}
        else{
            $collection = new GamesDatabase();
            $collection->id = $this ->gamesidgen->getNewId('gameidgen');
            $collection->game_id = $collection->id;
            $collection->status = 1;
			$collection->games_name = $input_data->games_name;
			$collection->games_folder = $input_data->games_folder;
			$collection->daily_tips = $input_data->daily_tips;
			$collection->tina = $input_data->tina;
			$collection->rahul = $input_data->rahul;
			$collection->game_type = $collection->games_name;
            $collection->created_at = date ( 'Y-m-d H:i:s' );
            $collection->created_by = $collection->id;
            $collection->modified_at = date ( 'Y-m-d H:i:s' );
            if ($collection->save()){
				$game_cor_val = $input_data -> gameCoreFrame;
					foreach($game_cor_val as $value ){
						$game_cor_map = new GamesCoreframeMap();
						$game_cor_map -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map -> grade_id = $input_data->grade_id;
						$game_cor_map -> standard_id = $value->standard_id;
						$game_cor_map -> gamecoretype = $value->gamecoretype;
						$game_cor_map -> indicator_id = $value->indicator_id;
						$game_cor_map -> framework_id = $value->framework_id;
						$game_cor_map -> subject_id = $value->subject_id;
						$game_cor_map -> game_id = $collection->id;
						$game_cor_map->save();
						$game_cor_map2 = new GamesCoreframeMap();
						$game_cor_map2 -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map2 -> grade_id = $game_cor_map -> grade_id;
						$game_cor_map2 -> standard_id = $game_cor_map -> standard_id;
						$game_cor_map2 -> gamecoretype = $game_cor_map -> gamecoretype;
						$game_cor_map2 -> indicator_id = $value->indicator_id1;
						$game_cor_map2 -> framework_id = $game_cor_map -> framework_id;
						$game_cor_map2 -> subject_id = $game_cor_map -> subject_id;
						$game_cor_map2 -> game_id = $collection->id;
						$game_cor_map2-> save();
						$game_cor_map3 = new GamesCoreframeMap();
						$game_cor_map3 -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map3 -> grade_id = $game_cor_map -> grade_id;
						$game_cor_map3 -> standard_id = $game_cor_map -> standard_id;
						$game_cor_map3 -> gamecoretype = $game_cor_map -> gamecoretype;
						$game_cor_map3 -> indicator_id = $value->indicator_id2;
						$game_cor_map3 -> framework_id = $game_cor_map -> framework_id;
						$game_cor_map3 -> subject_id = $game_cor_map -> subject_id;
						$game_cor_map3 -> game_id = $collection->id;
						$game_cor_map3->save();
						$game_cor_map4 = new GamesCoreframeMap();
						$game_cor_map4 -> id = $this ->gamesidgen->getNewId('gameidgen');
						$game_cor_map4 -> grade_id = $game_cor_map -> grade_id;
						$game_cor_map4 -> standard_id = $game_cor_map -> standard_id;
						$game_cor_map4 -> gamecoretype = $game_cor_map -> gamecoretype;
						$game_cor_map4 -> indicator_id = $value->indicator_id3;
						$game_cor_map4 -> framework_id = $game_cor_map -> framework_id;
						$game_cor_map4 -> subject_id = $game_cor_map -> subject_id;
						$game_cor_map4 -> game_id = $collection->id;
						$game_cor_map4->save();
					}
				$getName = $input_data->file_name ;
				$tmpfname = 'excel/'.$getName;
				
				$excelReader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpfname);
				$excelReader->setReadDataOnly(true);
				$excelObj = $excelReader->load($tmpfname);
				$worksheet = $excelObj->getSheet(0);//
				$lastRow = $worksheet->getHighestRow();
				$lastCol = $worksheet->getHighestColumn();
				
				//$data = array();
				// $data2 = array();
				$row = 1;
				$slidNum = 2;
										$slide = array();
										$num = 1;
										for($x = 'A'; $x != $lastCol; $x++){
											$y =  $x.''.$row;
											$slide[$x] = $worksheet->getCell($y)->getValue();
											if($x > 'C' && $x <= $lastCol){
												if($num == 1){
													$num ++;
												}
												else{
													$slide['num'.$x] = $slidNum;
													$slidNum ++;
													$num = 1;
												}
											}
											
										}
				for ($row = 2; $row <= $lastRow; $row++) {
					 $dual_column = 1;
					for($x = 'A'; $x != $lastCol; $x++){
						$y =  $x.''.$row;
						$data1[] = [$x => $worksheet->getCell($y)->getValue()];
						if($x == 'A'){
							$A_value2 = $worksheet->getCell($y)->getValue();
							$getvalue1 = GameTaggingHealthMaster::findFirstByheath_name($A_value2);
							$A_value = $getvalue1->id;
						}
						if($x == 'C'){
							$B_value2 = $worksheet->getCell($y)->getValue();
							$getvalue2 = GameTaggingDataCapture::findFirstBydata_capture($B_value2);
							$B_value = $getvalue2->id;
						}
						if($x > 'C' && $x <= $lastCol){
							if($dual_column == 1){
								// $slide_Number2 = $worksheet->getCell($y)->getValue();
								$getvalue3 = GameTaggingSlideCategory::findFirstBycategory_name($slide[$x]);
								$slide_Number = $getvalue3->id;
								$dual_column++;
							}else
							{
								$weightage = $worksheet->getCell($y)->getValue();
								$data_sava = new GameTaggingTransection();
								$data_sava -> GameID = $collection->id;
								$data_sava -> Slideid = $slide_Number;
								$data_sava -> slidenum = $slide['num'.$x];
								$data_sava -> health_parameter = $A_value;
								$data_sava -> Data_Capture_Parameter = $B_value;
								$data_sava -> Weightage = $weightage;
								$data_sava -> save();
									//return $this->response->setJsonContent(['status' => false, 'message' => 'Failed','inputs' => $data_sava]);
								
								$dual_column = 1;
							}
						} 

					}
					
				}
				
				$excel = new GameTaggingExcel();
				$excel->game_id = $collection->id;
				$excel->excel_sheet = $getName;
				if(!$excel->save()){
					return $this->response->setJsonContent(['status' => false, 'message' => 'Failed','data' => $excel,$getName]);
				}
					
						$savedata = array();
						$savedata_valau['game_id'] = $collection->id;
						$savedata_valau['grade_id'] = $input_data->grade_id;
						$savedata[] = $savedata_valau;
						  return $this->response->setJsonContent(['status' => true, 'message' => 'succefully','data' => $savedata,]);
						}
					else{
						return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
					}
			}
    }

    /**
     * This function using to GamesDatabase information edit
     */
	 
	public function questionanswer(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation();
        $game_quetion_answer = $input_data -> gameQuestionanswer;
		$tagging =  0;
		foreach($game_quetion_answer as $game_quetion){
			$question_answer = new GamesQuestionAnswer();
			$question_answer -> id = $this ->gamesidgen->getNewId('gameidgen');
			$question_answer -> question_id = $game_quetion->question_id;
			$question_answer -> question = $game_quetion->question;
			$question_answer -> answer = $game_quetion->answer;
			$question_answer -> game_type = $game_quetion->game_type;
			$question_answer -> game_type_value = $game_quetion->game_type_value;
			$question_answer -> answer_des = $game_quetion->answer_des;
			$question_answer -> game_id = $input_data -> game_id;
			if(!$question_answer -> save()){
				return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
			}
			/* if($question_answer -> save ()){
				$game_question_taggin = $game_quetion->questionCoreFrame;
				$tagging = +1;
				foreach($game_question_taggin as $value){
					$collection1 = new QuestionGameCoreMap();
					$collection1 -> question_wight = $value->weightage;
					$collection1 -> game_id = $input_data -> game_id;
					$collection1 -> question_id = $question_answer -> id;
					$collection1 -> grade_id = $input_data -> grade_id;
					$collection1 -> framework_id = $value->quesframework_id;
					$collection1 -> subject_id = $value->quessubject_id;
					$collection1 -> standard_id = $value->quesstandard_id;
					$collection1 -> tagging = $tagging;
					$collection1 -> indicator_id = $value->quesindicator_id;
					$collection1 -> save();
					$collection2 = new QuestionGameCoreMap();
					$collection2 -> question_wight = $value->weightage;
					$collection2 -> game_id = $input_data -> game_id;
					$collection2 -> question_id = $question_answer -> id;
					$collection2 -> grade_id = $input_data -> grade_id;
					$collection2 -> framework_id = $value->quesframework_id;
					$collection2 -> subject_id = $value->quessubject_id;
					$collection2 -> standard_id = $value->quesstandard_id;
					$collection2 -> tagging = $tagging;
					$collection2 -> indicator_id = $value->quesindicator_id1;
					$collection2 -> save();
					$collection3 = new QuestionGameCoreMap();
					$collection3 -> question_wight = $value->weightage;
					$collection3 -> game_id = $input_data -> game_id;
					$collection3 -> question_id = $question_answer -> id;
					$collection3 -> grade_id = $input_data -> grade_id;
					$collection3 -> framework_id = $value->quesframework_id;
					$collection3 -> subject_id = $value->quessubject_id;
					$collection3 -> standard_id = $value->quesstandard_id;
					$collection3 -> tagging = $tagging;
					$collection3 -> indicator_id = $value->quesindicator_id2;
					$collection3 -> save();
					$collection4 = new QuestionGameCoreMap();
					$collection4 -> question_wight = $value->weightage;
					$collection4 -> game_id = $input_data -> game_id;
					$collection4 -> question_id = $question_answer -> id;
					$collection4 -> grade_id = $input_data -> grade_id;
					$collection4 -> framework_id = $value->quesframework_id;
					$collection4 -> subject_id = $value->quessubject_id;
					$collection4 -> standard_id = $value->quesstandard_id;
					$collection4 -> tagging = $tagging;
					$collection4 -> indicator_id = $value->quesindicator_id3;
					$collection4 -> save();
				}	//
				
			} */
			/* else{
				return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
			} */
		}
		return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
	}
    public function update() {

        $input_data = $this->request->getJsonRawBody();
        $game_id = isset($input_data->games_id) ? $input_data->games_id : '';
        if (empty($game_id)):
            return $this->response->setJsonContent(['status' => false, 'message' => 'Id is null']);
        else:
            $validation = new Validation();
            $messages = $validation->validate($input_data);
            if (count($messages)):
                foreach ($messages as $message):
                    $result[] = $message->getMessage();
                endforeach;
                return $this->response->setJsonContent($result);
            else:
                $collection = GamesDatabase::findFirstBygame_id($game_id);
                if ($collection):
                    $collection->games_name = $input_data->games_name;
					$collection->games_folder = $input_data->games_folder;
					$collection->daily_tips = $input_data->daily_tips;
                    if ($collection->save()):
						$getexcel = GameTaggingExcel::findFirstBygame_id($game_id);
						if($getexcel){
							$getexcel->excel_sheet = $input_data->file_name;
							if($getexcel-> save()){
								$excaldalete = GameTaggingTransection::findFirstByGameID($game_id);
								if($excaldalete){
									if($excaldalete->delete()){
										$getName = $input_data->file_name ;
										$tmpfname = 'excel/'.$getName;
										
										$excelReader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpfname);
										$excelReader->setReadDataOnly(true);
										$excelObj = $excelReader->load($tmpfname);
										$worksheet = $excelObj->getSheet(0);//
										$lastRow = $worksheet->getHighestRow();
										$lastCol = $worksheet->getHighestColumn();
										
										//$data = array();
										// $data2 = array();
										$row = 1;
										$slidNum = 2;
										$slide = array();
										$num = 1;
										for($x = 'A'; $x != $lastCol; $x++){
											$y =  $x.''.$row;
											$slide[$x] = $worksheet->getCell($y)->getValue();
											if($x > 'C' && $x <= $lastCol){
												if($num == 1){
													$num ++;
												}
												else{
													$slide['num'.$x] = $slidNum;
													$slidNum ++;
													$num = 1;
												}
											}
											
										}
										// return $this->response->setJsonContent(['status' => true, 'message' => $slide['C']]);
										for ($row = 2; $row <= $lastRow; $row++) {
											// $data1 = array();
											// $slide_Number = array();
											// $weightage = array();
											 $dual_column = 1;
											for($x = 'A'; $x != $lastCol; $x++){
												$y =  $x.''.$row;
												$data1[] = [$x => $worksheet->getCell($y)->getValue()];
												if($x == 'A'){
													$A_value2 = $worksheet->getCell($y)->getValue();
													$getvalue1 = GameTaggingHealthMaster::findFirstByheath_name($A_value2);
													$A_value = $getvalue1->id;
												}
												if($x == 'C'){
													$B_value2 = $worksheet->getCell($y)->getValue();
													$getvalue2 = GameTaggingDataCapture::findFirstBydata_capture($B_value2);
													$B_value = $getvalue2->id;
												}
												if($x > 'C' && $x <= $lastCol){
													if($dual_column == 1){
														// $slide_Number2 = $worksheet->getCell($y)->getValue();
														$getvalue3 = GameTaggingSlideCategory::findFirstBycategory_name($slide[$x]);
														$slide_Number = $getvalue3->id;
														$dual_column++;
													}else
													{
														$weightage = $worksheet->getCell($y)->getValue();
														$data_sava = new GameTaggingTransection();
														$data_sava -> GameID = $collection->id;
														$data_sava -> Slideid = $slide_Number;
														$data_sava -> slidenum = $slide['num'.$x];
														$data_sava -> health_parameter = $A_value;
														$data_sava -> Data_Capture_Parameter = $B_value;
														$data_sava -> Weightage = $weightage;
														$data_sava -> save();
															//return $this->response->setJsonContent(['status' => false, 'message' => 'Failed','inputs' => $data_sava]);
														
														$dual_column = 1;
													}
												} 

											}
											
										}
									return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
									}
								}
								else{
									$getName = $input_data->file_name ;
									$tmpfname = 'excel/'.$getName;
									
									$excelReader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpfname);
									$excelReader->setReadDataOnly(true);
									$excelObj = $excelReader->load($tmpfname);
									$worksheet = $excelObj->getSheet(0);//
									$lastRow = $worksheet->getHighestRow();
									$lastCol = $worksheet->getHighestColumn();
									
									//$data = array();
									// $data2 = array();
									$row = 1;
									$slidNum = 2;
										$slide = array();
										$num = 1;
										for($x = 'A'; $x != $lastCol; $x++){
											$y =  $x.''.$row;
											$slide[$x] = $worksheet->getCell($y)->getValue();
											if($x > 'C' && $x <= $lastCol){
												if($num == 1){
													$num ++;
												}
												else{
													$slide['num'.$x] = $slidNum;
													$slidNum ++;
													$num = 1;
												}
											}
											
										}
									// return $this->response->setJsonContent(['status' => true, 'message' => $slide['C']]);
									for ($row = 2; $row <= $lastRow; $row++) {
										// $data1 = array();
										// $slide_Number = array();
										// $weightage = array();
										 $dual_column = 1;
										for($x = 'A'; $x != $lastCol; $x++){
											$y =  $x.''.$row;
											$data1[] = [$x => $worksheet->getCell($y)->getValue()];
											if($x == 'A'){
												$A_value2 = $worksheet->getCell($y)->getValue();
												$getvalue1 = GameTaggingHealthMaster::findFirstByheath_name($A_value2);
												$A_value = $getvalue1->id;
											}
											if($x == 'C'){
												$B_value2 = $worksheet->getCell($y)->getValue();
												$getvalue2 = GameTaggingDataCapture::findFirstBydata_capture($B_value2);
												$B_value = $getvalue2->id;
											}
											if($x > 'C' && $x <= $lastCol){
												if($dual_column == 1){
													// $slide_Number2 = $worksheet->getCell($y)->getValue();
													$getvalue3 = GameTaggingSlideCategory::findFirstBycategory_name($slide[$x]);
													$slide_Number = $getvalue3->id;
													$dual_column++;
												}else
												{
													$weightage = $worksheet->getCell($y)->getValue();
													$data_sava = new GameTaggingTransection();
													$data_sava -> GameID = $collection->id;
													$data_sava -> Slideid = $slide_Number;
													$data_sava -> slidenum = $slide['num'.$x];
													$data_sava -> health_parameter = $A_value;
													$data_sava -> Data_Capture_Parameter = $B_value;
													$data_sava -> Weightage = $weightage;
													$data_sava -> save();
														//return $this->response->setJsonContent(['status' => false, 'message' => 'Failed','inputs' => $data_sava]);
													
													$dual_column = 1;
												}
											} 

										}
										
									}
									return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
								}
							}
						}
						else{
							$getexcel = new GameTaggingExcel();
							$getexcel->game_id = $game_id;
							$getexcel->excel_sheet = $input_data->file_name;
							if($getexcel->save()){
								$getName = $input_data->file_name ;
								$tmpfname = 'excel/'.$getName;
								
								$excelReader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpfname);
								$excelReader->setReadDataOnly(true);
								$excelObj = $excelReader->load($tmpfname);
								$worksheet = $excelObj->getSheet(0);//
								$lastRow = $worksheet->getHighestRow();
								$lastCol = $worksheet->getHighestColumn();
								
								//$data = array();
								// $data2 = array();
								$row = 1;
								$slidNum = 2;
										$slide = array();
										$num = 1;
										for($x = 'A'; $x != $lastCol; $x++){
											$y =  $x.''.$row;
											$slide[$x] = $worksheet->getCell($y)->getValue();
											if($x > 'C' && $x <= $lastCol){
												if($num == 1){
													$num ++;
												}
												else{
													$slide['num'.$x] = $slidNum;
													$slidNum ++;
													$num = 1;
												}
											}
											
										}
								// return $this->response->setJsonContent(['status' => true, 'message' => $slide['C']]);
								for ($row = 2; $row <= $lastRow; $row++) {
									// $data1 = array();
									// $slide_Number = array();
									// $weightage = array();
									 $dual_column = 1;
									for($x = 'A'; $x != $lastCol; $x++){
										$y =  $x.''.$row;
										$data1[] = [$x => $worksheet->getCell($y)->getValue()];
										if($x == 'A'){
											$A_value2 = $worksheet->getCell($y)->getValue();
											$getvalue1 = GameTaggingHealthMaster::findFirstByheath_name($A_value2);
											$A_value = $getvalue1->id;
										}
										if($x == 'C'){
											$B_value2 = $worksheet->getCell($y)->getValue();
											$getvalue2 = GameTaggingDataCapture::findFirstBydata_capture($B_value2);
											$B_value = $getvalue2->id;
										}
										if($x > 'C' && $x <= $lastCol){
											if($dual_column == 1){
												// $slide_Number2 = $worksheet->getCell($y)->getValue();
												$getvalue3 = GameTaggingSlideCategory::findFirstBycategory_name($slide[$x]);
												$slide_Number = $getvalue3->id;
												$dual_column++;
											}else
											{
												$weightage = $worksheet->getCell($y)->getValue();
												$data_sava = new GameTaggingTransection();
												$data_sava -> GameID = $collection->id;
												$data_sava -> Slideid = $slide_Number;
												$data_sava -> slidenum = $slide['num'.$x];
												$data_sava -> health_parameter = $A_value;
												$data_sava -> Data_Capture_Parameter = $B_value;
												$data_sava -> Weightage = $weightage;
												$data_sava -> save();
													//return $this->response->setJsonContent(['status' => false, 'message' => 'Failed','inputs' => $data_sava]);
												
												$dual_column = 1;
											}
										} 

									}
									
								}
									 return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
							}
						}
                        return $this->response->setJsonContent(['status' => true, 'message' => 'succefully']);
                    else:
                        return $this->response->setJsonContent(['status' => false, 'message' => 'Failed']);
                    endif;
                else:
                    return $this->response->setJsonContent(['status' => false, 'message' => 'Invalid id']);
                endif;
            endif;
        endif;
    }
	
	/**
	 * This function using delete kids caregiver information
	 */
	public function delete() {
		$input_data = $this->request->getJsonRawBody ();
		$id = isset ( $input_data->id ) ? $input_data->id : '';
		if (empty ( $id )) :
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Id is null' 
			] );
		 else :
			$collection = GamesDatabase::findFirstByid ( $id );
			if ($collection) :
				if ($collection->delete ()) :
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'Message' => 'Record has been deleted succefully ' 
					] );
				 else :
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'Message' => 'Data could not be deleted' 
					] );
				endif;
			 else :
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'ID doesn\'t']);
            endif;
        endif;
    }

	/* 
	*@function savereplaycount
	@Description (to get the game slide replay count based on slide no and question )
	*/

	public function savereplaycount(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation ();
		$validation->add ( 'game_id', new PresenceOf ( [ 
			'message' => 'Game id is required' 
		] ) );
		$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
			'message' => 'Please give the kid id' 
		] ) );
		/* $validation->add ( 'answers', new PresenceOf ( [ 
			'message' => 'Please give the answers' 
		] ) ); */
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent ( [ 
				'status' => false,
				'message' => $result
			] );
		}
		else {
			$qut_ans = new GameSlideReplayCount();
			$qut_ans -> session_id = $input_data->session_id;
			$qut_ans -> game_id = $input_data -> game_id;
			$qut_ans -> nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
			if(empty($input_data -> question_id)){
				$qut_ans -> question_id = 0;
			} else {
				$qut_ans -> question_id = $input_data -> question_id;
			}
			$qut_ans -> slide_no = $input_data -> slide_no;
			$qut_ans -> create_at = date('Y-m-d');
			if($qut_ans->save ()){
				return $this->response->setJsonContent(['status' => true, 'message' => 'Answer save successfully']);
			}
			else{
				return $this->response->setJsonContent(['status' => false, 'message' => 'Answer save error', 'data_game' => $qut_ans]);
			}		
		}
	}


    
	public function savegamequsans(){
		$input_data = $this->request->getJsonRawBody();
		/* $validation = new Validation ();
			$validation->add ( 'game_id', new PresenceOf ( [ 
					'message' => 'Game id is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			/* $validation->add ( 'answers', new PresenceOf ( [ 
					'message' => 'Please give the answers' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => $result
				] );
			}  */
			if(empty($input_data)){
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Please give input values',
					'data' => $input_data
				] );
			}
			else {
				$collection = NidaraSchoolKidProfile::findFirstByncs_id($input_data -> nidara_kid_profile_id);
				if(empty($collection)){
					$qut_ans = new GamesAnswers();
					$qut_ans -> session_id = $input_data->session_id;
					$qut_ans -> game_id = $input_data -> game_id;
					$qut_ans -> nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
					$qut_ans -> questions_no = $input_data -> question_id;
					$qut_ans -> slide_no = $input_data -> slide_no;
					$qut_ans -> answers = $input_data -> options;
					$qut_ans -> actual_time = $input_data -> actual_time;
					$qut_ans -> object_name = $input_data -> object_val;
					$qut_ans -> slide_type = $input_data -> slide_type;
					$qut_ans -> replaycount = $input_data -> replaycount;
					$qut_ans -> time = $input_data -> time;
					$qut_ans -> created_at = date('Y-m-d');
					if($qut_ans->save ()){
						return $this->response->setJsonContent(['status' => true, 'message' => 'Answer save successfully']);
					}
					else{
						return $this->response->setJsonContent(['status' => false, 'message' => 'Answer save error', 'data' => $qut_ans]);
					}
				}
				else{
					$qut_ans = new SchoolGamesAnswers();
					$qut_ans -> id = $this->gamesidgen->getNewId ( 'answers');
					$qut_ans -> session_id = $input_data->session_id;
					$qut_ans -> game_id = $input_data -> game_id;
					$qut_ans -> nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
					$qut_ans -> questions_no = $input_data -> question_id;
					$qut_ans -> slide_no = $input_data -> slide_no;
					$qut_ans -> answers = $input_data -> options;
					$qut_ans -> actual_time	 = $input_data -> actual_time	;
					$qut_ans -> time = $input_data -> time;
					$qut_ans -> created_at = date('Y-m-d');
					if($qut_ans->save ()){
						return $this->response->setJsonContent(['status' => true, 'message' => 'Answer save successfully']);
					}
					else{
						return $this->response->setJsonContent(['status' => false, 'message' => 'Answer save error']);
					}
				}
				
			}
	}
	public function savegamestatus(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation ();
			$validation->add ( 'game_id', new PresenceOf ( [ 
					'message' => 'Game id is required' 
			] ) );
			/* $validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) ); */
			/* $validation->add ( 'answers', new PresenceOf ( [ 
					'message' => 'Please give the answers' 
			] ) ); */
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => $result
				] );
			}
			else{
					$kidstatus = new KidsGamesStatus ();
					$kidstatus->game_id = $input_data -> game_id;
					$kidstatus->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$kidstatus->session_id = $input_data->session_id;
					$kidstatus->current_status =  $input_data->current_status;
					$kidstatus->created_date = date('Y-m-d');
				if($kidstatus->save ()){
				
					return $this->response->setJsonContent ( [ 
							'status' => true,
							'message' => $kidstatus
					] );
				}
				else{
					return $this->response->setJsonContent ( [ 
							'status' => false,
							'message' => 'Error'
					] );
				}
			}
	}
	
     /**
     * Save game result
     */
	public function savegamesresult() {
		try {
			$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
			$baseurl = $this->config->baseurl;
			$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
			if ($token_check->status != 1) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Invalid User" 
				] );
			}
			$input_data = $this->request->getJsonRawBody ();
			if (empty ( $input_data )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'message' => 'Please give the same result'
				] );
			}
			$validation = new Validation ();
			$validation->add ( 'game_id', new PresenceOf ( [ 
					'message' => 'Game id is required' 
			] ) );
			$validation->add ( 'nidara_kid_profile_id', new PresenceOf ( [ 
					'message' => 'Please give the kid id' 
			] ) );
			$validation->add ( 'answers', new PresenceOf ( [ 
					'message' => 'Please give the answers' 
			] ) );
			$messages = $validation->validate ( $input_data );
			if (count ( $messages )) {
				foreach ( $messages as $message ) {
					$result [] = $message->getMessage ();
				}
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => $result
				] );
			}
			foreach ( $input_data->answers as $answer ) {
				if(!isset($answer->options)){
					return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => "Please give the options"
					] );	
				}
				foreach ( $answer->options as $option ) {
					$optionobj =Options::findFirstByid($option);
					$answers = new Answers ();
					$answers->id = $this->gamesidgen->getNewId ( 'answers' );
					$answers->questions_id = $answer->question_id;
					$answers->session_id = $input_data->session_id;
					$answers->is_correct = $optionobj->is_answer;
					$answers->options_id = $option;
					$answers->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
					$answers->created_at = date ( 'Y-m-d H:i:s' );
					$answers->created_by = 1;
					$answers->save ();
				}
			}
			// Save the result status for kid
			$kidstatus = KidsGamesStatus::findFirstBynidara_kid_profile_id ( $input_data->nidara_kid_profile_id );
			if (! $kidstatus) {
				$kidstatus = new KidsGamesStatus ();
				$kidstatus->id = $this->gamesidgen->getNewId ( 'kidgamestatus' );
				$kidstatus->nidara_kid_profile_id = $input_data->nidara_kid_profile_id;
			}
			$gamemapid=$this->getGuidedLearningId($input_data->game_id);
			$kidstatus->guided_learning_games_map_id = $gamemapid->guided_learning_schedule_id;
			if ($input_data->current_status == 'quit') {
				$kidstatus->current_status = "quit";
			} else {
				$kidstatus->current_status = "completed";
			}
			$kidstatus->save ();
			
			return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'Game saved successfully' 
			] );
		} catch ( Exception $e ) {echo $e->getMessage();
			return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'Error while saving the datas' 
			] );
		}
	}
	
	
	
	
	public function getgameinfo(){
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => 'Hi'
			] );
	}
	
	public function gameintroslidetime(){
		$input_data = $this->request->getJsonRawBody();
		$validation = new Validation ();
		$validation->add ( 'game_id', new PresenceOf ( [ 
			'message' => 'Game id is required' 
		] ) );
		$validation->add ( 'child_id', new PresenceOf ( [ 
			'message' => 'Please give the kid id' 
		] ) );
		$messages = $validation->validate ( $input_data );
		if (count ( $messages )) {
			foreach ( $messages as $message ) {
				$result [] = $message->getMessage ();
			}
			return $this->response->setJsonContent ( [ 
				'status' => false,
				'message' => $result
			] );
		}
		else{
			$collect = new GameIntroSlideTimes();
			$collect->game_id = $input_data->game_id;
			$collect->child_id = $input_data->child_id;
			$collect->slide_number = $input_data->slide_number;
			$collect->time_count = $input_data->time_count;
			if(!$collect->save()){
				return $this->response->setJsonContent ( [ 
					'status' => false,
					'message' => 'data cont save '
				] );
			}
			else{
				return $this->response->setJsonContent ( [ 
					'status' => true,
					'message' => 'data save succefully '
				] );
			}
		}
	}
	
	
	
	/**
	 * Get Game Map id
	 * @param integer $gameid
	 * @return array
	 */
	 
	 
	public function getgamedata(){
		return $this->response->setJsonContent ( [
				'status' => true,
				'data' => 'Hi'
			] );
	}
	public function getGuidedLearningId($gameid){
		$gamemap = $this->modelsManager->createBuilder ()->columns ( array (
				'GuidedLearningDayGameMap.id',
				'GuidedLearningDayGameMap.day_guided_learning_id',
		) )->from ( 'GamesDatabase' )
		->join ( 'GuidedLearningDayGameMap', 'GuidedLearningDayGameMap.game_id=GamesDatabase.id' )
		->inwhere ( "GamesDatabase.game_id", array (
				$gameid
		) )->getQuery ()->execute ();
		$guided_learning_map = array ();
		foreach ( $gamemap as $guided_learning_map ) {
			return $guided_learning_map;
		}
	}
	
	/**
	 * Get answer status
	 * @param object $answer
	 * @return number
	 */
	public function getlessonstatus($answer) {
		$gamestatus = $this->modelsManager->createBuilder ()->columns ( array (
				'Questions.id as question_id',
				'Options.id as option_id',
				"Options.is_answer",
				"Options.is_multi_answer" 
		) )->from ( 'Questions' )->leftjoin ( 'Options', 'Options.questions_id=Questions.id' )
		->inwhere ( "Questions.id", array (
				$answer->question_id 
		) )->inwhere ( "Options.id", $answer->options )->getQuery ()->execute ();
		$wrong_answer = 0;
		foreach ( $gamestatus as $game ) {
			if (empty ( $game->is_answer )) {
				$wrong_answer ++;
			}
		}
		return $wrong_answer;
	}
	
	/**
	 * Get Game info
	 * @return string
	 */
	public function getgameinfobygameid() {
		try {
			$headers = $this->request->getHeaders ();
			if (empty ( $headers ['Token'] )) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Please give the token" 
				] );
			}
			$baseurl = $this->config->baseurl;
			$token_check = $this->tokenvalidate->tokencheck ( $headers ['Token'], $baseurl );
			if ($token_check->status != 1) {
				return $this->response->setJsonContent ( [ 
						"status" => false,
						"message" => "Invalid User" 
				] );
			}
			$input_data = $this->request->getJsonRawBody ();
			$game_id = isset ( $input_data->game_id ) ? $input_data->game_id : '';
			if (empty ( $game_id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Please give the game id' 
				] );
			}
			if (empty ( $input_data->nidara_kid_profile_id )) {
				return $this->response->setJsonContent ( [ 
						'status' => false,
						'Message' => 'Please give the kid id' 
				] );
			}
			$gamedatabase = $this->modelsManager->createBuilder ()->columns ( array (
					'Questions.id as question_id',
					'GamesDatabase.game_type' 
			) )->from ( 'Questions' )->leftjoin ( 'QuestionsTagging', 'QuestionsTagging.questions_id=Questions.id' )
			->leftjoin ( 'Indicators', 'QuestionsTagging.indicators_id=Indicators.id' )
			->leftjoin ( 'GamesTagging', 'Indicators.id=GamesTagging.indicators_id' )
			->leftjoin ( 'GamesDatabase', 'GamesTagging.games_database_id=GamesDatabase.id' )
			->orderBy ( 'Questions.id' )->inwhere ( "GamesDatabase.game_id", array (
					$game_id 
			) )->getQuery ()->execute ();
			$gamedatas = array ();
			$i = 1;
			$game_name = $this->getGameNameByGameId ( $game_id );
			$optionssdataarray = array ();
			foreach ( $gamedatabase as $gamedata ) {
				$options = Options::findByquestions_id ( $gamedata->question_id );
				$optionssdataarray = array ();
				foreach ( $options as $option ) {
					$optionssdata ['option_id'] = $option->id;
					$optionssdata ['option'] = $option->option;
					$optionssdata ['is_correct'] = $option->is_answer;
					$optionssdata ['is_image'] = $option->is_image;
					$optionssdata ['image_path'] = $option->id.'.png';
					$optionssdataarray [] = $optionssdata;
				}
				$questionsdata ['options'] = $optionssdataarray;
				$questionsdata ['questions_id'] = $gamedata->question_id;
				$questionsdatas [] = $questionsdata;
			}
			$session_id = $this->getSessionId ( $game_id, $input_data->nidara_kid_profile_id );
			$questionaries ['game_id'] = $game_id;
			$questionaries ['session_id'] = $session_id;
			$questionaries ['questionaries'] = $questionsdatas;
			return $this->response->setJsonContent ( $questionaries );
		} catch ( Exception $e ) {
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => 'Error while getting the datas'.$e->getMessage() 
			]);
		}
	}
	
	
	public function getgameexcelsheet(){
		$subject = GameExcelSheet::find();
        if ($subject):
            return $this->response->setJsonContent([
					'status' => true, 
					'data' => $subject		]);

        else:
            return $this->response->setJsonContent(['status' => false, 'Message' => 'Faield']);
        endif; 
	}
	/**
	 * Save session id
	 */
	public function getSessionMainId(){
	$gamesesstion->session_id = uniqid ();
	return $this->response->setJsonContent ([ 
					'status' => true,
					'data' => $gamesesstion->session_id 
			]);
	}
	/**
	 * Save game history
	 */
	public function getSessionId() {
		//$gamemapid = $this->getGuidedLearningId ( $gameid );
		$input_data = $this->request->getJsonRawBody();
		$gamehistory = new GameHistory ();
		$gamehistory->id = $this->gamesidgen->getNewId ( 'gameshistory' );
		$gamehistory->session_id = $input_data->session_id;
		$gamehistory->nidara_kid_profile_id = $input_data -> nidara_kid_profile_id;
		$gamehistory->guided_learning_games_map_id = $input_data->guided_learning_games_map_id;
		$gamehistory->created_at = date ( 'Y-m-d H:i:s' );
		$gamehistory->created_by = 1;
		if($gamehistory->save ()){
			return $this->response->setJsonContent ([ 
					'status' => true,
					'message' => 'Game saved successfully' 
			]);
		}
		else{
			return $this->response->setJsonContent ([ 
					'status' => false,
					'message' => $gamehistory 
			]);
		}
		
		//return $gamehistory->session_id;
	}
	
	/**
	 * Get Game name by game id
	 * @param string $gameid
	 * @return value
	 *
	public function getGameNameByGameId($gameid) {
		$gamearray = array (
				"5992eaf7c114b" => "A",
				"599006c9dd128" => "one",
				"599007029d6df" => "five",
				"599c561d37d82" => "Emotion motion",
				"599c54be3f0f1" => "Nutrition",
				"599c5402348c6" => "Yoga",
				"599c51fc3b5b4" => "Brain Game",
				"599c515ea3952" => "Write Number 5",
				"599c446c91889" => "Dinosaurs",
				"59903520328af" => "In The Sky",
				"599033e356d49" => "My Body",
				"59900e7c907ee" => "Big and Small"
		);
		return $gamearray [$gameid];
	}
	
	**
	 * Dummy function for test
	 */
	public function dummydata() {
		$json = file_get_contents ( APP_PATH . "/library/gamesdata/games.json" );
		return $this->response->setJsonContent ( json_decode ( $json, true ) );
	}
	
}
