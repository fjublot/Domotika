<?php 
	function fn_PurgeAutoTrace() {
		fn_PurgeTrace(3600, 'M2M');
		fn_PurgeTrace(86400, 'trad');
		fn_PurgeTrace(86400, 'purge');
		fn_PurgeTrace(86400, 'xml');
		fn_PurgeTrace(7*86400, 'carte');
		fn_PurgeTrace(31*86400, 'cron');
		fn_PurgeTrace(31*86400, 'push');
		fn_PurgeTrace(31*86400, 'scenario');
		fn_PurgeTrace(31*86400, 'asynch');
		fn_PurgeTrace(31*86400, 'ihm');
		fn_PurgeTrace(90*86400, 'install');
		fn_PurgeTrace(31*86400, 'acces');
		fn_PurgeTrace(90*86400, 'version');
	}
?>