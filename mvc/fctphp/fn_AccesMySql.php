<?php 
	function fn_AccesMySql()
	{
		return function_exists("mysql_connect");
	}
?>