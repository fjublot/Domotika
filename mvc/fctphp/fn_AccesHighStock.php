<?php 
function fn_AccesHighStock()
{
	if ( ! file_exists($GLOBALS["root_path"]."ressources/highstock") )
		return false;
	if ( ! file_exists($GLOBALS["root_path"]."ressources/highstock/highstock.js") )
		return false;
	return true;
}
?>