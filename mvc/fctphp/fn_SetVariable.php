<?php 
	function fn_SetVariable($variable_id, $value, $when=NULL)
	{
		$variable = new variable($variable_id);
		$variable->valeur = $value;
		if ( is_null($when) )
			$variable->update_time = time();
		else
			$variable->update_time = $when;
		$variable->mysql_save();
	}
?>