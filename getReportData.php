<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$post = $_POST;
$projects = !empty( $post['projects'] ) ? $post['projects'] : null;
$forms = !empty( $post['forms'] ) ? $post['forms'] : null;
$sites = !empty( $post['sites'] ) ? $post['sites'] : null;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
        try{
	
		$sql = "SELECT
				submissions.id as submission_uid,
				project.id  as project_uid,
				project.name as project_name,
				sites.id as site_uid,
				sites.name as site_name,
				fields.id as field_uid,
				fields.value as field_name,
				form.id as form_uid,
				form.name as form_name,
				submission_x_data.value,
				submissions.created_at
			FROM
				submissions
			LEFT JOIN submission_x_data ON submission_x_data.submission_uid = submissions.id
			LEFT JOIN fields ON submission_x_data.field_uid = fields.id
			LEFT JOIN project ON submissions.project_uid = project.id
			LEFT JOIN sites ON sites.id = submissions.site_uid
			LEFT JOIN form ON form.id = submissions.form_uid
			WHERE fields.id IS NOT NULL";
		
		if( $projects != 'all' && !empty( $projects ) )
		{
			$sql = $sql." AND submissions.project_uid IN( ".$projects.")";
		}

		if( $forms != 'all' && !empty( $forms ) )
		{
			$sql = $sql." AND submissions.form_uid IN( ".$forms.")";
		}

		if( $sites != 'all' && !empty( $sites ) )
		{
			$sql = $sql." AND submissions.site_uid IN( ".$sites.")";
		}
		$statement = $pdo->prepare($sql);
		$statement->execute();

        }catch(PDOException $e){
                $error = 'Error counting project_user';
                exit();
        }

	$rows = $statement->fetchAll( PDO::FETCH_OBJ );

	$data = array();
	foreach( $rows as $row )
	{
		if( !isset( $data[$row->form_uid] ) )
		{
			$data[$row->form_uid] = array();
			$data[$row->form_uid]['id'] = $row->form_uid;
			$data[$row->form_uid]['name'] = $row->form_name;
			$data[$row->form_uid]['fields'] = array();
			$data[$row->form_uid]['data'] = array();
		}
	
		if( !isset( $data[$row->form_uid]['fields'][$row->field_uid] ) )
		{
			$data[$row->form_uid]['fields'][$row->field_uid] = array();	
			$data[$row->form_uid]['fields'][$row->field_uid]['id'] = $row->field_uid;
			$data[$row->form_uid]['fields'][$row->field_uid]['label'] = $row->field_name;
		}
		if( !isset( $data[$row->form_uid]['data'][$row->submission_uid] ) )
		{	
			$data[$row->form_uid]['data'][$row->submission_uid] = array();
		}
		$data[$row->form_uid]['data'][$row->submission_uid][] = $row;
	}
	$return = array();
	foreach( $data as $form )
	{
		$fields = $form['fields'];
		$form['fields'] = array();
		foreach( $fields as $field )
		{
			$form['fields'][] = $field;		
		}
		
		$data = $form['data'];
		$form['data'] = array();
		foreach( $data as $d )
		{
			$form['data'][] = $d;
		}

		$return[] = $form;
	}	

	$results['data'] = $return;
        $results['success'] = true;
        return print json_encode( $results );
?>

