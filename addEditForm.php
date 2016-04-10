<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

include 'ionicFunctions.inc.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$data = $request->data;
$data = $data->form;
$name = isset( $data->name ) ? $data->name : 'Un-named';
$desc = isset( $data->description ) ? $data->description : null;
$form_uid = null;
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
	try{

                $sql = "UPDATE 
				form
			SET
				name = :name,
				description = :description,
				updated_at = NOW()
			)";
		$s = $pdo->prepare($sql);
		$s->bindValue(':name', $name);
                $s->bindValue(':description', $desc);
                $s->execute();

	}catch(PDOException $e){
                $error = 'Error counting project_user';
		exit();
        }
	$form_uid = $pdo->lastInsertId();

	if( !empty( $form_uid ) )
	{
		$sql = "DELETE FROM form_x_fields WHERE form_uid = :form_uid";
		$s = $pdo->prepare($sql);
		$s->bindParam(':form_uid', $form_uid);
		$s->execute();

		foreach( $data->fields as $field )
		{
			$field_uid = null;
			try{
		
				$sql = "INSERT INTO fields
					(
						type,
						value
					) VALUES (
						:type,
						:value
					)";	
				$s = $pdo->prepare($sql);
                		$s->bindValue(':type', $field->type);
				$s->bindValue(':value', $field->value);
				$s->execute();
				$field_uid = $pdo->lastInsertId();   

			}catch(PDOException $e){
				$error = 'Error counting project_user';
				exit();
			}	

			try{

                                $sql = "INSERT INTO form_x_fields
					(
						form_uid,
						field_uid
					) VALUES (
						:form_uid,
						:field_uid
					)";
				$s = $pdo->prepare($sql);
				$s->bindValue(':form_uid', $form_uid );
				$s->bindValue(':field_uid', $field_uid );
				$s->execute();
			}catch(PDOException $e){
                                $error = 'Error counting project_user';
                                exit();
                        }	
		
			if( count( $field->options ) > 0 )
			{
				foreach( $field->options as $option )
				{
					try{
						$sql = "INSERT INTO
							field_options
						(
							field_uid,
							value
						) VALUES (
							:field_uid,
							:value
						)";
						$s = $pdo->prepare($sql);
                	                	$s->bindValue(':field_uid', $field_uid);
						$s->bindValue(':value', $option->value);
						$s->execute();
					} catch(PDOException $e){
                         		       $error = 'Error counting project_user';
                                		exit();
                        		}
				}
			}
		}
	}

$results['success'] = true; 

print json_encode($results);
exit();
?>
