<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$data = $request->data;
$user_uids = $data->user_uids;
$project_uid = $data->project_uid;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
		
	$site_uids = array();
	try{
		$sql = "SELECT site_uid FROM project_x_sites WHERE project_uid = :project_uid";
		$s = $pdo->prepare($sql);
		$s->bindParam(':project_uid', $project_uid);
                $s->execute();

		$site_uids = $s->fetchAll(PDO::FETCH_OBJ);
	}catch(PDOException $e){
		$results['Status'] = 'Error';
                        $results['Message'] = 'Error updating project';
                        print json_encode($results);
                         exit();
	}
	

	foreach( $site_uids as $site )
	{
		foreach( $user_uids as $user_uid )
		{
			try{
			
				$sql = 'INSERT INTO
					site_x_users
				(
					site_uid,
					user_uid
				) VALUES (
					:site_uid,	
					:user_uid
				)';
				$s = $pdo->prepare($sql);
				$s->bindParam(':site_uid', $site->site_uid);
				$s->bindParam(':user_uid', $user_uid);
				$s->execute();
	
			}catch(PDOException $e){
				 $results['Status'] = 'Error';
				$results['Message'] = 'Error updating project';
				print json_encode($results);
				 exit();
			 }
		}
	}
	$results['success'] = true;
	return print json_encode( $results );
?>
