<?php 
	function fn_GetByXpath($xpath, $type, $balise) {
		//$type = fn ou bal ou attr
		$nodes = $GLOBALS["config"]->xpath($xpath);
		if ($type == "fn")
			return $balise($nodes);
		else { 
			if ($type == "bal") {
				if (count($nodes)== 1)
					return $nodes[0]->{$balise};
				elseif (count($nodes)== 0)
					return "";
				else
					return fn_GetTranslation("multiple");
			}
			else {
				return 'attribut';
			}
		}
	}
?>