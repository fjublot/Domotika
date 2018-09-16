<?php 
	function fn_IpAsLong($a) {
		$d = 0;
		$b = explode(".", $a, 4);
		for ($i = 0; $i < 4; $i++) {
			$d *= 256;
			$d += $b[$i];
		}
		return $d;
	}
?>