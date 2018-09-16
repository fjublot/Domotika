<?php
/*----------------------------------------------------------------*
* Titre : ListDir.php                                             *
* Programme permettant de lister les répertoires d'un dossier     *
*----------------------------------------------------------------*/
require_once("LoadConfig.php");
session_name((string)$GLOBALS["config"]->general->namesession);
session_start();
header('Content-Type: text/xml; charset: UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo "<document>";
if ( isset($_REQUEST["dossier"]) )
{
    $folder = opendir ($_REQUEST["dossier"]);
    $listdir=array();
    $i=0;
    while ($file = readdir ($folder))
    {
        if ($file != "." && $file != "..")
        {
            $pathfile = $_REQUEST["dossier"].'/'.$file;
            if(filetype($pathfile) == $_REQUEST["type"])
            {
              $listdir[$i]=$file;
              $i++;
            }
        }
    }
    closedir ($folder);
    natcasesort($listdir);
    foreach ($listdir as $dir)
    {
    if ($_REQUEST["type"]=='dir')
        echo '<element value="'.$dir.'" dossier="'.$_REQUEST["dossier"].'/'.$dir.'"></element>';
    if ($_REQUEST["type"]=='file')
        echo '<element value="'.$dir.'" dossier="'.$_REQUEST["dossier"].'/"></element>';
    }
}
echo "</document>";
?>