<?php
	//include($GLOBALS["root_path"]."class/multilang.php");
  if ( !isset($_SESSION['lang']))
		$_SESSION['lang'] = "fr";
		$libelle = new MultiLang($_SESSION['lang']);
?>
