<?php 
	function fn_GetServeurUrl()
	{
		$fn_GetServeurUrl = "http";
		if ( isset($_SERVER["HTTPS"]) )
			$fn_GetServeurUrl .= "s";
		$fn_GetServeurUrl .= "://".$_SERVER["HTTP_HOST"];
		if ( $_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "" )
			$fn_GetServeurUrl .= ":".$_SERVER["SERVER_PORT"];
		$fn_GetServeurUrl .= dirname($_SERVER["REQUEST_URI"])."/";
		return $fn_GetServeurUrl;
	}
?>