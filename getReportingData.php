<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$return = array();
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		$sql = 'SELECT
				project.id as project_uid,
				project.name as project_name,
				sites.id as sites
			FROM
				project
			LEFT JOIN project_x_sites ON project_x_sites.project_uid = project.id
			LEFT JOIN project_x_forms ON project_x_forms.project_uid = project.id';
		$s = $pdo->prepare($sql);
		$s->execute();
	}catch(PDOException $e){
		$return['Status'] = 'Error';
		$return['Message'] = 'Error fetching user';
		print json_encode($return);
		 exit();
	 }

	$rows = $s->fetchAll( PDO::FETCH_OBJ );	
	$return['status'] = 'Success';
	$return['data'] = $rows;
	return print json_encode( $return );
?>
