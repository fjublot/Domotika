<?php 
	function fn_GetNextTime($type, $numero, $when=NULL)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			$query  = "SELECT `time` FROM `".get_class($current)."` ";
		$query .= "WHERE `numero` = ".$numero;
			if ( is_int($when) )
				$query .= " AND time >= ".$when;
			$query .= " ORDER BY time ASC LIMIT 1";
			$rows = $db->runQuery($query);
			foreach ($rows as $row) {
			  return $row["time"];
		}
		}
	  else
		return false;
	}
?>
