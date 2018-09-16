<?php 
	function fn_TraceOptimize()
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			$query = "OPTIMIZE TABLE `trace`";
	//echo $sql."<br>\n";
			$count = $db->runQuery($query);
		}
	}
?>