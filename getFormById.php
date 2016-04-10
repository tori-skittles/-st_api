<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$get = $_GET;
$form_uid = $get['form_uid'];

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		
		$sql = 'SELECT 
				form.id as form_uid,
				form.name as name,
				fields.id as field_uid,
                                fields.type as field_type,
                                fields.value as field_value,
                                fields.icon as field_icon,
                                fields.color as field_color,
                                field_options.id as field_option_uid,
                                field_options.value as field_option_value
			FROM form
			LEFT JOIN form_x_fields ON form_x_fields.form_uid = form.id
                        LEFT JOIN fields ON fields.id = form_x_fields.field_uid
                        LEFT JOIN field_options ON field_options.field_uid = fields.id
                        WHERE 
				form.id = :form_uid';
		$s = $pdo->prepare($sql);
		$s->bindParam(':form_uid', $form_uid);
		$s->execute();
		$rows = $s->fetchAll( PDO::FETCH_ASSOC );	
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error updating project';
		print json_encode($results);
		 exit();
	 }
	$return = array();
	$return['fields'] = array();
	if( !empty( $rows ) )
	{
	
		foreach( $rows as $row)
		{
			$field_uid = $row['field_uid'];
			if( !isset( $return['fields'][$field_uid] ) && !empty($field_uid) )
			{
				$return['fields'][$field_uid] = array();
				$return['fields'][$field_uid]['id'] = $field_uid;
				$return['fields'][$field_uid]['type'] = $row['field_type'];
				$return['fields'][$field_uid]['value'] = $row['field_value'];
				$return['fields'][$field_uid]['icon'] = $row['field_icon'];
				$return['fields'][$field_uid]['color'] = $row['field_color'];
				$return['fields'][$field_uid]['options'] = array();
			}

			if( !empty( $row['field_option_uid'] ) )
			{
				$option = (Object) null;
				$option->id = $row['field_option_uid'];
				$option->value = $row['field_option_value'];
				$return['fields'][$field_uid]['options'][$option->id] = $option;
			}

		}
	
		$data = array();
		$data['id'] = $rows[0]['form_uid'];
                $data['name'] = $rows[0]['name'];
                $data['fields'] = array();
		foreach( $return['fields'] as $r )
		{
			$options = $r['options'];
			$r['options'] = array();
			foreach( $options as $o )
			{
				$r['options'][] = $o;
			}			
			$data['fields'][] = $r;
		}

	}else{
		$data = array();
	}

		$results['data'] = $data;
		$results['success'] = true;
		return print json_encode( $results );
	?>
