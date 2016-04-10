<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$project = $request->project;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
	$project->is_active = isset( $project->is_active ) ? $project->is_active : 1;
	try{
		$sql = 'UPDATE
				project
			SET
				name = :name,
				description = :description,
				is_active = :is_active,
				updated_at = NOW()
			WHERE
				id = :project_uid';
		$s = $pdo->prepare($sql);
		$s->bindValue(':project_uid', $project->id);
		$s->bindValue(':name', $project->name);
		$s->bindValue(':description', $project->description);
		$s->bindValue(':is_active', $project->is_active);
		$s->execute();
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error updating project';
		print json_encode($results);
		 exit();
	 }

	$results['success'] = true;
	return print json_encode( $results );
?>
