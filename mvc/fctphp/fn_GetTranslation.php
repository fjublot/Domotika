<?php
	function fn_GetTranslation($id)	{
		if ( ! isset($_SESSION['lang']))
			$_SESSION['lang']="fr";
		if ( ! isset($GLOBALS["traduction"][$id]) ) {
			if ( $id == 'no_translation' )
				return "Erreur de traduction";
			else {
				//fn_Trace("Erreur de traduction '".$id."' en ".$_SESSION['lang']." dans ".$_SERVER['REQUEST_URI'], "trad");
				return fn_GetTranslation('no_translation', $id, $_SESSION['lang']);
			}
		}
		$traduction = $GLOBALS["traduction"][$id];
		for ($i = 1; $i < func_num_args(); $i++) {
			$arg_value = func_get_arg($i);
			$traduction = str_replace("%".$i, $arg_value, $traduction);
		}
	  return $traduction;
	}
?>