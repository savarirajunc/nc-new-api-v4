<?php

$servername = "localhost";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, "nidara-v0");

$type = $argv[1]; // Input From terminal
if (isset($type)) {
    $name = $type;
}


$sql = "SHOW COLUMNS FROM $type";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
    $field[] = $row['Field'];
}



$newFileContent = "<?php
                    use Phalcon\Mvc\Micro;
                    use Phalcon\Validation;
                    use Phalcon\Validation\Validator\PresenceOf;

                    class " . ucfirst($name) . "Controller extends \Phalcon\Mvc\Controller {
                        public function index() {        
                        }";

$newFileContent .= "/**
                        * Fetch all Record from database :-
                        */
                       public function viewall() {
                           $"."subject = " . ucfirst($name) . "::find();
                           if ($"."subject):
                               return Json_encode($"."subject);
                           else:
                               return $"."this->response->setJsonContent(['status' => 'Error', 'Message' => 'Faield']);
                           endif;
                       }";
   
   $newFileContent .= " /*
                        * Fetch Record from database based on ID :-
                        */

                       public function getbyid($"."id = null) {

                           $"."input_data = $"."this->request->getJsonRawBody();
                           $"."id = isset($"."input_data->id) ? $"."input_data->id : '';
                           if (empty($"."id)):
                               return $"."this->response->setJsonContent(['status' => 'Error', 'message' => 'Invalid input parameter']);
                           else:
                               $"."collection = " . ucfirst($name) . "::findFirstByid($"."id);
                               if ($"."collection):
                                   return Json_encode($"."collection);
                               else:
                                   return $"."this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data not found']);
                               endif;
                           endif;
                       }";
   
   $newFileContent .="/**
                        * This function using to create " . ucfirst($name) . " information
                        */
                       public function create() {

                           $"."input_data = $"."this->request->getJsonRawBody();

                           /**
                            * This object using valitaion 
                            */
                           $"."validation = new Validation();";
   foreach($field as $field_name):
       $newFileContent .="$"."validation->add('".$field_name."', new PresenceOf(['message' => '".$field_name." is required']));\r\n";
   endforeach;
        

    $newFileContent .="$"."messages = $"."validation->validate($"."input_data);
                        if (count($"."messages)):
                            foreach ($"."messages as $"."message) {
                                $"."result[] = $"."message->getMessage();
                            }
                            return $"."this->response->setJsonContent($"."result);
                        else:
                            $"."collection = new " . ucfirst($name) . "();";
        
    foreach($field as $field_name):
        $newFileContent .="$"."collection->".$field_name." = $"."input_data->".$field_name.";\r\n";
    endforeach;
           
    $newFileContent .="  if ($"."collection->save()):
                                    return $"."this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
                                else:
                                    return $"."this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
                                endif;
                            endif;
                        }";
          
    $newFileContent.=" /**
                        * This function using to " . ucfirst($name) . " information edit
                        */
                       public function update($"."id = null) {

                           $"."input_data = $"."this->request->getJsonRawBody();
                           $"."id = isset($"."input_data->id) ? $"."input_data->id : '';
                           if (empty($"."id)):
                               return $"."this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
                           else:
                               $"."validation = new Validation();\r\n";
    
    foreach($field as $field_name):
       $newFileContent .="$"."validation->add('".$field_name."', new PresenceOf(['message' => '".$field_name."is required']));\r\n";
   endforeach; 
            
    $newFileContent.="$"."messages = $"."validation->validate($"."input_data);
                        if (count($"."messages)):
                            foreach ($"."messages as $"."message):
                                $"."result[] = $"."message->getMessage();
                            endforeach;
                            return $"."this->response->setJsonContent($"."result);
                        else:
                            $"."collection = " . ucfirst($name) . "::findFirstByid($"."id);
                            if ($"."collection):";
      foreach($field as $field_name):
         $newFileContent .="$"."collection->".$field_name." = $"."input_data->".$field_name.";\r\n";
        endforeach;
                    
        $newFileContent .=" if ($"."collection->save()):
                                            return $"."this->response->setJsonContent(['status' => 'Ok', 'message' => 'succefully']);
                                        else:
                                            return $"."this->response->setJsonContent(['status' => 'Error', 'message' => 'Failed']);
                                        endif;
                                    else:
                                        return $"."this->response->setJsonContent(['status' => 'Error', 'message' => 'Invalid id']);
                                    endif;
                                endif;
                            endif;
                        }";
          
          
    $newFileContent .="/**
                        * This function using delete kids caregiver information
                        */
                       public function delete() {

                           $"."input_data = $"."this->request->getJsonRawBody();
                           $"."id = isset($"."input_data->id) ? $"."input_data->id : '';
                           if (empty($"."id)):
                               return $"."this->response->setJsonContent(['status' => 'Error', 'message' => 'Id is null']);
                           else:
                               $"."collection = " . ucfirst($name) . "::findFirstByid($"."id);
                               if ($"."collection):
                                   if ($"."collection->delete()):
                                       return $"."this->response->setJsonContent(['status' => 'OK', 'Message' => 'Record has been deleted succefully ']);
                                   else:
                                       return $"."this->response->setJsonContent(['status' => 'Error', 'Message' => 'Data could not be deleted']);
                                   endif;
                               else:
                                   return $"."this->response->setJsonContent(['status' => 'Error', 'Message' => 'ID doesn\'t']);
                               endif;
                           endif;
                       }

                   }";
 //$model_name="phalcon module ".$name;
   // system($model_name);
$newFileName = 'app/controllers/' . ucfirst($name) . 'Controller.php';
if (file_put_contents($newFileName, $newFileContent) !== false) {
    echo "File created (" . basename($newFileName) . ")";
    chmod($newFileName, 0777);
} else {
    echo "Cannot create file (" . basename($newFileName) . ")";
} 

