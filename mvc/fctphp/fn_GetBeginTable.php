<?php 
	function fn_GetBeginTable($table)
	{ global $db;
		$query = "SELECT `time` FROM `".$table."` ORDER BY `time` ASC LIMIT 1";
		$rows = $db->runQuery($query);
		if (count($rows)==0)
		 $time = time();
	  else
	  {
		foreach($rows as $row) {
			   $time = $row["time"];
		} 
	  }
		return $time;
	}
?>