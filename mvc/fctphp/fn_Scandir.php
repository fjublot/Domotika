<?php
	if ( ! function_exists("scandir") ) {
		function scandir($dir) {
			$files = array();
			$dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))) {
				array_push($files, $filename);
			}
			return $files;
		}
	}
	else {
		function fn_Scandir($dir) {
			return scandir($dir);
		}
	}	
?>