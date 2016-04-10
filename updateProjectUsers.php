<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$project_uid = $request->project_uid;
$add_users = $request->add_users;
$rm_users = $request->rm_users;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		$sql = 'SELECT
				site_uid
			FROM
				project_x_sites
			WHERE project_uid = :project_uid'; 
		$s = $pdo->prepare($sql);
		$s->bindValue(':project_uid', $project_uid);
		$s->execute();
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error fetching user';
		print json_encode($results);
		 exit();
	 }
	$rows = $s->fetchAll(PDO::FETCH_OBJ);
	foreach( $rm_users as $user_uid )
	{	
		foreach( $rows as $row )
		{
			$site_uid = (int) $row->site_uid;
			try{
				$sql = "DELETE FROM
					site_x_users
				WHERE
					site_uid = :site_uid 
				AND
					user_uid = :user_uid";
	
				$statement = $pdo->prepare($sql);
				$statement->bindValue(':site_uid', $site_uid);
				$statement->bindValue(':user_uid', $user_uid);
				$statement->execute();

			}catch(PDOException $e){
				$results['Status'] = 'Error';
	                	$results['Message'] = 'Error removing user';
	                	print json_encode($results);
			}
		}
	}

	$results['success'] = true;
	return print json_encode( $results );
?>
