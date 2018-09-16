<?php
function send_message_custom($account, $password, $apikey, $application, $event, $message, $moreinfo, $url)
{
	   	$headers = 'From: '.$GLOBALS["config"]->general->nameadmin.' <'.$GLOBALS["config"]->general->mailadmin.'> '. "\r\n" .
     'X-Mailer: PHP/' . phpversion().'\r\n' .
     'MIME-Version: 1.0' . "\r\n" .
     'Content-type: text/html; charset=iso-8859-1' . "\r\n";

  
  if ( @mail($account, $application , '<html><body>'.urldecode($message).'</body></html>', $headers) )
		{fn_Trace("Envoie du mail à ".$account." Ok.","mail");
    return array(true, "Envoie du mail à ".$account." Ok.");
	}
  else
	{ fn_Trace("Envoie du mail à ".$account." impossible.","mail");
  	return array(false, "Envoie du mail à ".$account." impossible.");
	}
}

function send_message_mail($account, $password, $apikey, $application, $message, $moreinfo, $url)
{
	$message .= PHP_EOL. "\r\n";
	$message .= $url;
  $headers = 'From: '.$GLOBALS["config"]->general->nameappli.' <'.$GLOBALS["config"]->general->mailadmin.'> '. "\r\n" .
  'X-Mailer: PHP/' . phpversion().'\r\n' .
  'MIME-Version: 1.0' . "\r\n" .
  'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $object = $moreinfo;
	if ( @mail($account, $application , $message, $headers) )
		return array(true, "Envoie du mail à ".$account." Ok");
	else
		return array(false, "Envoie du mail à ".$account." impossible.");
}
?>