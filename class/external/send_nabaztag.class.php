<?php
function send_message_nabaztag($account, $apikey, $application, $event, $message, $moreinfo, $url)
{
	if ( wget('http://api.nabaztag.com/?sn='.$account.'&token='.$apikey.'&tts='.urlencode($message)) )
		return array(true, "Notification Ok");
	else
		return array(false, "Erreur de notification");
}
?>