<?php

require 'vendor/autoload.php';

$args = [
    'credentials'=>[
        'key'=>'XXXX',
        'secret'=>'jXXctXXXXXXXXPvN6F',
    ],
    'region'=>'us-east-2',
    'version'=>'latest'
];

$client = new Aws\Rekognition\RekognitionClient($args);


$imageFile = 'markerv1/rohit-sachin.jpg';

$date = date('Y-m-d',strtotime('14-04-2025'));

$result = $client->indexFaces([
    'CollectionId' => 'aws_test_finalv2',
    'Image' => [
        'Bytes' => file_get_contents($imageFile),
    ],
    //'ExternalImageId' => uniqid('face_'), // Optional: set a custom ID
    'DetectionAttributes' => [] // Can be ['ALL'] if you want emotion/age/gender/etc.
]);

echo json_encode($result['FaceRecords']);die;

//[{"Face":{"FaceId":"f4ca4351-f1c3-425a-9bf7-e1f6c0d2e463","BoundingBox":{"Width":0.07093219459056854,"Height":0.11223665624856949,"Left":0.42075294256210327,"Top":0.5291380286216736},"ImageId":"a091dc2e-d829-34a4-bd2f-ef2a2a37fb3c","ExternalImageId":"face_67fcbeebb9b13","Confidence":99.96995544433594},"FaceDetail":{"BoundingBox":{"Width":0.07093219459056854,"Height":0.11223665624856949,"Left":0.42075294256210327,"Top":0.5291380286216736},"Landmarks":[{"Type":"eyeLeft","X":0.4345535337924957,"Y":0.5672051310539246},{"Type":"eyeRight","X":0.4655752182006836,"Y":0.5603556036949158},{"Type":"mouthLeft","X":0.4418964982032776,"Y":0.6105477809906006},{"Type":"mouthRight","X":0.4679034352302551,"Y":0.6048222780227661},{"Type":"nose","X":0.45163917541503906,"Y":0.5863553285598755}],"Pose":{"Roll":-8.455382347106934,"Yaw":-5.4879841804504395,"Pitch":9.294404983520508},"Quality":{"Brightness":38.193572998046875,"Sharpness":5.775668621063232},"Confidence":99.96995544433594}},{"Face":{"FaceId":"ec075c78-5475-48ed-9cac-2e48a35f01fe","BoundingBox":{"Width":0.0705966204404831,"Height":0.1117347702383995,"Left":0.27634522318840027,"Top":0.5585412383079529},"ImageId":"a091dc2e-d829-34a4-bd2f-ef2a2a37fb3c","ExternalImageId":"face_67fcbeebb9b13","Confidence":99.89300537109375},"FaceDetail":{"BoundingBox":{"Width":0.0705966204404831,"Height":0.1117347702383995,"Left":0.27634522318840027,"Top":0.5585412383079529},"Landmarks":[{"Type":"eyeLeft","X":0.29862654209136963,"Y":0.5969598889350891},{"Type":"eyeRight","X":0.3296751379966736,"Y":0.5986446142196655},{"Type":"mouthLeft","X":0.2987593412399292,"Y":0.6374546885490417},{"Type":"mouthRight","X":0.32475122809410095,"Y":0.6388038992881775},{"Type":"nose","X":0.31541281938552856,"Y":0.6186782121658325}],"Pose":{"Roll":5.54368782043457,"Yaw":4.394208908081055,"Pitch":13.759309768676758},"Quality":{"Brightness":35.234439849853516,"Sharpness":5.775668621063232},"Confidence":99.89300537109375}}]


