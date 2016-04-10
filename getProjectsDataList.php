<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');
$results = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';

	try{
		$sql = 'SELECT 
                                submissions.project_uid as project_uid,
                                project.name as project_name,
                                submissions.id as submission_uid,
                                submissions.site_uid,
                                submissions.form_uid,
                                submissions.user_uid,
                                user.first_name,
                                user.last_name,
                                form.name as form_name,
                                submissions.created_at as submitted_at,
                                sites.name as site_name
                        FROM
                                submissions
                        LEFT JOIN project ON project.id = submissions.project_uid
                        LEFT JOIN sites ON sites.id = submissions.site_uid
                        LEFT JOIN form ON form.id = submissions.form_uid
                        LEFT JOIN user ON user.id = submissions.user_uid
                        WHERE submissions.id IS NOT NULL'; 
		$s = $pdo->prepare($sql);
		$s->execute();
	}catch(PDOException $e){
		 $results['Status'] = 'Error';
		$results['Message'] = 'Error fetching user';
		print json_encode($results);
		 exit();
	 }
	$rows = $s->fetchAll( PDO::FETCH_OBJ );
	$data = array();
	foreach( $rows as $row )
	{
		if( !isset( $data[$row->project_uid] ) )
		{
			$data[$row->project_uid] = array();
			$data[$row->project_uid]['name'] = $row->project_name;
			$data[$row->project_uid]['forms'] = array();
		}
		if( !isset( $data[$row->project_uid]['forms'][$row->form_uid] ) )
		{
			$data[$row->project_uid]['forms'][$row->form_uid] = array();
			$data[$row->project_uid]['forms'][$row->form_uid]['name'] = $row->form_name;	
			$data[$row->project_uid]['forms'][$row->form_uid]['sites'] = array();
		}

		if( !isset( $data[$row->project_uid]['forms'][$row->form_uid]['sites'][$row->site_uid] ) )
		{
			$data[$row->project_uid]['forms'][$row->form_uid]['sites'][$row->site_uid] = array();
			$data[$row->project_uid]['forms'][$row->form_uid]['sites'][$row->site_uid]['id'] = $row->site_uid;
			$data[$row->project_uid]['forms'][$row->form_uid]['sites'][$row->site_uid]['name'] = $row->site_name;
			$data[$row->project_uid]['forms'][$row->form_uid]['sites'][$row->site_uid]['submissions'] = array();
		}
		$d = (Object) null;
		$d->id = $row->submission_uid;
		$d->submitted_at = $row->submitted_at;
		$data[$row->project_uid]['forms'][$row->form_uid]['sites'][$row->site_uid]['submissions'][] = $d;
	}
	$return = array();
	foreach( $data as $project )
	{
		$p = array();
		$p['id'] = $project['id'];
		$p['name'] = $project['name'];
		$p['forms'] = array();
		foreach( $project['forms'] as $form )
		{
			$f = array();
			$f['id'] = $form['id'];
			$f['name'] = $form['name'];
			$f['sites'] = array();	
			foreach( $form['sites'] as $site )
			{	
				$f['sites'][] = $site;
			}
			$p['forms'][] = $f;
		}
		$return[] = $p;
	}
	



	$results['status'] = 'Success';
	$results['data'] = $return;
	return print json_encode( $results );
?>
