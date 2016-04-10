<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
include 'ionicFunctions.inc.php';
$uploadData = $request->uploadData;
$email = $uploadData->email;
$status = resetPassword($email);
$results = Array('Status' => $status);
print json_encode($results);
exit();
?>