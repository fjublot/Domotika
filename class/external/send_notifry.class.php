<?php
require_once($GLOBALS["class_path"]."external/notifry.php");
// Configuration.
function send_message_notifry($account, $passwd, $apikey, $application, $message, $moreinfo, $url)
{
	$result = notifry($apikey, $application, $message, $url);
	if ( isset($result['message']) )
	{
		return array($result['success'], $result['message']);
	}
	else
	{
		return array($result['success'], $result['error']);
	}
}
?>