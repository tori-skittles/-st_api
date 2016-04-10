<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

try{
	$sql = "SELECT COUNT(*) FROM site";
	$s = $pdo->prepare($sql);
	$s->execute();
}catch(PDOException $e){
	$error = 'Error fetching sites';
	include 'error.html.php';
	exit();
}

$row = $s->fetch();
if($row[0] > 0){
	try{
		$sql = "SELECT site_id, site_name, site_description, site_lat,
			site_lon, project_id FROM site";
		$s = $pdo->prepare($sql);
		$s->execute();
	}catch(PDOException $e){
		$error = 'Error fetching sites';
		include 'error.html.php';
		exit();
	}
	foreach($s as $row){
	$sites[] = array('site_id' => $row['site_id'],'site_name' => $row['site_name'],
	'site_description' => $row['site_description'],'site_lat' => $row['site_lat'],
	'site_lon' => $row['site_lon'],'project_id' => $row['project_id']);
	}
}else{
	$sites[] = array('site_id' => 0,'site_name' => 'None',
	'site_description' => 'None','site_lat' => 0,
	'site_lon' => 0,'project_id' => 0);
}

try{
	$sql = "SELECT COUNT(*) FROM project";
	$s = $pdo->prepare($sql);
	$s->execute();
}catch(PDOException $e){
	$error = 'Error fetching sites';
	include 'error.html.php';
	exit();
}

$row = $s->fetch();
if($row[0] > 0){
	try{
		$sql = "SELECT project_id, project_name FROM project";
		$s = $pdo->prepare($sql);
		$s->execute();
	}catch(PDOException $e){
		$error = 'Error fetching sites';
		include 'error.html.php';
		exit();
	}
	foreach($s as $row){
	$projects[] = array('project_id' => $row['project_id'],'project_name' => $row['project_name']);
	}
}else{
	$projects[] = array('project_id' => 0,'project_name' => 'none');
}

foreach($sites as $site){
	foreach($projects as $project){
		if($site['project_id'] == $project['project_id']){
			$rsites[] = array_merge($site, array('project_name' => $project['project_name']));
		}
	}
}		

$results['Status'] = 'Success';
$results['sites'] = $rsites;
print json_encode($results);
exit();
?>