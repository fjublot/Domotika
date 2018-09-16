<?php
function send_message_livenotifier($account, $password, $apikey, $application, $message, $moreinfo, $url)
{
	if ( wget('http://api.livenotifier.net/notify?apikey='.$apikey.'&title='.$GLOBALS["config"]->general->nameappli.'&message='.urlencode($message)) )
		return array(true, "Notification Ok");
	else
		return array(false, "Erreur de notification");
}
?>