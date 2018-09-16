<?php 
	function fn_PurgeTrace($secondes, $type = NULL) { 
		
		global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' ) {
			date_default_timezone_set('UTC');
			$query = "DELETE FROM `trace` WHERE `timeutc` < FROM_UNIXTIME(".(time()-$secondes).");";
			if ( ! is_null($type) ) {
				$query .= " AND `type` = '".$type."'";			
				$message = "Purge traces de type ".$type." anterieures au ".date('d/m/Y H:i:s', (time()-$secondes))." UTC.";
			}
			else {
				$message = "Purge traces anterieures au ".date('d/m/Y H:i:s', (time()-$secondes))." UTC.";
			
			}
			
			$db->runQuery($query);
			fn_Trace($message." (".$query.")", "purge");
			return $message ;
		}
	}
?>