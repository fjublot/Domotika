<?php 
	function fn_AccesJpGraph()
	{
		if ( ! file_exists($GLOBALS["root_path"]."jpgraph") )
			return false;
		if ( ! file_exists($GLOBALS["root_path"]."jpgraph/jpgraph.php") )
			return false;
		return true;
	}
?>