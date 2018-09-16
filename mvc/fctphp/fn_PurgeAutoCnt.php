<?php 
	function fn_PurgeAutoCnt()
	{
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
		{
			date_default_timezone_set('UTC');
			$table = "cnt";
			//Moyenne 10 minutes
			for($m=-12; $m < -2; $m++)
			{
				$init_time = mktime (0, ($m*10), 0);
				fn_MoyennerTable($table, $init_time, $init_time+600);
			}
			//Moyenne horraire
			for($heure=-2; $heure < -25; $heure--)
			{
				$init_time = mktime ($heure, 0, 0);
				fn_MoyennerTable($table, $init_time, $init_time+3600);
			}
			//Moyenne toutes les 6 heures
			$begin_time = fn_GetBeginTable($table);
			$init_time = mktime (0, 0, 0, date("n"), -1);
			while ( $init_time > $begin_time )
			{
				for($periode=3; $periode < 24; $periode = $periode + 6)
				{
					fn_MoyennerTable($table, $init_time+($periode)*3600, $init_time+($periode+6)*3600);
				}
				$init_time -= 24 * 3600;
			}
			$sql = "OPTIMIZE TABLE `".$table."`";
	//echo $sql."<br>\n";
			mysql_query($sql) or die ('Erreur : '.mysql_error()." -> ".$sql );
		}
	}
?>