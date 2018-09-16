<?php 
	function fn_PurgeTable($table, $secondes, $numero = NULL)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			date_default_timezone_set('UTC');
			if ( ! is_null($numero) )
				fn_Trace("Purge ".$table." numero ".$numero." anterieur au ".date('d/m/Y H:i:s', (time()-$secondes)).".", "purge");
			else
				fn_Trace("Purge ".$table." anterieur au ".date('d/m/Y H:i:s', (time()-$secondes)).".", "purge");
			$query = "DELETE FROM `".$table."` WHERE `time` < ".(time()-$secondes);
			if ( ! is_null($numero) )
				$query .= " AND numero = ".$numero;
			$count = $db->runQuery($query);
			$query = "OPTIMIZE TABLE `".$table."`";
			//echo $sql."<br>\n";
			$count = $db->runQuery($query);
		}
	}
?>