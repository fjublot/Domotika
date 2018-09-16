<?php 
	function fn_GetPrevTime($type, $numero, $when=NULL)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			$query = "SELECT `time` FROM `".get_class($current)."` WHERE `numero` = ".$numero;
			if ( is_int($when) )
				$query .= " AND time <= ".$when;
			$query .= " ORDER BY time DESC LIMIT 1";
			$rows = $db->runQuery($query);
			foreach ($rows as $row) {
			  return $row["time"];
		  }
	  }
		return false;
	}
?>