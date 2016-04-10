<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
include 'ionicFunctions.inc.php';
$uploadData = $request->uploadData;
$first_name = $uploadData->first_name;
$last_name = $uploadData->last_name;
$email = $uploadData->email;
$phone = $uploadData->phone;
emailAccountCreators($first_name, $last_name, $email, $phone);
print json_encode($results);
exit();
?>