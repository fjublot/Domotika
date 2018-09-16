<?php 
	function fn_GetVariableId($label)
	{
		$xpath = '//variables/variable';
		$List_fils = $GLOBALS["config"]->xpath($xpath);
		if ( count($List_fils) != 0 )
		{
			foreach($List_fils as $fils)
			{
				if ( (string)$fils->label == $label )
					return $fils->attributes()->numero;
			}
		}
		return 0;
	}
?>