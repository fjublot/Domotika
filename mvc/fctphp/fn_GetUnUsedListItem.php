<?php 
	function fn_GetUnUsedListItem($class, $carteid="", $jsonorarray="array", $xmlid=false, $vide=false, $addone2idx=false) {
		$result = array();
		if ($vide==true) {
			$result[] = array(
				'id' => '#N/A',
				'text' => (string)fn_GetTranslation('none')
			);
		}
		
		$model=fn_GetModel("carte", $carteid);
		$current_carte = new $model($carteid);
		$xpath = "//modelcartes/modelcarte[@numero='" . $current_carte->model. "']";
		$modelcarte = $GLOBALS["config"]->xpath($xpath);
		
		switch ($class) {
			case "relai":
				$nb = $modelcarte[0]->relais->max;
			break;
			case "btn":
				$nb = $modelcarte[0]->btns->max;
			break;
			case "cnt":
				$nb = $modelcarte[0]->cnts->max;
			break;
			case "an":
				$nb = $modelcarte[0]->ans->max;
			break;
		}
		$numero=1;		
		// Seulement ceux qui ne sont pas déja existants
		$BorneMaxi=1;
		
		if (property_exists($model, 'nb_extension'))
			$BorneMaxi = $current_carte->nb_extension+1;
			
		if ($class=="cnt" && $model=="carteecodev") {
			$xpath = "//modelcarte[@numero='ecodev']/cnts/xml_ids/xml_id";
			$xmlids = $GLOBALS["config"]->xpath($xpath);
			$disabled=false;
			foreach ($xmlids as $xmlid) {
				$result[] = array(
					'id' => (string)$xmlid->attributes()->numero,
					'text' => (string)$xmlid->label,
					'disabled' => $disabled
				);
			
			}
		}
		else {
			while ($numero <= (($BorneMaxi) * $nb)) { // Boucle sur tout les items pilotés par la carte 
				$xpath = "//" . $class . "[carteid='" . $carteid. "' and no='" . ($numero-1) . "']";
				$label=fn_GetByXpath($xpath, 'bal', 'label');
				$disabled=false;
				if ($addone2idx==true)
					$id=$numero;
				else
					$id=$numero-1;
	
				$result[] = array(
					'id' => $id,
					'text' => $numero." - ".$label,
					'disabled' => $disabled
				);
				$numero=$numero+1;
			}
		}
	
		if ($jsonorarray=="json")
			return json_encode($result);
		else
			return $result;
	}
?>