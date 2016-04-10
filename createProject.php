<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

include 'ionicFunctions.inc.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$uploadData = $request->uploadData;
$project_name = $uploadData->project_name;
$project_descr = $uploadData->project_descr;
$user_id = $uploadData->user_id;

$project_id = createProject($project_name, $project_descr);

$results['success'] = true; 

print json_encode($results);
exit();
?>
