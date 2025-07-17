<?php 
//ini_set('display_errors', 1);
define('UPLOAD_DIR', 'uploadv1/');
$img = $_REQUEST['image_src'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);

$file = UPLOAD_DIR . uniqid() . '.jpeg';
$success = file_put_contents($file, $data);

$student_reg_number = $_REQUEST['student_reg_number'];

require 'vendor/autoload.php';


$args = [
'credentials'=>[
        'key'=>'XXXX',
        'secret'=>'jXXctXXXXXXXXPvN6F',
    ],
'region'=>'us-east-2',
'version'=>'latest'
];
 
try {
$client = new Aws\Rekognition\RekognitionClient($args);
        $resulting = $client->detectFaces([
           
            'Image' => [ // REQUIRED
                'Bytes' => file_get_contents($file),
                
            ],
        ]);
        $res_resulting = json_encode($resulting->toArray());
        $characters_res_resulting = json_decode($res_resulting);
      
         if(count($characters_res_resulting->FaceDetails) <= 1){
            
        $results = $client->searchFacesByImage([
            'CollectionId' => 'aws_face_project_collection', // REQUIRED
            'DetectionAttributes' => ["ALL", "DEFAULT"],
            'ExternalImageId' =>  "$student_reg_number",
            'Image' => [ // REQUIRED
                'Bytes' => file_get_contents($file),
                
            ],
            'MaxFaces' =>4,
            'QualityFilter' => 'LOW',
        ]);
    
    
        $res = json_encode($results->toArray());
    
    
        $characters = json_decode($res);

        $data = array();

        $check = $characters->FaceMatches;
         
        if(empty($check))
        {
            
             $result = $client->indexFaces([
            'CollectionId' => 'aws_face_project_collection', 
            'DetectionAttributes' => ["ALL", "DEFAULT"],
            'ExternalImageId' =>  "$student_reg_number",
            'Image' => [ 
                'Bytes' => file_get_contents($file),
              
            ],
            'MaxFaces' => 12,
            'QualityFilter' => 'LOW',
            ]);
            
            
            $res = json_encode($result['FaceRecords']);

            $fin_response = json_decode($res);

            $face_array = array();
            for($i=0;$i<count($fin_response);$i++){
                $face_array[$i]['FaceId']= $fin_response[$i]->Face->FaceId;
                $face_array[$i]['ExternalImageId']= $fin_response[$i]->Face->ExternalImageId;
            }

            
            $con = new PDO("mysql:host=localhost;dbname=face_project",'root','');
            
            for($i=0;$i<count($face_array);$i++){
                $reg_no =$face_array[$i]['ExternalImageId'];
                $FaceId =$face_array[$i]['FaceId'];
                $query2 = "INSERT INTO tbl_student 
                (`register_number`,`image_name`, `face_id`, `created_date`) 
                values ( '$reg_no', '$file', ' $FaceId',NOW() ) "; 
                $sth2 = $con->prepare($query2);
                $sth2->execute();
            }
            
            $id =  $con->lastInsertId();
            if($id > 0){
                echo 1;
            }else{
             echo 2;   
            }
        }else{
             echo 'Image already exists';die;
        }

}else{
            echo 'More then one face detect';die;
        }

}
catch(Exception $e) {
    echo 'Message: Something goes wrong in face capture due to aws';die;
}

