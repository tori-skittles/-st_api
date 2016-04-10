<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$get = $_GET;
$userId = $get['user_uid'];

include 'ionicFunctions.inc.php';
$isAdmin = true;
$projects = getProjects($userId, $isAdmin);
$role = getUserRole($userId);
$results['data'] = $projects;
$results['role'] = $role;

print json_encode($results);
exit();
?>

