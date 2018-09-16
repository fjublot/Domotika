<?php
/*----------------------------------------------------------------*
* Titre : CreateGraph.php                                         *
* Programme permettant de maintenir les objets                    *
*----------------------------------------------------------------*/
header('Content-Type: text/xml; charset: UTF-8');
header("Cache-Control: no-cache");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<root>';
if ( isset($_REQUEST["class"]) && isset($_REQUEST["numero"]) )
{
	$current = new $_REQUEST["class"]($_REQUEST["numero"]);
	$current_graphique = new Graphique("");
	$current_graphique->label = 'Graphique de '.$current->label;
	$current_graphique->title = 'Graphique de '.$current->label;
	$current_graphique->subtitle = 'Généré automatiquement';
	$current_graphique->height = 100;
	$current_graphique->width = 800;
	$current_graphique->export = true;
	if ( in_array ($_REQUEST["class"], array("relai", "btn")) )
	{
		$current_graphique->mode = "Chart";
		$current_graphique->type = "line";
	}
	else
	{
		$current_graphique->mode = "StockChart";
		$current_graphique->type = "spline";
	}
	$current_graphique->public = true;
	$current_graphique->data = array($_REQUEST["class"]."_".$_REQUEST["numero"]);
	echo '<message>'.htmlspecialchars_decode ($current_graphique->save()).'</message>';
}
echo "</root>";
?>