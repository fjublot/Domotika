<?php
require_once ('LoadConfig.php');
require_once("class/".$_REQUEST["class"].".php");
$out = '<div id="'.$_REQUEST["class"].'s">';
$disp = false;
$carte = array();
if ( isset($GLOBALS["config"]->{$_REQUEST["class"]."s"}) )
{
	if (($_REQUEST["class"]=='cnt') || ($_REQUEST["class"]=='an') || ($_REQUEST["class"]=='variable')  || ($_REQUEST["class"]=='vartxt'))
	{
		if ( $_SESSION['Debit'] > 0 )
			{
       $out .= '<div class="Container'.$_REQUEST["class"].'" >';
       $out .= '<img class="Image'.$_REQUEST["class"].'" src="./images/Picto'.$_REQUEST["class"].'.png" alt="icone" />';
       $out .= '<div class="Caption'.$_REQUEST["class"].'" style="display:none;"></div>';
       $out .= '</div>';
      }
	}
	foreach($GLOBALS["config"]->{$_REQUEST["class"]."s"}->{$_REQUEST["class"]} as $info)
	{
		if ( isset($_REQUEST["filtre_numero"]) )
		{
			if ( is_array($_REQUEST["filtre_numero"]) )
			{
				$find = false;
				foreach($_REQUEST["filtre_numero"] as $filtre_numero)
				{
					if ( preg_match("/".$filtre_numero."/", $info->attributes()->numero) )
						$find = true;
				}
				if ( ! $find )
					continue;
			}
			elseif ( ! preg_match("/".$_REQUEST["filtre_numero"]."/", $info->attributes()->numero) )
				continue;
		}
	   $current = new $_REQUEST["class"]($info->attributes()->numero, $info);
		if ( fn_GetAuth($_SESSION["AuthId"], $_REQUEST["class"], $current->numero) )
		{
			if ( isset($current->carteid) && $current->carteid != "" )
			{
				//Charge les données de la carte
				if ( ! array_key_exists($current->carteid, $carte) )
				{
					require_once("class/carte.php");
					$model = fn_GetModel("carte", $current->carteid);    
					$carte[$current->carteid] = new $model($current->carteid);
					$carte[$current->carteid]->get_status();
				}
				$current->getxmlvalue($carte[$current->carteid]->status);
			}
			$out .= $current->disp();
			$disp = true;
		}
	}
 	if (($_REQUEST["class"]=='cnt') || ($_REQUEST["class"]=='an') || ($_REQUEST["class"]=='variable')  || ($_REQUEST["class"]=='vartxt'))
	{
		$out .= '</div>';
	}
}
$out .= '<hr /></div>';
if ( $disp )
	echo $out;
?>