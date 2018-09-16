<?php
	function fn_LoadTraduction() {
		if ( isset($_SESSION['lang']) && file_exists($GLOBALS["root_path"]."lang/".$_SESSION['lang']."/all.inc") ) {
			require_once($GLOBALS["root_path"]."lang/".$_SESSION['lang']."/all.inc");
		}
		else {
			require_once($GLOBALS["root_path"]."lang/fr/all.inc");
			$_SESSION['lang'] = "fr";
		}
	}
?>