$response = '[{"Face":{"FaceId":"f4ca4351-f1c3-425a-9bf7-e1f6c0d2e463","BoundingBox":{"Width":0.07093219459056854,"Height":0.11223665624856949,"Left":0.42075294256210327,"Top":0.5291380286216736},"ImageId":"a091dc2e-d829-34a4-bd2f-ef2a2a37fb3c","ExternalImageId":"face_67fcbeebb9b13","Confidence":99.96995544433594},"FaceDetail":{"BoundingBox":{"Width":0.07093219459056854,"Height":0.11223665624856949,"Left":0.42075294256210327,"Top":0.5291380286216736},"Landmarks":[{"Type":"eyeLeft","X":0.4345535337924957,"Y":0.5672051310539246},{"Type":"eyeRight","X":0.4655752182006836,"Y":0.5603556036949158},{"Type":"mouthLeft","X":0.4418964982032776,"Y":0.6105477809906006},{"Type":"mouthRight","X":0.4679034352302551,"Y":0.6048222780227661},{"Type":"nose","X":0.45163917541503906,"Y":0.5863553285598755}],"Pose":{"Roll":-8.455382347106934,"Yaw":-5.4879841804504395,"Pitch":9.294404983520508},"Quality":{"Brightness":38.193572998046875,"Sharpness":5.775668621063232},"Confidence":99.96995544433594}},{"Face":{"FaceId":"ec075c78-5475-48ed-9cac-2e48a35f01fe","BoundingBox":{"Width":0.0705966204404831,"Height":0.1117347702383995,"Left":0.27634522318840027,"Top":0.5585412383079529},"ImageId":"a091dc2e-d829-34a4-bd2f-ef2a2a37fb3c","ExternalImageId":"face_67fcbeebb9b13","Confidence":99.89300537109375},"FaceDetail":{"BoundingBox":{"Width":0.0705966204404831,"Height":0.1117347702383995,"Left":0.27634522318840027,"Top":0.5585412383079529},"Landmarks":[{"Type":"eyeLeft","X":0.29862654209136963,"Y":0.5969598889350891},{"Type":"eyeRight","X":0.3296751379966736,"Y":0.5986446142196655},{"Type":"mouthLeft","X":0.2987593412399292,"Y":0.6374546885490417},{"Type":"mouthRight","X":0.32475122809410095,"Y":0.6388038992881775},{"Type":"nose","X":0.31541281938552856,"Y":0.6186782121658325}],"Pose":{"Roll":5.54368782043457,"Yaw":4.394208908081055,"Pitch":13.759309768676758},"Quality":{"Brightness":35.234439849853516,"Sharpness":5.775668621063232},"Confidence":99.89300537109375}}]';

$fin_response = json_decode($response);

$face_array = array();
for($i=0;$i<count($fin_response);$i++){
    $face_array[$i]['FaceId']= $fin_response[$i]->Face->FaceId;
    $face_array[$i]['ExternalImageId']= $fin_response[$i]->Face->ExternalImageId;
}
// $i=0; $face_array = array();
// foreach ($response as $record) {
//     $faceId = $record['Face']['FaceId'];
//     $ExternalImageId = $record['Face']['ExternalImageId'];
//     $face_array[$i]['FaceId'] = $faceId;
//     $face_array[$i]['ExternalImageId'] = $ExternalImageId;
//     $i = $i+1;
// }

print_r($face_array);die;
//[{"FaceId":"5b1ad23f-45cf-444b-bd59-fb90bc187341","ExternalImageId":"face_67fcafb3ac179"},{"FaceId":"ea0d0160-f3b2-4610-afc3-9aee2c534e43","ExternalImageId":"face_67fcafb3ac179"}]
for($f=0;$f<count($face_array);$f++){
    $ExternalImageId = $face_array[$f]['ExternalImageId'];
    $con = new PDO("mysql:host=localhost;dbname=face_project",'root','');
            
    echo $query2 = "INSERT INTO tbl_students_attendance (`student_reg_number`,`is_present`, `date`, `created_date`, `updated_date`) 
        values ( '$ExternalImageId', '1', '$date',NOW(),NOW() ) "; 
    $sth2 = $con->prepare($query2);
    $sth2->execute();
    
    $id =  $con->lastInsertId();
    
}