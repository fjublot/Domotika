<?php
header('Content-Type: text/xml; charset: UTF-8');
require_once("LoadConfig.php");
session_name((string)$GLOBALS["config"]->general->namesession);
session_start();
fn_LoadTraduction();
$_SESSION['Debit'] = $_REQUEST["debit"];
fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"xml");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<Debit>
	<titre>Compensation</titre>
	<message><?php
		//si haut debit
		if  ( $_SESSION['Debit'] > 0 )
			echo fn_GetTranslation('height_speed_connect');
		else
			echo fn_GetTranslation('low_speed_connect');
	?></message>
</Debit>