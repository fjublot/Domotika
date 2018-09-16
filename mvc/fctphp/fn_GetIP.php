<?php
	function fn_GetIP() {
		$CurrentIP = '';
		if (getenv('HTTP_CLIENT_IP')) {
			$CurrentIP =getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$CurrentIP =getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_X_FORWARDED')) {
			$CurrentIP =getenv('HTTP_X_FORWARDED');
		} elseif (getenv('HTTP_FORWARDED_FOR')) {
			$CurrentIP =getenv('HTTP_FORWARDED_FOR');
		} elseif (getenv('HTTP_FORWARDED')) {
			$CurrentIP = getenv('HTTP_FORWARDED');
		} else {
			$CurrentIP = $_SERVER['REMOTE_ADDR'];
		}
		if (strlen($CurrentIP)<7)
			$CurrentIP='127.0.0.1';
		return $CurrentIP;
	}
?>