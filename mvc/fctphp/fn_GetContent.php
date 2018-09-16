<?php
	function fn_GetContent($url, $user="", $password="", $connecttimeout = 1, $timeout = 2){
		
		if ((string)$GLOBALS["config"]->general->debug == 'on')
			fn_Trace($url, "get");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , $connecttimeout); //timeout in seconds to try to connect
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //timeout in seconds
		if ($user != "")
			curl_setopt($ch, CURLOPT_USERPWD, $user.":".$password);
		$data = curl_exec($ch);
		$reponseInfo = curl_getinfo($ch);
		$httpResponseCode = $reponseInfo['http_code'];
		curl_close($ch);
		if ($httpResponseCode != 200)
			$data=false;
		return array ($httpResponseCode, $data);
	}
?>