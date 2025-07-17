<?php

require 'vendor/autoload.php';

use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;

// AWS credentials and config
$rekognition = new RekognitionClient([
    'credentials'=>[
        'key'=>'XXXX',
        'secret'=>'jXXctXXXXXXXXPvN6F',
    ],
    'region'=>'us-east-2',
    'version'=>'latest'
]);


define('UPLOAD_DIR', 'markerv1/');
$img = $_REQUEST['image_src'];
$attendanceDate=$_REQUEST['attendanceDate'];
$img = str_replace('data:image/jpeg;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$file = UPLOAD_DIR . uniqid() . '.jpeg';
$success = file_put_contents($file, $data);

ini_set('display_errors', 1);

//$file = 'markerv1/rohit-sachin.jpg';
$imageBytes = file_get_contents($file);

$date = date('Y-m-d',strtotime($attendanceDate));

// Step 1: Detect all faces in the uploaded image
$detectResult = $rekognition->detectFaces([
    'Image' => [
        'Bytes' => $imageBytes,
    ],
    'Attributes' => ['DEFAULT'],
]);

$faces = $detectResult['FaceDetails'];

// Step 2: Load image with GD to crop each face
$imageResource = imagecreatefromjpeg($file);
$imageWidth = imagesx($imageResource);
$imageHeight = imagesy($imageResource);


// Step 3: Loop through faces, crop each, match in collection
$collectionId = 'aws_face_project_collection';
$matchedResults = [];

$unique_id = rand();
foreach ($faces as $index => $face) {
    $box = $face['BoundingBox'];
    $left = (int)($box['Left'] * $imageWidth);
    $top = (int)($box['Top'] * $imageHeight);
    $width = (int)($box['Width'] * $imageWidth);
    $height = (int)($box['Height'] * $imageHeight);

    // Crop face
    $faceCrop = imagecrop($imageResource, ['x' => $left, 'y' => $top, 'width' => $width, 'height' => $height]);

    // Save temp cropped face

    // Create temp directory if it doesn't exist
    $tempDir = __DIR__ . "/temp";
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    // Save temp cropped face
    $tempPath = $tempDir . "/$unique_id._face_{$index}.jpg";
    imagejpeg($faceCrop, $tempPath);

    // Then continue processing...
    $faceBytes = file_get_contents($tempPath);


    // $tempPath =  __DIR__ ."/\tmp/\face_{$index}.jpg";
    // imagejpeg($faceCrop, $tempPath);

    // Get cropped face bytes
    $faceBytes = file_get_contents($tempPath);

    // Step 4: Search this face in collection
    $searchResult = $rekognition->searchFacesByImage([
        'CollectionId' => $collectionId,
        'Image' => [
            'Bytes' => $faceBytes,
        ],
        'FaceMatchThreshold' => 80,
        'MaxFaces' => 15,
    ]);

    $matchedResults[] = $searchResult['FaceMatches'] ?? [];
}


foreach ($matchedResults as $i => $matches) 
{
    $i + 1;
    $con = new PDO("mysql:host=localhost;dbname=face_project",'root','');

    foreach ($matches as $match) {
        $face_id = $match['Face']['FaceId'];

        $query3 = "SELECT * from tbl_student  WHERE  face_id LIKE '%". $face_id."%' ";       
        $sth4 = $con->prepare($query3);
        $sth4->execute();
        $res=$sth4->fetch();
        $register_number = $res['register_number'];
        if( $register_number != NULL){
            
            $ExternalImageId = $register_number;
   
            
            $query2 = "INSERT INTO tbl_students_attendance (`student_reg_number`,`is_present`, `date`, `created_date`, `updated_date`) 
                values ( '$ExternalImageId', '1', '$date',NOW(),NOW() ) "; 
            $sth2 = $con->prepare($query2);
            $sth2->execute();
            
            $id =  $con->lastInsertId();
        }
        
    }
    
    
}
if($id > 0){
    echo 1;
}else{
 echo 2;   
}

?>