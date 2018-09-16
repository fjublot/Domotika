<?php
if ( isset($_REQUEST["asxml"]) )
{
	header('Content-Type: text/xml; charset: UTF-8');
	header("Cache-Control: no-cache");
}
echo "<RadioEmission>";

//$commande=$_REQUEST['radio433'];
$commande=$GLOBALS["class_path"].'/radio433/radioEmission '.$_REQUEST['gpioport'].' '.$_REQUEST['remoteid'].' '.$_REQUEST['numero'].' '.$_REQUEST['value'];
			system($commande);
			system($commande);
echo "<commande>".$commande."</commande>";
echo "</RadioEmission>";
?>