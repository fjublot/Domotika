<?php 
	function fn_GetValueFunction($type, $numero, $start, $end, $function, $clause = NULL)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' ) {
			$query = "SELECT ".$function."(`etat`) AS etat FROM `".$type."` WHERE ";
			$query .= "`numero` = :numero ";
			if ( is_int($start) )
				$query .= " AND time >= :starttime ";
			if ( is_int($end) )
				$query .= " AND time <= :endtime ";
			if ( !is_null($clause) )
				$query .= " AND ".$clause;
			$query .= " GROUP BY numero";
			$params = array (              
			'numero' => $numero,
			'starttime' => $start,
			'endtime' => $end
			);
			$rows = $db->runQuery($query, $params);
			foreach ($rows as $row) {
				return $row['etat'];
			}
		}
		else
		return false;
	}
?>