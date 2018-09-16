<?php
function send_message_smsfree($account, $apikey, $application, $event, $message, $moreinfo, $url, $image)
{
	trace('https://smsapi.free-mobile.fr/sendmsg?user='.$account.'&pass='.$apikey.'&msg='.urlencode($message),'freesms');
  if ( wget('https://smsapi.free-mobile.fr/sendmsg?user='.$account.'&pass='.$apikey.'&msg='.urlencode($message)) )
		return array(true, "Notification Ok");
	else
		return array(false, "Erreur de notification");
}
?>