<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

include 'ionicFunctions.inc.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$uploadData = $request->uploadData;
$site_name = $uploadData->name;
$site_descr = $uploadData->description;
$site_lat = $uploadData->lat;
$site_lon = $uploadData->lon;
$projects = isset( $uploadData->project_uid ) ? $uploadData->project_uid : $uploadData->projects;

if( !empty( $projects ) )
{
	$site_id = createSite($site_name, $site_descr, $site_lat, $site_lon, $project_uid);
}

$uploadData->id = $site_id;
$results['site_uid'] = $site_id;

print json_encode($results);
exit();
?>
