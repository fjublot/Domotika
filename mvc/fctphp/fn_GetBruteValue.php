<?php 
	function fn_GetBruteValue($currentclass, $currentnumero, $when=NULL) {
		// Instanciation de l'item (relai, btn, ctn, ...)
		//$model = fn_GetModel($currentclass, $currentnumero);
		$currentitem = new $currentclass($currentnumero);
			if ( is_null($when) && isset($currentitem->carteid) && $currentitem->carteid != "" ) {
				// Instanciation de la carte
				$model = fn_GetModel("carte", $currentitem->carteid);
				$currentcarte = new $model($currentitem->carteid);
					list($errorcode, $message) = $currentcarte->get_status();
					if ($errorcode > 0) 
						$value = false;
					else
						$currentitem->getxmlvalue($currentcarte->status);
			}
			else { // Recherche en base
				$currentitem->mysql_load($when);
			}
			//if ( property_exists(get_class($currentitem), 'valconv') )
			//	$value = $currentitem->valconv;
			//else
				$value = $currentitem->valeur;
		
			$return = array($errorcode, $value);
			return $return;
	}
?>