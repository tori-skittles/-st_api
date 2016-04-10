<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$data = $request->uploadData;
$email = $data->email;
$password = md5($data->password . 'ionic');
$first_name = $data->first_name;
$last_name = $data->last_name;
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
	
	try{
		
		$sql = 'SELECT * FROM user WHERE email = :email';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->execute();
		$rows = $s->fetchAll( PDO::FETCH_OBJ );	
	
		if( count( $rows ) > 0 )
		{
			$results['user_status'] = 'email_exists';
		}else{
			$sql = 'INSERT INTO user 
				(
					email,
					password,
					first_name,
					last_name	
				)VALUES(
					:email,
                                        :password,
                                        :first_name,
                                        :last_name 
				)';
			$s = $pdo->prepare($sql);
			$s->bindValue(':email', $email);
			$s->bindValue(':first_name', $first_name);
			$s->bindValue(':last_name', $last_name);
                        $s->bindValue(':password', $password);
			$s->execute();
			$results['user_status'] = 'registered';
		}

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
