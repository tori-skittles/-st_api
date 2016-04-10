<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$post = $_POST;
$form = $post['form'];
$form_uid = $form['form_uid'];
$fields = $form['fields'];

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
        try{
		if( !empty( $form_uid ) )
		{

			$sql = "UPDATE 
					form
				SET
					name = :name,
					description = :description
				WHERE
					form.id = :form_uid";

			$statement = $pdo->prepare($sql);
			$statement->bindParam(':name', $form['name']);
			$statement->bindParam(':description', $form['description']);
			$statement->bindParam(':form_uid', $form_uid);
			$statement->execute();
		}else{
			$sql = "INSERT INTO
					form
				(
					name,
					description,
					created_at
				) VALUES (
					:name,
                                        :description,
					NOW()
				)";
		
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':name', $form['name']);
                        $statement->bindParam(':description', $form['description']);
                        $statement->bindParam(':form_uid', $form_uid);
			$statement->execute();
			
			$form_uid = $pdo->lastInsertId();	
		}

		//handle fields
		$sql = "DELETE FROM form_x_fields WHERE form_x_fields.form_uid = :form_uid";
		$statement = $pdo->prepare($sql);
		$statement->bindParam(':form_uid', $form_uid);
                $statement->execute();
		
		foreach( $fields as $field )
		{
			if( !empty( $field['id'] ) )
			{
				//update field details
				$sql = "UPDATE
						fields
					SET
						type = :type,
						value = :value
					WHERE
						fields.id = :field_uid";

				$statement = $pdo->prepare($sql);
				$statement->bindParam(':type', $field['type']);
				$statement->bindParam(':name', $field['name'] );
				$statement->bindParam(':field_uid', $field['id'] );
				$statement->execute();

			} else {	
				$sql = "INSERT INTO
						fields
					(
						type,
						value,
						created_at
					) VALUES (		
						:type,
						:value,
						NOW()
					)";
				$statement = $pdo->prepare($sql);		
				$statement->bindParam(':type', $field['type'] );
                                $statement->bindParam(':value', $field['name'] );
				$statement->execute();

				$field['id'] = $pdo->lastInsertId();
			}
			//Insert into form_x_fields
			$sql = "INSERT INTO
					form_x_fields
				(
					form_uid,
					field_uid
				) VALUES (
					:form_uid,
					:field_uid
				)";
			$statement = $pdo->prepare($sql);
			$statement->bindParam(':form_uid', $form_uid);
			$statement->bindParam(':field_uid', $field['id']);

			$statement->execute();
			//Options handle
			if( !empty( $field['options'] ) )
			{
				//delete existing
				$sql = "DELETE FROM field_options WHERE field_uid = :field_uid";
				$statement = $pdo->prepare($sql);
				$statement->bindParam(':field_uid', $field_uid);
                        	$statement->execute();
				
				foreach( $field['options'] as $opt )
				{
					//add in new
					$sql = "INSERT INTO 
						field_options
					(
						field_uid,
						value,
						created_at
					) VALUES (
						:field_uid,
                                                :value,
                                                NOW() 
					)";
					$statement = $pdo->prepare($sql);
					$statement->bindParam(':field_uid', $field['id']);
					$statement->bindParam(':value', $opt );	
					$statement->execute();
				}
			}

		}

        }catch(PDOException $e){
                return $e;
                exit();
        }


        $results['success'] = true;
      	return json_encode($results);
	//  return print json_encode( $results );
?>

