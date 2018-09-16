<?php 
	function fn_SetBdd($class, $numero, $value, $when=NULL)
	{
		$element = new $class($numero);
		$element->valeur = $value;
		if ( is_null($when) )
			$element->update_time = time();
		else
			$element->update_time = $when;
		$element->mysql_save();
	}
?>