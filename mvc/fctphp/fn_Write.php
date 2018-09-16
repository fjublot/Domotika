<?php 
	function fn_Write($file, $data) {
		$dir = dirname($file);
		if ( !is_dir($dir) )
			mkdir($dir);
		if ( $fp = @fopen($file, 'a') )
		{
			fwrite($fp, $data);
			fclose($fp);
		}
		else
		{
			return false;
		}
		return true;
	}
?>