<?php
require_once($GLOBALS["page_inc_path"] . 'loginverif.php');
include($GLOBALS["page_inc_path"] . 'head.php');
include($GLOBALS["page_inc_path"] . 'topbar.php');
//require_once($GLOBALS["page_inc_path"] . "connect_mysql.php");
$current = new $_REQUEST["class"]($_REQUEST["numero"]);
if ( fn_GetAuth($_SESSION["AuthId"], $_REQUEST["class"], $current->numero) )
{
	$current->mysql_load();
	echo $current->disp_info();
	echo "<iframe width=1000 height=500 src='".$current->disp_url()."'></iframe>";
}
include($GLOBALS["page_inc_path"] . 'foot.php');