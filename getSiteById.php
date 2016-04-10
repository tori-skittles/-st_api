<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
	try{
		
		$sql = 'SELECT * FROM sites';
		$s = $pdo->prepare($sql);
		$s->execute();
		$rows = $s->fetchAll( PDO::FETCH_OBJ );	
	
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error updating project';
		print json_encode($results);
		 exit();
	 }
	$results['data'] = $rows;
	$results['success'] = true;
	return print json_encode( $results );
?>
