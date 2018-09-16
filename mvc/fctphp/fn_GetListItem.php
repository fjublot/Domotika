<?php 
	function fn_GetListItem($listclasses, $filtre_numero="", $filtre_carteid="", $filtre_type="", $prefix="", $jsonorarray="array", $vide=false) {
		$result = array();
		if ($vide==true) {
			$result[] = array(
				'id' => '#N/A',
				'text' => (string)fn_GetTranslation('none')
			);
		}

		$classes = explode("|", $listclasses);
		if ( isset($GLOBALS["config"]) ) {
			foreach($classes as $class) {
				if ( isset($GLOBALS["config"]->{$class."s"}) ) {
					foreach($GLOBALS["config"]->{$class."s"}->{$class} as $info) {
						if (( $filtre_numero!="") && ! preg_match("/".$filtre_numero."/", $info->attributes()->numero) )
							continue;
						if (( $filtre_carteid!="") && ( ! isset($info->carteid) || $filtre_carteid != $info->carteid ) )
							continue;
						if (( $filtre_type!="") && $filtre_type != $info->type )
							continue;
						$numero = (string)$info->attributes()->numero;
						if (( $prefix!="") && $prefix == true )
							$numero = $class."_".$numero;
						if ($class=="pushto") {
							$result[] = array(
								'id' => $numero,
								'text' => $info->label." (".$info->type.")"
							);
						}
						elseif ($class=="carte") {
							$result[] = array(
								'id' => $numero,
								'text' => $info->label." (".$info->model.")"
							);
						}

						else {
							$result[] = array(
								'id' => $numero,
								'text' => (string)$info->label
							);
						}
					}
				}
			}
	    }
		if ($jsonorarray=="json")
			return json_encode($result);
		else
			return $result;
	}
?>