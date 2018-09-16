<?php 
	function fn_GetListXmlId($carteid, $class, $jsonorarray="array") {
		$result = array();
		$model=fn_GetModel("carte", $carteid);
		$current_carte = new $model($carteid);
		$xpath = "//modelcartes/modelcarte[@numero='" . $current_carte->model. "']/".$class."s/xmlids/xmlid";
		$xmlids = $GLOBALS["config"]->xpath($xpath);

		foreach($xmlids as $xmlid) {
			$result[] = array(
				'id' => "".$xmlid->attributes()->numero,
				'text' => $xmlid->label." (".$xmlid->attributes()->numero.")"
			);
		}
		
		if ($jsonorarray=="json")
			return json_encode($result);
		else
			return $result;
	}
?>