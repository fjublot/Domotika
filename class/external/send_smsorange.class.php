<?php
function send_message_smsorange($account, $apikey, $application, $event, $message, $moreinfo, $url)
{
	if ( wget('http://run.orangeapi.com/sms/sendSMS.xml?id='.$apikey.'&from=20345&to='.$account.'&content='.urlencode($message)) )
		return array(true, "Notification Ok");
	else
		return array(false, "Erreur de notification");
}
?>