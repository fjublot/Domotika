<?php
	function fn_SortByNumero($elem1, $elem2) {
		$numero1 = intval($elem1['numero']);
		$numero2 = intval($elem2['numero']);
		if ($numero1 == $numero2) {
			return 0;
		} 
		else {
			return ($numero1 < $numero2) ? -1 : 1;
		}
}
?>