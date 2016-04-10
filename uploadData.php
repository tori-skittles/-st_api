<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
include 'ionicFunctions.inc.php';
$uploadData = $request->uploadData;
if( !empty( $uploadData->project_uid ) && !empty( $uploadData->site_uid ) && !empty( $uploadData->form ) && !empty( $uploadData->user_uid ) )
{
	$success = submitData($uploadData);
} else {
	$success = false;
}
return true;
exit();

?>
