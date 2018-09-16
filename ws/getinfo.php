<?php
/*----------------------------------------------------------------*
* Titre : getinfo.php                                             *
* Script de debug                                                 *
*----------------------------------------------------------------*/
header('Content-Type: text/xml; charset: UTF-8');
header("Cache-Control: no-cache");
header('Content-disposition: attachment; filename=debug.xml');
header("Content-Description: File Transfer");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<document>';
echo '<phpinfo>';
ob_start();
phpinfo();
$info_arr = array();
$info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
$vide = 1;
foreach($info_lines as $line)
{
	// new cat?
	if ( preg_match("~<h2>(.*)</h2>~", $line, $title))
	{
		if ( isset($cat) )
			echo "</".$cat.">";
		echo "<".strtr ($title[1], " ", "_").">".PHP_EOL;
		$cat = strtr($title[1], " ", "_");
	}
	if( preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
	{
		$val[1] = html_entity_decode ($val[1]);
		$val[1] = strtr($val[1], " :/.'\"[]", "________");
		$val[1] = trim($val[1], "_");
		$val[1] = str_replace("|", "", $val[1]);
		$val[1] = str_replace("(", "", $val[1]);
		$val[1] = str_replace(")", "", $val[1]);
		$val[2] = str_replace("\n", "", $val[2]);
		$val[2] = str_replace("\r", "", $val[2]);
		$val[2] = html_entity_decode ($val[2]);
		$val[2] = htmlspecialchars ($val[2], ENT_NOQUOTES);
		if ( $val[1] == "" )
		{
			$val[1] = "vide_".$vide;
			$vide++;
		}
		echo "<".$val[1].">".utf8_encode($val[2])."</".$val[1].">".PHP_EOL;
	}
	elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
	{
		$val[1] = html_entity_decode ($val[1]);
		$val[1] = strtr($val[1], " :/.'\"[]", "________");
		$val[1] = trim($val[1], "_");
		$val[1] = str_replace("(", "", $val[1]);
		$val[1] = str_replace(")", "", $val[1]);
		$val[2] = str_replace("\n", "", $val[2]);
		$val[2] = str_replace("\r", "", $val[2]);
		$val[2] = html_entity_decode ($val[2]);
		$val[2] = str_replace("\n", "", $val[3]);
		$val[2] = str_replace("\r", "", $val[3]);
		$val[3] = html_entity_decode ($val[3]);
		$val[2] = htmlspecialchars ($val[2], ENT_NOQUOTES);
		$val[3] = htmlspecialchars ($val[3], ENT_NOQUOTES);
		if ( $val[1] == "" )
		{
			$val[1] = "vide_".$vide;
			$vide++;
		}
		echo "<".$val[1]."-def>".utf8_encode($val[2])."</".$val[1]."-def>".PHP_EOL;
		echo "<".$val[1].">".utf8_encode($val[3])."</".$val[1].">".PHP_EOL;
	}
}
if ( isset($cat) )
	echo "</".$cat.">";
echo '</phpinfo>';
if ( file_exists($GLOBALS["config_file"]) )
{
	$doc = new domDocument();
	$doc->preserveWhiteSpace = FALSE;
	$doc->formatOutput = TRUE;
	$doc->load($GLOBALS["config_file"]);
	$xpathdoc= new DOMXPath($doc);
	foreach (array("password", "mysql_password", "signature", "pass") as $key)
	{
		$xpath = '//'.$key;
		$nodes = $xpathdoc->query($xpath);
		if ( $nodes->length != 0 )
		{
			foreach ($nodes as $item)
			{
				$parentNode = $item->parentNode;
				$parentNode->removeChild($item);
				$NewCaracteristique = $doc->createElement($key, "*******");
				$parentNode->appendChild($NewCaracteristique);
			}
		}
	}
	foreach (array("url") as $key)
	{
		$xpath = '//'.$key;
		$nodes = $xpathdoc->query($xpath);
		if ( $nodes->length != 0 )
		{
			foreach ($nodes as $item)
			{
				$parentNode = $item->parentNode;
				$parentNode->removeChild($item);
				$NewCaracteristique = $doc->createElement($key, "http://******/****/");
				$parentNode->appendChild($NewCaracteristique);
			}
		}
	}
	foreach (array("ip", "host", "mysql_host") as $key)
	{
		$xpath = '//'.$key;
		$nodes = $xpathdoc->query($xpath);
		if ( $nodes->length != 0 )
		{
			foreach ($nodes as $item)
			{
				$parentNode = $item->parentNode;
				$parentNode->removeChild($item);
				$NewCaracteristique = $doc->createElement($key, "0.0.0.0");
				$parentNode->appendChild($NewCaracteristique);
			}
		}
	}
	foreach (array("mailadmin", "mail") as $key)
	{
		$xpath = '//'.$key;
		$nodes = $xpathdoc->query($xpath);
		if ( $nodes->length != 0 )
		{
			foreach ($nodes as $item)
			{
				$parentNode = $item->parentNode;
				$parentNode->removeChild($item);
				$NewCaracteristique = $doc->createElement($key, "*******@*****.**");
				$parentNode->appendChild($NewCaracteristique);
			}
		}
	}
	$xpath = '//pushto/label';
	$nodes = $xpathdoc->query($xpath);
	if ( $nodes->length != 0 )
	{
		foreach ($nodes as $item)
		{
			$parentNode = $item->parentNode;
			$parentNode->removeChild($item);
			$NewCaracteristique = $doc->createElement("label", "**********");
			$parentNode->appendChild($NewCaracteristique);
		}
	}
	$xpath = '//pushto/moreinfo';
	$nodes = $xpathdoc->query($xpath);
	if ( $nodes->length != 0 )
	{
		foreach ($nodes as $item)
		{
			$parentNode = $item->parentNode;
			$parentNode->removeChild($item);
			$NewCaracteristique = $doc->createElement("moreinfo", "**********");
			$parentNode->appendChild($NewCaracteristique);
		}
	}
	foreach($doc->childNodes as $node)
		echo utf8_encode ($doc->saveXML($node)).PHP_EOL;
}
$doc = new domDocument();
$doc->preserveWhiteSpace = FALSE;
$doc->formatOutput = TRUE;
$doc->load("default.conf");
foreach($doc->childNodes as $node)
	echo utf8_encode ($doc->saveXML($node)).PHP_EOL;
if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
{
	echo '<mysql>';
//	include($GLOBALS["page_inc_path"] .'connect_mysql.php');
	foreach (array("an", "btn", "cnt", "relai") as $item)
	{
		echo '<'.$item.'s>';
		$select = "SELECT `numero`, count(`time`) as nb FROM ".$item." group BY `numero` ORDER BY `numero`";
		$result = mysql_query($select) or die ('Erreur : '.mysql_error()." -> ".$select );
		while ( $row = mysql_fetch_assoc($result) )
		{
			echo "<".$item." numero='".$row["numero"]."'>";
			echo "<numero>".$row["numero"]."</numero>";
			echo "<nb>".$row["nb"]."</nb>";
			$select = "SELECT max(`time`) as max FROM ".$item;
			$result_tmp = mysql_query($select) or die ('Erreur : '.mysql_error()." -> ".$select );
			$row = mysql_fetch_assoc($result_tmp);
			mysql_free_result($result_tmp);
			echo "<last>".date("d/m/Y G:i:s", $row["max"])."</last>";
			echo "</".$item.">";
		}
		mysql_free_result($result);
		echo '</'.$item.'s>';
	}
	echo '</mysql>';
}
if ( isset($GLOBALS["config"]->{"cartes"}->{"carte"}) )
{
	echo "<carte_info>";
	foreach($GLOBALS["config"]->{"cartes"}->{"carte"} as $info)
	{
		echo "<carte numero='".(string)$info->attributes()->numero."'>";
		require_once('class/carte.php');
		$model = fn_GetModel("carte", $info->attributes()->numero);
		$current = new $model($info->attributes()->numero, $info);
		if ( $current->portm2m != "" )
			echo "<mode>M2M</mode>";
		else
			echo "<mode>http</mode>";
		if ( $current->active == 1 )
		{
			$current->get_status();
			if ( $current->status === false )
				echo "<error>acces refuse</error>";
			else
				echo preg_replace('!<\?xml version="1\.0"\?>!', "", $current->status->asXML());
		}
		echo "</carte>";
	}
	echo "</carte_info>";
}
if ( is_dir("config/scenarios") )
{
	echo '<scenarios>';
	foreach(fn_DirectoryToArray("config/scenarios") as $file)
	{
		$code = join("\n",file($file));
		$code = preg_replace ("!http://.*/!", "http://.../", $code);
		$code = preg_replace ("!pwd=[a-zA-Z0-9]*!", "pwd=XXX", $code);
		$code = preg_replace ("!password=[a-zA-Z0-9]*!", "password=XXX", $code);
		echo '<scenario id="'.$file.'">'.htmlspecialchars($code).'</scenario>'.PHP_EOL;
	}
	echo '</scenarios>';
}
if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on' )
{
	$Luser = array();
	$Luser[0] = "Inconnu";
	if ( isset($GLOBALS["config"]->users) )
	{
		$xpath = "//users/user";
		$ListUser = $GLOBALS["config"]->xpath($xpath);
		foreach($ListUser as $user)
		{
			$Luser[(string)$user->attributes()->numero] = $user->label;
		}
	}
	echo "<traces>";
	$select = "SELECT * FROM `trace` ORDER BY `time` DESC, `microtime` DESC LIMIT 100";
	$result = mysql_query($select) or die ('Erreur : '.mysql_error()." -> ".$select );
	while ( $row = mysql_fetch_assoc($result) )
	{
		echo "<trace>";
			echo "<timezone>".$row["timezone"]."</timezone>";
			if ( ! isset($_REQUEST["jour"]) )
				echo "<jour>".date("d/m/Y", $row["time"])."</jour>";
			echo "<heure>".date("H:i:s", $row["time"])."</heure>";
			echo "<microtime>".$row["microtime"]."</microtime>";
			echo "<type>".$row["type"]."</type>";
			echo "<user>".$Luser[$row["user_id"]]."</user>";
			echo "<from>".long2ip($row["from"])."</from>";
			$row["texte"] = preg_replace ("!http://.*/!", "http://.../", $row["texte"]);
			$row["texte"] = preg_replace ("!mail=[a-zA-Z0-9]*!", "mail=XXX", $row["texte"]);
			$row["texte"] = preg_replace ("!pass=[a-zA-Z0-9]*!", "pass=XXX", $row["texte"]);
			$row["texte"] = preg_replace ("!passconfirm=[a-zA-Z0-9]*!", "passconfirm=XXX", $row["texte"]);
			$row["texte"] = preg_replace ("!ip=[a-zA-Z0-9\.]*!", "ip=0.0.0.0", $row["texte"]);
			echo "<texte>".$row["texte"]."</texte>";
		echo "</trace>";
	}
	mysql_free_result($result);
	echo "</traces>";
}
echo '<timezone>';
$phptimezone = date_default_timezone_get();
date_default_timezone_set($phptimezone);
echo '<php>'.$phptimezone.'</php>';
echo '<jour>'.date("d/m/Y").'</jour>';
echo '<heure>'.date("G:i:s").'</heure>';
if ( isset($_SESSION["timezone"]) )
{
	date_default_timezone_set($_SESSION["timezone"]);
	echo '<user>'.$_SESSION["timezone"].'</user>';
	echo '<jour>'.date("d/m/Y").'</jour>';
	echo '<heure>'.date("G:i:s").'</heure>';
}
echo '</timezone>';
echo '<file_list>';
foreach(fn_DirectoryToArray($GLOBALS["root_path"]) as $file)
{
	echo '<file size="'.filesize($file).'" type="'.filetype($file).'" mdate="'.date ("d/m/Y H:i:s", filemtime($file)).'" right="'.fn_FileAccessRight($file).'" owner="'.fileowner($file).'" group="'.filegroup($file).'">'.$file.'</file>'.PHP_EOL;
}
echo '</file_list>';
echo '</document>';
?>