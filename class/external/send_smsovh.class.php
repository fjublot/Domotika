<?php

function send_message_smsovh($account, $password, $apikey, $application, $message, $moreinfo, $url)
{
	$moreinfo = explode(",", $moreinfo);
	try {
		$soap = new SoapClient("https://www.ovh.com/soapi/soapi-re-1.54.wsdl");

		//telephonySmsUserSend
		$result = $soap->telephonySmsUserSend($account, $password, $apikey, $moreinfo[0], $moreinfo[1], $message);
		print_r($result); // your code here ...
		return array(true, "Notification Ok");
	} catch(SoapFault $fault) {
		return array(false, "Erreur de notification");
	}
}
?>