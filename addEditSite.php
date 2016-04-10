<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$post = $_POST;
$data = $post['data'];
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
        try{
		$sql = "UPDATE
				sites
			SET
				name = :name,
				description = :description,
				lat = :lat,
				lon = :lon,
				updated_at = NOW()
			WHERE
				id = :id";
		
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':name', $data['name']);
		$statement->bindValue(':description', $data['description']);
		$statement->bindValue(':lat', $data['lat']);
		$statement->bindValue(':lon', $data['lon']);
		$statement->bindValue(':id', $data['id']);
		$statement->execute();


		if( !empty( $data->projects ) )
		{
			$projects = explode(",",$projects);
			foreach( $projects as $project )
			{
				$sql = 'INSERT INTO
                                project_x_sites
                        	(
                                	site_uid,
                                	project_uid
                        	) VALUES (
                                	:site_uid,      
                                	:project_uid
                        	)';	
                        $s = $pdo->prepare($sql);
                        $s->bindParam(':site_uid', $data->id);
                        $s->bindParam(':project_uid', $project);
                        $s->execute();
			
			}
		}

        }catch(PDOException $e){
                $error = 'Error counting project_user';
                include 'error.html.php';
                exit();
        }
	
        $results = array();
	$results['success'] = true;
        return print json_encode( $results );
?>

