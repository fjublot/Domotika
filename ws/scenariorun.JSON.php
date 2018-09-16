<?php
	header('Content-Type: application/json; charset=utf-8');
	header("Cache-Control: no-cache");
	$start_time = microtime(true);
	$return['debug'] = (string)$GLOBALS["config"]->general->debug;
	$return['jour'] = date("d/m/Y");
	$return['time'] = date("G:i:s");
	if ( isset($GLOBALS["config"]->{"scenarios"}) ) {
		$current = new scenario($numero);
		$return['numero'] = $numero;
		$return['label'] = $current->label;
		
		if ( isset($_SESSION["AuthId"]) && fn_GetAuth($_SESSION["AuthId"], "scenario", $current->numero) ) {
			if ($current->actif == "on") {
				list($status, $message, $error) = $current->run();
			}
			else {
			$error = fn_GetTranslation('objectinactiv', "scenario", $numero);
			$message="";
			}
		}
		else {
			$error = fn_GetTranslation('usernorighton', $_SESSION["AuthId"], "scenario", $numero);
			$message="";
		}
	}
	else {
		$error = fn_GetTranslation('canot_find_class', 'scenario');
		$message="";
	}
	
	$return['message'] = $message;
	$return['error'] = $error;
	$return['code'] = $current->code;
	$current_time = microtime(true);
	$return['debugtime'] = ceil(($current_time - $start_time)*1000)." ms";
	echo json_encode($return);
?>