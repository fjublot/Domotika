<?php 
	function fn_SetCnt($currentnumero, $value) {
		$cnt = new cnt($currentnumero);
		$cnt->setvalue($value);
	}
?>