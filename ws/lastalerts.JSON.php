<?php
	header('Content-Type: application/json; charset=utf-8');
	global $db;
	if ($nbalerts=="")
		$alerts = $db->getLastAlerts();
	else
		$alerts = $db->getLastAlerts($nbalerts);
	if (count($alerts)>0) {
		foreach ($alerts as $alert) {
			$model = fn_GetModel("carte", $alert['carte_id']);
			$currentcarte = new $model($alert['carte_id']);
			$alertjson['carteid'] 	= $alert['carte_id'];
			$alertjson['model'] 	= $currentcarte->model;
			$alertjson['label'] 	= $currentcarte->label;
			$alertjson['class'] 	= $alert['class'];
			$alertjson['no'] 		= $alert['no'];		
			$alertjson['localtime'] 	= $alert['localtime'];
			$alertjson['timezone'] 	= $alert['timezone'];
			$alertjson['message']	= $alert['message'];
			$results[] = $alertjson;
		}
		echo json_encode($results);
	}
	else
		$results[]=array("message" => false);

?>
