<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$sites = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_uid = $request->user_uid;
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		$sql = "SELECT
				sites.id,
				sites.name,
				sites.description,
				sites.lat,
				sites.lon,
				project_x_sites.project_uid,
				project.name
			FROM
				site_x_users
			LEFT JOIN sites ON sites.id = site_x_users.site_uid
			LEFT JOIN project_x_sites ON project_x_sites.site_uid = site_x_users.site_uid
			LEFT JOIN project ON project.id = project_x_sites.project_uid
			WHERE
				site_x_users.user_uid = :user_uid
			AND sites.id IS NOT NULL
			GROUP BY project_x_sites.id";	
		
		$s = $pdo->prepare($sql);
		$s->bindParam(':user_uid', $user_uid);
		$s->execute();
		$sites = $s->fetchAll(PDO::FETCH_OBJ);

	}catch(PDOException $e){
		$error = 'Error fetching sites';
		exit();
	}

$results['Status'] = 'Success';
$results['data'] = $sites;
print json_encode($results);
exit();
?>
