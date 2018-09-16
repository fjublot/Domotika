<?php

if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' ) {
	if ( isset($DispInfo) )
		echo fn_GetTranslation('update_database');
	$nb_error_avant = count($error);
	require("install_mysql.php");
	if ( isset($DispInfo) )
	{
		if ( count($error) == $nb_error_avant )
			echo '<img class="ImgValid" id="ImgValid_mysql" alt="" title="" src="images/Ok.png";/><br />';
		else
			echo '<img class="ImgValid" id="ImgValid_mysql" alt="" title="" src="images/Ko.png";/><br />'.join("<br/>", $error);
	}
}
if ( isset($DispInfo) )
	echo fn_GetTranslation('nettoyage_file');
flush();
if ( file_exists("js") )
{
	$dirjs='js';
	if ($handle = opendir($dirjs))
	{
		while (false !== ($entry = readdir($handle)))
		{
			if ( preg_match("/js\./", $entry) )
			{
				@unlink ( $dirjs.'/'.$entry) or array_push($error, fn_GetTranslation('unable_to_delete_file', $dirjs.'/'.$entry));
			}
		}
		closedir($handle);
	}
}

// Nettoyage des fichiers inutiles
foreach(array(	"help/extract.php",
				"help/renseigne.php",
				"config/cacert.crt",
				"help/fr/pushme.type.help",
				"help/fr/relai.ctlmessageup.help",
				"help/fr/relai.ctlmessagedn.help",
				"class/carteM2M.php",
				"class/terminal.php",
				"kml.php",
				"ViewGraph.php",
				"js/standard.js",
				"js/halo.js",
				"class/validate.php",
				"class/timerelt.php",
				"class/timer.php",
				"css/halo/ans.css",
				"css/halo/cnts.css",
				"css/halo/relais.css",
				"css/halo/scenarios.css",
				"css/halo/variables.css",
				"capturesview.php",
				"reflect2.php",
				"reflect3.php",
				"map.php",
				"ListOption.php",
				"runsav.php",
				"ViewCaptures.php",
				"ViewCapturesConfig.php",
				"ViewCapturesImages.php",
				"ViewCapturesMenu.php",
				"publier.sh",
				"test.php",
				"i.php",
				"Navbar.php",
				"Accueil.php",
				"js/jquerymobile",
				"css/jquerymobile",
				"js/ajax.php"
       ) as $file)
{
	if ( is_file($file) )
		@unlink($file) or array_push($error, fn_GetTranslation('unable_to_delete_file', $file));
	elseif ( is_dir($file) )
		@fn_RmDirRec($file) or array_push($error, fn_GetTranslation('unable_to_delete_dir', $file));
}
if ( isset($DispInfo) )
{
	if ( count($error) == 0 )
		echo '<img class="ImgValid" id="ImgValid_file" alt="" title="" src="images/Ok.png";/><br />';
	else
		echo '<img class="ImgValid" id="ImgValid_file" alt="" title="" src="images/Ko.png";/><br />';
}
require_once("connect_mysql.php");
$doc = new domDocument();
$doc->preserveWhiteSpace = FALSE;
$doc->formatOutput = TRUE;
$doc->load($GLOBALS["config_file"]);
$xpathdoc= new DOMXPath($doc);
$List = $xpathdoc->query('//config/general');
$general = $List->item(0);
foreach(array("mineur_version", "majeur_version") as $field)
{
	$List = $xpathdoc->query('//config/general/'.$field);
	if ( $List->length <> 0 )
	{
		$general->removeChild($List->item(0));
	}
	$New = $doc->createElement($field, (string)utf8_encode($GLOBALS["config"]->$field));
	$current = $general->appendChild($New);
}
$List = $xpathdoc->query('//config/general/timezone');
if ( $List->length == 0 )
{
	$New = $doc->createElement('timezone', date_default_timezone_get());
	$current = $general->appendChild($New);
}
$List = $xpathdoc->query('//config/general/lang');
if ( $List->length == 0 )
{
	$New = $doc->createElement('lang', "fr");
	$current = $general->appendChild($New);
}
$List = $xpathdoc->query('//config/general/skin');
if ( $List->length == 0 )
{
	$New = $doc->createElement('skin', "standard");
	$current = $general->appendChild($New);
}
$List = $xpathdoc->query('//config');
$general = $List->item(0);
foreach(array("typecnt", "modeltypecnt") as $class)
{
	$List = $xpathdoc->query('//config/'.$class);
	if ( $List->length <> 0 )
	{
		$general->removeChild($List->item(0));
	}
}
$List = $xpathdoc->query('//auths/user/carte');
if ( $List->length <> 0 )
{
	foreach ($List as $cible)
	{
		$cible->parentNode->removeChild($cible);
	}
}
$doc->save($GLOBALS["config_file"]);
// Passage des identifiants en int
$doc = new  domDocument();
$doc->preserveWhiteSpace = FALSE;
$doc->formatOutput = TRUE;
$doc->load($GLOBALS["config_file"]);
$xpathdoc= new DOMXPath($doc);
$data = array();
foreach(array("carte", "relai", "btn", "cnt", "an", "scenario", "camera", "user", "cron", "variable", "pushto", "graphique") as $class)
{
	if ( isset($GLOBALS["config"]->{$class."s"}->{$class}) )
	{
		foreach($GLOBALS["config"]->{$class."s"}->{$class} as $info)
		{
			$old_numero = $info->attributes()->numero;
			if ( ! preg_match("/^[0-9]*$/", $old_numero) )
			{
				// Passe les identifiants en numero
				$nodes = $xpathdoc->query('//'.$class.'s/'.$class);
				$numero = 0;
				if ( $nodes->length <> 0 )
				{
					foreach ($nodes as $current)
					{
						if ( preg_match("/^[0-9]*$/", $current->getAttribute("numero")) && intval($current->getAttribute("numero")) > $numero )
						{
							$numero = intval($current->getAttribute("numero"));
						}
					}
				}
				$new_numero = $numero + 1 ;
				if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
				{
					$sql = "UPDATE `".$class."` SET `numero` = ".$new_numero.", `carte_id` = 0 WHERE ";
					if ( isset($info->carteid) )
					{
						if ( $info->carteid != "" )
							$sql .= "`carte_id` = ".$info->carteid." and ";
						else
							$sql .= "`carte_id` = 0 and ";
					}
					$sql .= "`numero` = ";
					if ( isset($info->no) && $info->no != "" )
					{
						if ( $info->flag_indice == 1 )
							$sql .= ($info->no+1);
						else
							$sql .= $info->no;
					}
					else
						$sql .= $old_numero;
					@mysql_query($sql);
				}
				array_push($data, $class."_".$new_numero);
				$query='//'.$class.'[@numero="'.$old_numero.'"]';
				$nodes = $xpathdoc->query($query);
				foreach ($nodes as $item)
				{
					$item->setAttribute("numero", $new_numero);
				}
				$query='//ctrlbtn[text()="'.$old_numero.'"]';
				$nodes = $xpathdoc->query($query);
				foreach ($nodes as $cible)
				{
					$taille = strlen($cible->nodeValue);
					$cible->firstChild->deleteData(0,$taille);
					$cible->firstChild->insertData(0, (string)$new_numero);
				}
				$query='//cderelai';
				$nodes = $xpathdoc->query($query);
				foreach ($nodes as $cible)
				{
					$values = explode(',', (string)$cible->nodeValue);
					if ( in_array($old_numero, $values) )
					{
						$new_values = array();
						foreach ($values as $val)
						{
							if ( $old_numero == $val )
	                  	array_push($new_values, $new_numero);
	                  else
	                  	array_push($new_values, $val);
		            }
						$taille = strlen($cible->nodeValue);
						$cible->firstChild->deleteData(0,$taille);
						$cible->firstChild->insertData(0, join(',', $new_values));
					}
				}
				$query='//data';
				$nodes = $xpathdoc->query($query);
				foreach ($nodes as $cible)
				{
					$values = explode(',', (string)$cible->nodeValue);
					if ( in_array($class."_".$old_numero, $values) )
					{
						$new_values = array();
						foreach ($values as $val)
						{
							if ( $class."_".$old_numero == $val )
								array_push($new_values, $class."_".$new_numero);
							else
								array_push($new_values, $val);
						}
						$taille = strlen($cible->nodeValue);
						$cible->firstChild->deleteData(0,$taille);
						$cible->firstChild->insertData(0, (string)join(',', $new_values));
					}
				}
				$query='//compensation[text()="'.$old_numero.'"]';
				$nodes = $xpathdoc->query($query);
				foreach ($nodes as $cible)
				{
					$taille = strlen($cible->nodeValue);
					if ( $taille != 0 )
					{
						$cible->firstChild->deleteData(0,$taille);
						$cible->firstChild->insertData(0, (string)$new_numero);
					}
					else
					{
						$cible->parentNode->removeChild($cible);
						$NewCaracteristique = $doc->createElement($key, (string)$new_numero);
						$cible->appendChild($NewCaracteristique);
					}
				}
			}
			else
				array_push($data, $class."_".$old_numero);
		}
	}
}
$doc->save($GLOBALS["config_file"]);
unset($GLOBALS["config"]);
require ('LoadConfig.php');
if ( ! isset($GLOBALS["config"]->pages) )
{
	require_once("class/Page.php");
	$current = new Page();
	$current->label = "Default";
	$current->data = $data;
	$current->image = "./images/login/icon.png";
	$current->save();
	fn_InitAuthAllUser("page", $current->numero);
}
foreach(array("relai", "btn", "cnt", "an", "variable", "carte", "scenario", "camera", "user", "cron", "pushto", "graphique", "page") as $class)
{
	if ( isset($GLOBALS["config"]->{$class."s"}->{$class}) )
	{
		require_once("class/".$class.".php");
		if ( isset($DispInfo) )
			echo fn_GetTranslation('update_class', $class);
		flush();
		foreach($GLOBALS["config"]->{$class."s"}->{$class} as $info)
		{
			if ( $class == "carte" )
			{
				$model = fn_GetModel($class, $info->attributes()->numero);
				$current = new $model($info->attributes()->numero, $info);
			}
			else
				$current = new $class($info->attributes()->numero, $info);
			$GLOBALS["auto"] = true;
			$GLOBALS["warning"] = $warning;
			$current->update();
			$warning = $GLOBALS["warning"];
			$current->save();
		}
		if ( isset($DispInfo) )
			echo '<img class="ImgValid" id="ImgValid_'.$class.'" alt="" title="" src="images/Ok.png";/><br />';
	}
}
?>