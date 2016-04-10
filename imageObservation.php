<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
include 'ionicFunctions.inc.php';
$uploadData = $request->uploadData;
$project_id = $uploadData->project_id;
$site_id = $uploadData->site_id;
$user_id = $uploadData->user_id;
$lat = $uploadData->lat;
$lng = $uploadData->lng;

$observation_id = insertObservation($project_id, $site_id, $user_id, $lat, $lng);

print json_encode($observation_id);
exit();
?>