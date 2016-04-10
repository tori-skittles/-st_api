<?php
header('Content-type: text/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, x-prototype-version, x-requested-with');

$results = array();
$get = $_GET;
$user_uid = $get['user_uid'];
$isAdmin = !empty( $get['admin'] ) ? $get['admin'] : false;
include $_SERVER['DOCUMENT_ROOT'] . '/includes/dbTest.inc.php';
        try{
		if( $isAdmin === true || $isAdmin === "true")
		{	
		$sql = "SELECT
				project.id as project_uid,
				project.name as project_name,
				project.description as project_description,
				project.created_at as project_created_at,
				sites.id as site_uid,
				sites.name as site_name,
				sites.lat,
				sites.lon,
				sites.description as site_description,
				sites.created_at as site_created_at,
				form.id as form_uid,
				form.name as form_name,
				form.description as form_description,
				form.created_at as form_created_at
			FROM
				project
			LEFT JOIN project_x_sites ON project_x_sites.project_uid = project.id AND project_x_sites.is_active = 1
			LEFT JOIN sites ON sites.id = project_x_sites.site_uid
			LEFT JOIN site_x_users ON site_x_users.site_uid = sites.id
			LEFT JOIN project_x_forms ON project_x_forms.project_uid = project.id AND project_x_forms.is_active = 1
			LEFT JOIN form ON form.id = project_x_forms.form_uid
			WHERE project.is_active = 1";
		
		$statement = $pdo->prepare($sql);
		$statement->execute();
		}else{
			  $sql = "SELECT
                                project.id as project_uid,
                                project.name as project_name,
                                project.description as project_description,
                                project.created_at as project_created_at,
                                sites.id as site_uid,
                                sites.name as site_name,
                                sites.lat,
                                sites.lon,
                                sites.description as site_description,
                                sites.created_at as site_created_at,
                                form.id as form_uid,
                                form.name as form_name,
                                form.description as form_description,
                                form.created_at as form_created_at
                        FROM
                                project
                        LEFT JOIN project_x_sites ON project_x_sites.project_uid = project.id AND project_x_sites.is_active = 1
                        LEFT JOIN sites ON sites.id = project_x_sites.site_uid
                        LEFT JOIN site_x_users ON site_x_users.site_uid = sites.id
                        LEFT JOIN project_x_forms ON project_x_forms.project_uid = project.id AND project_x_forms.is_active = 1
                        LEFT JOIN form ON form.id = project_x_forms.form_uid
                        WHERE project.is_active = 1
			AND site_x_users.user_uid = :user_uid";
			$statement = $pdo->prepare($sql);
                	$statement->bindValue(':user_uid', $user_uid);
			$statement->execute();
		
		}


        }catch(PDOException $e){
                $error = 'Error counting project_user';
                include 'error.html.php';
                exit();
        }

        $rows = $statement->fetchAll( );

	$data = array();
	foreach( $rows as $row )
	{
		$project_uid = $row['project_uid'];
		$site_uid = $row['site_uid'];
		$form_uid = $row['form_uid'];

		if( !isset( $data[$project_uid] ) )
		{
			$data[$project_uid] = array();
			$data[$project_uid]['name'] = $row['project_name'];
			$data[$project_uid]['id'] = $project_uid;
			$data[$project_uid]['description'] = $row['project_description'];
			$data[$project_uid]['created_at'] = $row['project_created_at'];
			$data[$project_uid]['sites'] = array();	
			$data[$project_uid]['forms'] = array();
		}

		if( !isset( $data[$project_uid]['sites'][$site_uid] ) && !empty( $site_uid) )
		{
			$data[$project_uid]['sites'][$site_uid] = array();
			$data[$project_uid]['sites'][$site_uid]['id'] = $site_uid;
			$data[$project_uid]['sites'][$site_uid]['name'] = $row['site_name'];
			$data[$project_uid]['sites'][$site_uid]['lat'] = $row['lat'];
			$data[$project_uid]['sites'][$site_uid]['lon'] = $row['lon'];
			$data[$project_uid]['sites'][$site_uid]['description'] = $row['site_description'];
			$data[$project_uid]['sites'][$site_uid]['created_at'] = $row['site_created_at'];
		}

		if( !isset( $data[$project_uid]['forms'][$form_uid] ) && !empty($form_uid) )
		{
			$data[$project_uid]['forms'][$form_uid] = array();
			$data[$project_uid]['forms'][$form_uid]['id'] = $form_uid;
			$data[$project_uid]['forms'][$form_uid]['fields'] = array();
			$data[$project_uid]['forms'][$form_uid]['name'] = $row['form_name'];
			$data[$project_uid]['forms'][$form_uid]['description'] = $row['form_description'];
			$data[$project_uid]['forms'][$form_uid]['created_at'] = $row['form_created_at'];
		}
	
	}
	$return = array();
	foreach( $data as $project)
	{
		$proj = $project;
		$sites = $project['sites'];
		$forms = $project['forms'];
		$proj['sites'] = array();
		$proj['forms'] = array();
		foreach( $sites as $site )
		{
			$proj['sites'][] = $site;		
		}
		foreach( $forms as $form )
		{
			$proj['forms'][] = $form;
		}
		$return[] = $proj;
	}

	$results['data'] = $return;
        $results['success'] = true;
        return print json_encode( $results );
?>

