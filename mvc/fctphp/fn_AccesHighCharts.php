<?php 
	function fn_AccesHighCharts()
	{
		if ( ! file_exists($GLOBALS["root_path"]."ressources/highcharts") )
			return false;
		if ( ! file_exists($GLOBALS["root_path"]."ressources/highcharts/highcharts.js") )
			return false;
		return true;
	}
?>