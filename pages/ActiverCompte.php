<?php
$Erreur = true;
// Vérifie que de bonnes valeurs sont passées en paramètres
if (!preg_match("/^[0-9]+$/", $_REQUEST["numero"]) || !preg_match("/^[a-f0-9]{8}$/", strtolower($_REQUEST['clef'])))
{
	header("Location: index.php");
}
else
{
	$current = new User($_REQUEST["numero"]);
	if ($current->clefactivation == strtolower($_REQUEST['clef']))
	{
		if ($current->actif != 0)
		{
			$message = fn_GetTranslation('compte_deja_actif');
		}
		else
		{
			$current->actif = 1;
			$current->save();
			$message = fn_GetTranslation('compte_active');
		}
	}
	else
	{
		$message = fn_GetTranslation('erreur_activation_compte');
	}
}
header ("Refresh: 2;URL=index.php");
Include($GLOBALS["page_inc_path"] . "head.php");
Include($GLOBALS["page_inc_path"] . "topbar.php");
if ($Erreur)
{
	echo '<div id="Erreur">' . $message . '</div>';
}
else
{
	echo '<div id="Popup">' . $message . '</div>';
}
include($GLOBALS["root_path"] . 'Foot.php');