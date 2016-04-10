<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$post = $_POST;
$data = $post['project'];
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
        try{
		$sql = "UPDATE
				project
			SET
				name = :name,
				description = :description,
				updated_at = NOW()
			WHERE
				id = :id";
		
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':name', $data['name']);
		$statement->bindValue(':description', $data['description']);
		$statement->bindValue(':id', $data['id']);
		$statement->execute();

        }catch(PDOException $e){
                $error = 'Error counting project_user';
                include 'error.html.php';
                exit();
        }
	
        $results = array();
	$results['success'] = true;
        return print json_encode( $results );
?>

