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

$observations = getUserObservations($userId);
$data = getObservationData($observations);
$images = getObservationImages($observations);
$projects = getUserProjects($userId);
$sites = getProjectSites($projects);
$forms = getProjectForms($projects);
$form_inputs = getFormInputs($forms);
//$dropdowns = getDropdowns($form_inputs);
$results['Status'] = 'Success';
$results['projects'] = $projects;
$results['sites'] = $sites;
$results['forms'] = $forms;
$results['form_inputs'] = $form_inputs;
$results['observations'] = $observations;
$results['data'] = $data;
$results['images'] = $images;
//$results['dropdowns'] = $dropdowns;
print json_encode($results);
exit();
?>