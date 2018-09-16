<?php
function send_message_nma($account, $passwd, $apikey, $application, $message, $moreinfo, $url)
{
	$retour = "";
	$code = false;
	$nma = new NotifyMyAndroid(); // Default creator
	// For a more detailed creator, use the signature NotifyMyAndroid($apikey=null, $verify=false, $devkey=null, $proxy=null, $userpwd=null)

	$nma_params = array(
	'apikey' => $apikey, // User API Key. CHANGE THIS TO YOUR API KEY
	//'developerkey' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', // Developer key. If you have one.
	'priority' => 0, // Range from -2 to 2.
	'application' => $application, // Name of the app.
	'event' => $message, // Name of the event.
	'description' => $message // Description of the event.
	);

	//Verify the APIKEY, this step is not necessary to send a notification
	if( !$nma->verify($apikey) ) { // CHANGE TO YOUR API KEY
	$retour .= $nma->getError() .PHP_EOL;
	} else {
	$retour .= "APIKEY is valid!".PHP_EOL;
	}

	//Send the notification
	if( !$nma->push( $nma_params ) ) {
	$retour .= $nma->getError().PHP_EOL;
	} else {
	$retour .= "Notification sent!".PHP_EOL;
	$code = true;
	}
	return array($code, $retour);
}
?>