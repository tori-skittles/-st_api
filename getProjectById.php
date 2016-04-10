<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$userId = $request->userId;
include 'ionicFunctions.inc.php';

$projects = getProjects($userId);

$results['projects'] = $projects;

print json_encode($results);
exit();
?>

