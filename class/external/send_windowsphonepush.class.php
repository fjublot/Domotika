<?php
function send_message_windowsphonepush($account, $password, $apikey, $application, $event, $message, $moreinfo, $url)
{
	if ( function_exists("curl_init") )
	{
		$notif = new WindowsPhonePushNotification($apikey);
		$notif->push_toast($application, $message);
		return array(true, "Notification envoye");
	}
	return array(false, "Erreur de notification");
}
?>