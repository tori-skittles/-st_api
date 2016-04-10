<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$post = json_decode($postdata);

$project_uid = $post->project_uid;
$forms = $post->rm_forms;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	foreach( $forms as $form_uid )
	{
		try{

			$sql = "UPDATE project_x_forms
				SET is_active = 0
				WHERE form_uid = :form_uid
				AND project_uid = :project_uid";

			$statement = $pdo->prepare($sql);
			$statement->bindValue(':form_uid', $form_uid);
			$statement->bindValue(':project_uid', $project_uid);
			$statement->execute();

		}catch(PDOException $e){
			$results['Status'] = 'Error';
			$results['Message'] = 'Error removing user';
			print json_encode($results);
		}
	}
	$results['success'] = true;
	return print json_encode( $results );
?>
