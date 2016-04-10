<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$post = $_POST;
$user = isset( $post['user'] ) ? $post['user'] : null;
$id = $user['id'];
$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];
$password =  md5($user->password . 'ionic');
$admin = $user['admin'];
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
	try{
		if( !empty( $id ) )
		{
			$sql = 'UPDATE 
					user
				SET
					first_name = :first_name,
					last_name = :last_name,
					email = :email,
					password = :password,
					super_admin = :super_admin
				WHERE
					id = :id';
			$s = $pdo->prepare($sql);
			$s->bindValue(':first_name', $first_name);
			$s->bindValue(':last_name', $last_name);
			$s->bindValue(':email', $email);
			$s->bindValue(':password', $password);
			$s->bindValue(':super_admin', $admin);
			$s->bindValue(':id', $id);
			$s->execute();
		}else{
			$sql = 'INSERT INTO
					user
				(
					first_name,
					last_name,
					email,
					password,
					super_admin
				) VALUES (
					:first_name,
                                        :last_name,
                                        :email,
					:password,
                                        :super_admin
				)';
			$s = $pdo->prepare($sql);
                        $s->bindValue(':first_name', $first_name);
                        $s->bindValue(':last_name', $last_name);
                        $s->bindValue(':email', $email);
			$s->bindValue(':password', $password);
                        $s->bindValue(':super_admin', $admin);
			$s->execute();
		}
	
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error updating project';
		print json_encode($results);
		 exit();
	 }
	$results['success'] = true;
	return print json_encode( $results );
?>
