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

$result = $client->createCollection([
    'CollectionId' => 'aws_face_project_collection', // REQUIRED
]);

//print_r($result);

//print_r($result->toArray());

$res = json_encode($result->toArray());

echo $res;

//{"StatusCode":200,"CollectionArn":"aws:rekognition:us-east-2:282423009935:collection\/aws_test_finalv2","FaceModelVersion":"7.0","@metadata":{"statusCode":200,"effectiveUri":"https:\/\/rekognition.us-east-2.amazonaws.com","headers":{"x-amzn-requestid":"e17429bd-cd93-417d-84c2-2ff104d6a671","content-type":"application\/x-amz-json-1.1","content-length":"128","date":"Sun, 13 Apr 2025 15:51:03 GMT"},"transferStats":{"http":[[]]}}}
//{"StatusCode":200,"CollectionArn":"aws:rekognition:us-east-2:282423009935:collection\/aws_face_project_collection","FaceModelVersion":"7.0","@metadata":{"statusCode":200,"effectiveUri":"https:\/\/rekognition.us-east-2.amazonaws.com","headers":{"x-amzn-requestid":"3c94ebc0-a0d9-44c6-a0ad-da44c658c250","content-type":"application\/x-amz-json-1.1","content-length":"139","date":"Sat, 19 Apr 2025 05:39:17 GMT"},"transferStats":{"http":[[]]}}}
?>