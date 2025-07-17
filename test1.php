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

// Load the image (local file or uploaded via form)
$imageFile = 'markerv1/rohit-sachin.jpg';
$imageBytes = file_get_contents($imageFile);

// Step 1: Detect all faces in the uploaded image
$detectResult = $rekognition->detectFaces([
    'Image' => [
        'Bytes' => $imageBytes,
    ],
    'Attributes' => ['DEFAULT'],
]);

$faces = $detectResult['FaceDetails'];



//$faces_encode = '[{"BoundingBox":{"Width":0.14248107373714447,"Height":0.21907907724380493,"Left":0.2819634675979614,"Top":0.09227294474840164},"Landmarks":[{"Type":"eyeLeft","X":0.2995549142360687,"Y":0.17618736624717712},{"Type":"eyeRight","X":0.3544691205024719,"Y":0.18582910299301147},{"Type":"mouthLeft","X":0.300441175699234,"Y":0.25285276770591736},{"Type":"mouthRight","X":0.3461546301841736,"Y":0.26127779483795166},{"Type":"nose","X":0.30541640520095825,"Y":0.22601057589054108}],"Pose":{"Roll":3.890087604522705,"Yaw":-27.272979736328125,"Pitch":-3.4093120098114014},"Quality":{"Brightness":67.58856201171875,"Sharpness":20.927310943603516},"Confidence":99.98575592041016},{"BoundingBox":{"Width":0.14134414494037628,"Height":0.19496607780456543,"Left":0.5431771278381348,"Top":0.17998027801513672},"Landmarks":[{"Type":"eyeLeft","X":0.562058687210083,"Y":0.249114528298378},{"Type":"eyeRight","X":0.6069796681404114,"Y":0.25034692883491516},{"Type":"mouthLeft","X":0.561899721622467,"Y":0.3174228072166443},{"Type":"mouthRight","X":0.5990675091743469,"Y":0.31882256269454956},{"Type":"nose","X":0.5571951866149902,"Y":0.27964863181114197}],"Pose":{"Roll":-2.2230112552642822,"Yaw":-36.56756591796875,"Pitch":8.634833335876465},"Quality":{"Brightness":76.17463684082031,"Sharpness":20.927310943603516},"Confidence":99.90861511230469}]';

//$faces = json_decode($faces_encode);

// Step 2: Load image with GD to crop each face
$imageResource = imagecreatefromjpeg($imageFile);
$imageWidth = imagesx($imageResource);
$imageHeight = imagesy($imageResource);

// Step 3: Loop through faces, crop each, match in collection
$collectionId = 'aws_face_project_collection';
$matchedResults = [];

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
    $tempPath = $tempDir . "/face_{$index}.jpg";
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
        'MaxFaces' => 3,
    ]);

    $matchedResults[] = $searchResult['FaceMatches'] ?? [];
}

// Step 5: Display results
foreach ($matchedResults as $i => $matches) {
    echo "Face " . ($i + 1) . " matches:\n";
    foreach ($matches as $match) {
        echo "- FaceId: " . $match['Face']['FaceId'] . " | Similarity: " . $match['Similarity'] . "%\n";
    }
    echo "\n";
}
?>
