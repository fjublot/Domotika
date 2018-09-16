<?php
	function fn_CheckSyntax($code) {
		return @eval('return true;' . $code);
	}
?>