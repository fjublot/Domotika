<?php 
	function fn_WgetXml($url) {
		return @simplexml_load_file($url);
	}
?>