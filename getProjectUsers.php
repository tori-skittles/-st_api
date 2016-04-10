<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$project_uid = $request->project_uid;
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		$sql = 'SELECT 
				user.id as user_uid, 
				first_name, 
				last_name,
				super_admin
			FROM 
				user 
			LEFT JOIN site_x_users ON site_x_users.user_uid = user.id
			LEFT JOIN project_x_sites ON project_x_sites.site_uid = site_x_users.site_uid
			LEFT JOIN project ON project.id = project_x_sites.project_uid
			WHERE project.id = :project_uid
			GROUP BY user.id';
		$s = $pdo->prepare($sql);
		$s->bindValue(':project_uid', $project_uid);
		$s->execute();
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error fetching user';
		print json_encode($results);
		 exit();
	 }
	$rows = $s->fetchAll();
	
	$results['status'] = 'Success';
	$results['data'] = $rows;
	return print json_encode( $results );
?>
