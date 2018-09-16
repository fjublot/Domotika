<?php 
	function fn_SetValue($type, $numero, $value, $when=NULL)
	{
		$current = new $type($numero);
		if ( isset($current->carteid) && $current->carteid != "" )
		{
			return false;
		}
		else
		{
			$current->valeur = $value;
			if ( is_null($when) )
				$current->update_time = time();
			else
				$variable->update_time = $when;
			$current->mysql_save();
		}
	}
?>
