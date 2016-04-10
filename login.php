<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$email = $request->emailLogin;
$pass = $request->passLogin;
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		$sql = 'SELECT id, first_name, last_name, email, phone, password, super_admin FROM user WHERE email = :email';
		$s = $pdo->prepare($sql);
		$s->bindValue(':email', $email);
		$s->execute();
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error fetching user';
		print json_encode($results);
		 exit();
	 }
	$row = $s->fetch();
	$results['loggedIn'] = FALSE;
	$results['userId'] = $row['id'];
	$results['firstName'] = $row['first_name'];
	$results['lastName'] = $row['last_name'];
	$results['email'] = $row['email'];
	$results['phone'] = $row['phone'];
	$results['password'] = $row['password'];
	$results['admin'] = $row['super_admin'];

	if($results['password'] == ''){
		 $password= md5($pass . 'ionic');
		var_dump($pass);
                var_dump($password);
		$results['Message'] = 'Please Set Your Password!!!';
		$results['loggedIn'] = TRUE;
	}else{
		$password= md5($pass . 'ionic');
//		var_dump($pass);
//		var_dump($password);
		try{
			$sql = 'SELECT COUNT(*) FROM user WHERE email = :email AND password = :password';
			$s = $pdo->prepare($sql);
			$s->bindValue(':email', $results['email']);
			$s->bindValue(':password', $password);
			$s->execute();
		}catch(PDOException $e){
			 $results['Status'] = 'Error';
			 $results['Message'] = 'Error checking password for user';
			$results['password'] = '';
			print json_encode($results);
			exit();
		}
		$row = $s->fetch();
		if($row[0] > 0){
			$results['loggedIn'] = TRUE;
		}else{
			$results['Status'] = 'Error';
			$results['Message'] = 'Email and Password did not match';
			$results['password'] = '';
			print json_encode($results);
			exit();
		}
	}
	$results['Status'] = 'Success';
	$results['password'] = '';
	print json_encode($results);
	exit();
?>
