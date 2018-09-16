<?php
	function fn_GetTime($type, $id, $start, $end, $function, $clause = NULL)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			$select = "SELECT ".$function."(`time`) AS time FROM `".$type."` WHERE ";
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
			if ( !is_null($clause) )
				$select .= " AND ".$clause;
			$select .= " GROUP BY numero";
			$rows = $db->runQuery($query, $params,  PDO::FETCH);
			foreach ($rows as $row) {
			  return $row["time"];
		}
		}
	  else
		return false;
	}
?>