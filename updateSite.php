<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

include 'ionicFunctions.inc.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$uploadData = $request->uploadData;
$project_id = $uploadData->project_id;
$site_id = $uploadData->site_id;

updateSite($site_id, $project_id);

$results['site_id'] = $site_id;
$results['project_id'] = $project_id;

print json_encode($results);
exit();
?>