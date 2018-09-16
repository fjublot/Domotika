<?php
//header('Content-Type: text / json; charset: UTF-8');
header("Cache-Control: no-cache");
fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"xml");
$select="";
if ( fn_AccesMySql() )
{
	$current = new $class($numero);
	// Decalage TZ :
	$decalage_tz = date("Z");
	// Valeur initiale
	$select = "";
	$close = "";
	$close_time = "";
	$group = "";
	if ( $nb_data=="")
		$nb_data = 1000;
	$close .= "numero = ".$current->numero;
	if ( $min != "" && $min != "NaN" )
	{
		$min -= $decalage_tz*1000;
		if ( $min < 0 )
			$min = (time()+$min*1000);
		// Recherche de la date minimal
		$Query = "SELECT time FROM `".$class."` ORDER BY TIME ASC LIMIT 1";
		$rows = $db->runQuery($Query);
		if (!$rows)
			$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
		if (count($rows) == 0)
			$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
		foreach ( $rows as $row ) {
			if ( $min < $row["time"]*1000 )
				$min = $row["time"]*1000;
		}
		$min = (float)$min;
		$min = ceil ($min/1000);
		$close_time .= " AND time > ".$min;
	}
	
	if ( $max != "" && $max != "NaN" )
	{
		$max -= $decalage_tz*1000;
		$max = (float)$max;
		$max = floor(max/1000);
		$close_time .= " AND time < ".$max;
	}
	
	$Query =  "SELECT min(time) AS min FROM `".$class."` where ".$close.$close_time;
	$rows = $db->runQuery($Query);
	if (!$rows)
		$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
	if (count($rows) == 0)
		$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
	foreach ( $rows as $row ) {
		$min_time = $row["min"];
	}

	$Query =  "SELECT max(time) AS max FROM `".$class."` where ".$close.$close_time;
	$rows = $db->runQuery($Query);
	if (!$rows)
		$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
	if (count($rows) == 0)
		$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
	foreach ( $rows as $row ) {
		$max_time = $row["max"];
	}
	unset($Query);
	unset($select);

	$Query = "SELECT count(time) AS nb FROM `".$class."` where ".$close.$close_time;
	$rows = $db->runQuery($Query);
	if (!$rows)
		$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
	if (count($rows) == 0)
		$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
	foreach ( $rows as $row ) {
		if ( $row["nb"] > $nb_data )
		{
		//	echo "/* Nb Data : ".$row["nb"]." */\n";
			if ( $class == "an" )
			{
				$delta = $max_time - $min_time;
				$pas = floor($delta/$nb_data);
				$select = "SELECT time, AVG(etat) AS etat FROM `".$class."` where ";
				$group .= " GROUP BY FLOOR(time/".$pas.")";
			}
		}
	}
	
	if ( !isset($select) )
	{
		$select = "SELECT time, etat AS etat FROM `".$class."` where ";
	}
	$order = " order by `time` asc";
	$select .= $close.$close_time;
	$select .= $group;
	$select .= $order;
	$values = array();
	if ($min!="")
	{
		// Recherche la valeur avant min
		$Query = "SELECT time, etat AS etat FROM `".$class."` where ".$close." AND time <= ".$min." ORDER BY TIME DESC LIMIT 1";
		$rows = $db->runQuery($Query);
		if (!$rows)
			$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
		if (count($rows) == 0)
			$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
		foreach ( $rows as $row ) {
				$time_before = $row["time"];
			$value_before = (float)$row["etat"];
		}
		// Recherche la valeur après min
		$Query = "SELECT time, etat AS etat FROM `".$class."` where ".$close." AND time >= ".$min." ORDER BY TIME DESC LIMIT 1";
		$rows = $db->runQuery($Query);
		if (!$rows)
			$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
		if (count($rows) == 0)
			$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
		foreach ( $rows as $row ) {
			$time_after = $row["time"];
			$value_after = (float)$row["etat"];
		}
		if ( isset ($time_before) && isset($time_after) )
		{
			if ( $time_after != $time_before )
				$value_min = ( $value_before * ($time_after - $min) + $value_after * ($min - $time_before) ) / ($time_after - $time_before);
			else
				$value_min = $value_before;
		}
		elseif ( isset ($time_before) )
			$value_min = $value_before;
		elseif ( isset ($time_after) )
			$value_min = $value_after;
		if ( $as_array != "" )
			$values[$min+$decalage_tz*1000] = (float)$value_min;
		else
			array_push($values, array($min+$decalage_tz*1000, $value_min));
	}
		$rows = $db->runQuery($select);
		if (!$rows)
			$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
		if (count($rows) == 0)
			$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
		foreach ( $rows as $row ) {

		if ($as_array != "")
			$values[($row["time"]+$decalage_tz)*1000] = (float)$row["etat"];
		else
			array_push($values, array(($row["time"]+$decalage_tz)*1000, (float)$row["etat"]));
	}
	if ($max!="" )
	{
		// Recherche la valeur avant max
		$Query = "SELECT time, etat AS etat FROM `".$class."` where ".$close." AND time <= ".$max." ORDER BY TIME DESC LIMIT 1";
		$rows = $db->runQuery($Query);
		if (!$rows)
			$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
		if (count($rows) == 0)
			$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
		foreach ( $rows as $row ) {
			$time_before = $row["time"];
			$value_before = (float)$row["etat"];
		}
		// Recherche la valeur après max
		$Query = "SELECT time, etat AS etat FROM `".$class."` where ".$close." AND time >= ".$max." ORDER BY TIME DESC LIMIT 1";
		$rows = $db->runQuery($Query);
		if (!$rows)
			$html = '<tr><td colspan="8">Impossible d\'exécuter la requête ($Query) dans la base.</td></tr>';
		if (count($rows) == 0)
			$html = '<tr><td colspan="8">'.fn_GetTranslation('no_line_result').'</td></tr>';
		foreach ( $rows as $row ) {
			$time_after = $row["time"];
			$value_after = (float)$row["etat"];
		}
		if ( isset ($time_before) && isset($time_after) )
		{
			if ( $time_after == $time_before )
				$value_max = $value_before;
			else
				$value_max = ( $value_before * ($time_after - $max) + $value_after * ($max - $time_before) ) / ($time_after - $time_before);
		}
		elseif ( isset ($time_before) )
			$value_max = $value_before;
		elseif ( isset ($time_after) )
			$value_max = $value_after;
		if ($as_array!='')
			$values[$max+$decalage_tz*1000] = $value_max;
		else
			array_push($values, array($max+$decalage_tz*1000, $value_max));
	}
//	if (($min!="") && ($max!="") )
//		echo "/* startTime = ".gmstrftime('%Y-%m-%d %H:%M:%S', (int)$min).", endTime =  ".gmstrftime('%Y-%m-%d %H:%M:%S', (int)$max)."*/\n";
//	echo "/*".$select." */\n";
//	echo "/*decalage TZ ".date("Z")." */\n";
	echo $callback."(".json_encode($values).");";
	flush();
}
?>