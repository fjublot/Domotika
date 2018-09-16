<?php 
	function fn_WgetText($url)
	{
		$data=false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
	//	curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_TIMEOUT, 10 );
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
?>