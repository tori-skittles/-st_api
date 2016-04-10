<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$get = $_GET;

$project_uid = $get['project_uid'];
$site_uid = $get['site_uid'];
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{    
		$sql = 'SELECT 
				image_id, 
				link, 
				image_name as name, 
				observation_id,
				user.first_name,
				user.last_name,
				image_time_created as created_at 
			FROM 
				image 
			LEFT JOIN user ON user.id = image.user_id
			WHERE 
				project_id = :project_id 
			AND 
				site_id = :site_id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':project_id', $project_uid);
		$s->bindValue(':site_id', $site_uid);
		$s->execute();
		
		$rows = $s->fetchAll(PDO::FETCH_OBJ);
	}catch(PDOException $e){
		$error = 'Error fetching image' . $e->getMessage();
		include 'error.html.php';
		exit();
	}

	
	$results['status'] = 'Success';
	$results['data'] = $rows;
	return print json_encode( $results );
?>
