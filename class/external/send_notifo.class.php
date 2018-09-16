<?php
function send_message_notifo($account, $apikey, $application, $message, $moreinfo, $url)
{
	$retour = "";
	$notifo = new Notifo_API($account, $apikey);
	
	/* set the notification parameters */
	$params = array("to"=>$account, /* "to" only used with Service accounts */
	"msg"=>$message,
	"title"=>$application,
	"uri"=>$url);
	
	/* send the notification! */
	$reponse = $notifo->send_notification($params);
	$code = false;
	
	return array($code, $reponse);
}
?>