<?php
use Phalcon\Mvc\Micro;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class FileuploadController extends \Phalcon\Mvc\Controller {
 

  public function uploadAction() {
	  $gamesesstion->session_id = uniqid ();
	  mkdir(('../public/upload/'),0775,true);
	  $upload = '../public/upload/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  $savefile ='/upload/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  if(mkdir($upload,0775,true)){
		  if($this->request->hasFiles() == true){
				 foreach ($this->request->getUploadedFiles() as $file) {
					$file->moveTo($upload . $file->getName());
					$file_name = $file->getName();
				}
			}else { 
				 die('You must choose at least one file to send. Please try again.');
			 }
		}
		$filearray = array();
		$filedata['path'] = $savefile .''. $file_name;
		$filedata['filename'] = $file_name;
		$filearray[] = $filedata;
		return $this->response->setJsonContent ([ 
			'status' => true,
			'data' => $filearray ,
		]);
  }

  public function uploadExcel(){
	$input_data = $this->request->getJsonRawBody();
	   
	$gamepath;
	$path;
	if ($this->request->isPost()) {
		foreach ($this->request->getPost() as $v) {
			$gamepath  = $v;
		}
	  }
	  $gamesesstion->session_id = uniqid ();
	  mkdir(('../public/upload/'),0777,true);
	  $upload = '../public/upload/school/excels/' . $gamepath . '/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  if(mkdir($upload,0777,true)){
		if($this->request->hasFiles() == true){
			   foreach ($this->request->getUploadedFiles() as $file) {
				  $file->moveTo($upload . $file->getName());
				  $file_name = $file->getName();
			  }
		  }else { 
			   die('You must choose at least one file to send. Please try again.');
		   }
		
	  }
	chmod($upload .''. $file_name, 0777);
	 $filearray = array();
	  $filedata['path'] = $upload .''. $file_name;
	  $filedata['filename'] = $file_name;
	  $filearray[] = $filedata;
	  return $this->response->setJsonContent ([ 
		  'status' => true,
		  'data' => $filearray ,
	  ]);
	  
  }

  public function uploadUserPhoto(){
	$input_data = $this->request->getJsonRawBody();
	  $gamesesstion->session_id = uniqid ();
	  mkdir(('../public/upload/'),0775,true);
	  $upload = '../public/upload/school/userphoto/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  $savefile = '/upload/school/userphoto/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  if(mkdir($upload,0775,true)){
		if($this->request->hasFiles() == true){
			   foreach ($this->request->getUploadedFiles() as $file) {
				  $file->moveTo($upload . $file->getName());
				  $file_name = $file->getName();
			  }
		  }else { 
			   die('You must choose at least one file to send. Please try again.');
		   }
	  }
	  $filearray = array();
	  $filedata['path'] = $savefile .''. $file_name;
	  $filedata['filename'] = $file_name;
	  $filearray[] = $filedata;
	  return $this->response->setJsonContent ([ 
		  'status' => true,
		  'data' => $filearray ,
	  ]);
	  
  }





  
  public function uploadfilesave(){
		$input_data = $this->request->getJsonRawBody();
		$insetdata = new ParentFeedbackFile();
		$insetdata -> user_id = $input_data -> user_id;
		$insetdata -> file_type =  $input_data->file_type;
		$insetdata -> path =  $input_data->path;
		$insetdata -> create_at = date('Y-m-d');
		if(!$insetdata -> save()){
			return $this->response->setJsonContent ([ 
			'status' => false,
			'message' => 'field'
			]);
		}
		return $this->response->setJsonContent ([ 
			'status' => true,
			'message' => 'file is succefully imported'
		]);
  }


    public function uploadcolor(){
	$input_data = $this->request->getJsonRawBody();
	  $gamesesstion->session_id = uniqid ();
	  mkdir(('../public/upload/'),0775,true);
	  $upload = '../public/upload/colorgame/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  $savefile = '/upload/colorgame/' . date('Y-m-d') . '/' . $gamesesstion->session_id . '/';
	  if(mkdir($upload,0775,true)){
		if($this->request->hasFiles() == true){
			   foreach ($this->request->getUploadedFiles() as $file) {
				  $file->moveTo($upload . $file->getName());
				  $file_name = $file->getName();
			  }
		  }else { 
			   die('You must choose at least one file to send. Please try again.');
		   }
	  }
	  $filearray = array();
	  $filedata['path'] = $savefile .''. $file_name;
	  $filedata['filename'] = $file_name;
	  $filearray[] = $filedata;
	  return $this->response->setJsonContent ([ 
		  'status' => true,
		  'data' => $filearray ,
	  ]);
	  
  }
}
