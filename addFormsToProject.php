<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$data = $request->data;
$form_uids = $data->form_uids;
$project_uid = $data->project_uid;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
	
	try{

		$sql = "DELETE FROM project_x_forms WHERE project_uid = :project_uid";
		$s = $pdo->prepare($sql);
		$s->bindParam(':project_uid', $project_uid);
                $s->execute();
	}catch(PDOException $e){
                         $results['Status'] = 'Error';
                        $results['Message'] = 'Error updating project';
                        print json_encode($results);
                         exit();
	 }
	foreach( $form_uids as $form )
	{
		try{
		
			$sql = 'INSERT INTO
				project_x_forms
			(
				form_uid,
				project_uid
			) VALUES (
				:form_uid,	
				:project_uid
			)';
			$s = $pdo->prepare($sql);
			$s->bindParam(':form_uid', $form);
			$s->bindParam(':project_uid', $project_uid);
			$s->execute();
	
		}catch(PDOException $e){
			 $results['Status'] = 'Error';
			$results['Message'] = 'Error updating project';
			print json_encode($results);
			 exit();
		 }
	}
	$results['success'] = true;
	return print json_encode( $results );
?>
