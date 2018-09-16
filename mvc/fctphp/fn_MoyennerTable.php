<?php 
	function fn_MoyennerTable($table, $debut, $fin, $numero = NULL)
	{ global $db;
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			date_default_timezone_set('UTC');
			$l_numero = array();
			if ( is_null($numero) )
			{
				$query = "SELECT DISTINCT numero FROM `".$table."`";
				$rows = $db->runQuery($query);
				foreach ($rows as $row) {
			array_push ($l_numero, $row["numero"]);
				}
			}
			else
				$l_numero = array($numero);
			foreach ($l_numero as $numero )
			{
				fn_Trace("Moyenner ".$table." numero ".$numero." entre ".date('d/m/Y \d\e H:i:s', $debut)." et ".date('H:i:s', $fin).".", "purge");
				// Recherche premiere valeur
				$total_etat = 0;
				$query = "SELECT `time`, `etat` FROM `".$table."` WHERE numero = ".$numero." and `time` < ".$debut." ORDER BY `time` DESC LIMIT 1";
				$rows = $db->runQuery($query);
				foreach($rows as $row)
				{
					$prev_etat = $row["etat"];
					$prev_time = $debut;
				}
				
		  $query = "SELECT `time`, `etat` FROM `".$table."` WHERE numero = ".$numero." and `time` >= ".$debut." AND `time` < ".$fin;
				$rows = $db->runQuery($query);
				if ( count($rows) > 1 )
				{
					// fn_Trace("Plus d'une valeur.", "purge");
					foreach ($rows as $row)
					{
						if ( isset($prev_etat) )
						{
							$total_etat += $prev_etat * ($row["time"] - $prev_time);
						}
						$prev_etat = $row["etat"];
						$prev_time = $row["time"];
					}
					if ( isset($prev_etat) )
					{
						$total_etat += $prev_etat * ($fin - $prev_time);
						fn_Trace("Purge ".$table." numero ".$numero." entre ".date('d/m/Y \d\e H:i:s', $debut)." et ".date('H:i:s', $fin)." (Moyenne ".($total_etat /($fin-$debut) ).").", "purge");
						
						// Suppression des données de la plage
						$query = "DELETE FROM `".$table."` WHERE numero = ".$numero." and `time` >= ".$debut." AND `time` < ".$fin;
					$count = $db->runQuery($query);
		
						$sql = "INSERT INTO `".$table."` VALUES (".$numero.", ".($total_etat /($fin-$debut) ).", ".($debut + ($fin - $debut)/2).")";
					$count = $db->runQuery($query);
					}
				}
			}
		}
	}
?>