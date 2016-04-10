<?php
include 'ionicFunctions.inc.php';
$ext = '.jpg';
$r = strval(rand());
$filename = '../uploads/' . time() . $r . $_SERVER['REMOTE_ADDR'] . $ext;
$link = 'uploads/' . time() . $r . $_SERVER['REMOTE_ADDR'] . $ext;
$imageName = $_POST['imageName'];
$project_id = $_POST['project_id'];
$site_id = $_POST['site_id'];
$user_id = $_POST['user_id'];
$observation_id = $_POST['observation_id'];
if(!is_uploaded_file($_FILES['post']['tmp_name']) || 
	!copy($_FILES['post']['tmp_name'], $filename)){
	 print json_encode($results);
	 exit;
}

$imageID = insertImage($link, $imageName, $project_id, $site_id, $user_id, $observation_id);

$results = $imageID;
print json_encode($results);
exit();
?>