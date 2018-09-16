<?php
require_once($GLOBALS["class_path"]."external/newtifry.php");
// Configuration.
function send_message_newtifry($account, $passwd, $apikey, $application, $message, $moreinfo, $url)
{
	$result = newtifry($apikey, $application, $message, $url);
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