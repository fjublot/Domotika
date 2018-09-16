<?php
	header('Content-Type: application/json; charset=utf-8');
	if ( !fn_AccesPushMeTo() )
		$result['messages'][] = array('message' => ucfirst(fn_GetTranslation('unable_to_use_because', 'PushMeTo', 'curl')));
	if ( ! fn_AccesMySql() )
		$result['messages'][] = array('message' => ucfirst(fn_GetTranslation('unable_to_use_because', 'Mysql', 'mysql')));
	if (isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' ) {
		$phpfile = fn_FindExec("php");
		if ( $phpfile == false )
			$phpfile = '{path}' . DIRECTORY_SEPARATOR . 'php';
		$result['messages'][] = array('message' => ucfirst(fn_GetTranslation('must_planif_commande')));
		$result['messages'][] = array('message' => 'cd '. dirname($_SERVER["SCRIPT_FILENAME"]).';'.$phpfile.' cron.php');
		$result['messages'][] = array('message' => fn_Gettranslation('or'));
		$result['messages'][] = array('message' => 'wget '. $GLOBALS["config"]->general->url .'cron.php');
	}

	echo json_encode($result);

?>