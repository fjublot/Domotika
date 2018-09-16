<?php
require_once ('LoadConfig.php');
fn_LoadTraduction();
if ( isset($GLOBALS["config_file"]) && isset($_GET["debug"]) )
{
	if ( $_GET["debug"] == "0" || $_GET["debug"] == "1" )
	{
		$doc = new domDocument();
		$doc->preserveWhiteSpace = FALSE;
		$doc->formatOutput = TRUE;
		$doc->load($GLOBALS["config_file"]);
		$xpathdoc= new DOMXPath($doc);
		$xpath = '//general/debug';
		$List_fils = $xpathdoc->query($xpath);
		if ( $List_fils->length == 1 )
		{
			$cible = $List_fils->item(0);
			$taille = strlen($cible->nodeValue);
			$cible->firstChild->deleteData(0,$taille);
			$cible->firstChild->insertData(0, $_GET["debug"]);
		}
		$doc->save($GLOBALS["config_file"]);
		if ( $_GET["debug"] == "0" )
			echo fn_GetTranslation('debug_off');
		else
			echo fn_GetTranslation('debug_on');
	}
}
?>