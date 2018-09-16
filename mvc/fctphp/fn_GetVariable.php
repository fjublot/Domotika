<?php 
	function fn_GetVariable($variable_id) {
		$variable = new variable($variable_id);
		$variable->mysql_load();
		return $variable->valeur;
	}
?>