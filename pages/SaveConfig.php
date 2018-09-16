<?php
//require_once ('loadconfig.php');
error_reporting(E_ALL);
//session_name((string)$GLOBALS["config"]->general->namesession); 
//session_start();

$File = 'Config_'.date("d-m-Y_G-i-s").'.zip';

header('Content-Type: application/zip');
header("Cache-Control: no-cache");
header('Content-disposition: attachment; filename='.$File);
header("Content-Description: File Transfer");

$zip = new ZipArchive();
if ( $zip->open($File, ZipArchive::CREATE) !== TRUE )
{
	echo "Impossible de créer le ZIP";
	die;
}
$zip->addFile("config/config.conf", "config/config.conf");
foreach($GLOBALS["config"]->{"scenarios"}->{"scenario"} as $info)
{
	$numero = $info->attributes()->numero;
	$zip->addFile("config/scenarios/".$numero.".inc", "config/scenarios/".$numero.".inc");
}
if ( $dh = opendir($GLOBALS["root_path"]."/config/images") )
{
	while (($file = readdir($dh)) !== false)
	{
		if (  is_file($GLOBALS["root_path"]."/config/images/".$file) && $file != ".htaccess" && $file != "Thumbs.db" )
			$zip->addFile("config/images/".$file, "config/images/".$file);
	}
	closedir($dh);
}
$zip->close();

$handle = fopen($File, "r");
while ( ! feof($handle) )
{
  print(fread($handle, 8192));
}
fclose($handle);
unlink($File);
?>