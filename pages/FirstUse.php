<?php	
	    include($GLOBALS["page_inc_path"] . 'headloadjs.php');
	if ( !fn_AccesPushMeTo() )
		$error[] = fn_GetTranslation('unable_to_use_because', 'PushMeTo', 'curl');
	
	if ( ! fn_AccesMySql() )
		$error[] = fn_GetTranslation('unable_to_use_because', 'Mysql', 'mysql');
	
	if (fn_AccesMySql() && isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' ) {
		$phpfile = fn_FindExec("php");
		if ( $phpfile == false )
			$phpfile = "{path}" . DIRECTORY_SEPARATOR . "php";
		$msg[] = fn_GetTranslation('must_planif_commande').".<br>\n";
		$msg[] = "(cd ".dirname($_SERVER["SCRIPT_FILENAME"]).";".$phpfile." cron.php)<br>\n";
		$msg[] = "wget ".$GLOBALS["config"]->general->url."cron.php<br>\n";
	}
?>