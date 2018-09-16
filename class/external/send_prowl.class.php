<?php
	function send_message_prowl($account, $password, $signature, $application, $message, $moreinfo, $url) {
			$params = array('application' => $application, 'url' => $url, 'description' => $message." - ".utf8_encode(date("H:i:s")), 'apikey' => $signature);			
			if (isset($moreinfo) && $moreinfo<>"") {
				$paramsuppls= explode(";", $moreinfo);
				foreach($paramsuppls as $paramsuppl) {
					$paramval = explode("=", $paramsuppl);
					$params[$paramval[0]] = $paramval[1] ;
				}
			}
			

			// Encode the parameters for transport.
			$encodedParameters = array();
			foreach( $params as $key => $value ) {
						$encodedParameters[] = $key . "=" .urlencode($value);
			}
			$body = implode("&", $encodedParameters);
			$url="https://api.prowlapp.com/publicapi/add?".$body;
			/*
			$agent = "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.12) Gecko/2009070611 Firefox/3.0.12";
			$url=$url."?".$body;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $agent);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($curl, CURLOPT_USERPWD, $account .":". $password);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_exec($curl);
			curl_close($curl);
			*/
			$response = file_get_contents($url);	
			//$response = new SimpleXMLElement($response);

			fn_Trace($url,'prowl');
			return array(true, "Notification Ok");
		
	}
?>