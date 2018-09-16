<?php 
	function fn_GetNbValue($type, $id, $start, $end)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			$query = "SELECT `time` AS time FROM `".$type."` WHERE ";
			$query .= "`numero` = :numero ";
			if ( is_int($start) )
				$query .= " AND time >= :starttime ";
			if ( is_int($end) )
				$query .= " AND time <= :endtime ";
		$params = array (              
		'numero' => $id,
		'starttime' => $start,
		'endtime' => $end
		);
			
			$count = $db->runQuery($query, $params,  PDO::ROWCOUNT);
			return $count;
	  }
	  else
		return false;
	}
?>