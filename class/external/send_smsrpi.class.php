<?php
	function send_message_smsrpi(
		$account, 
		$apikey="", 
		$application="", 
		$event="", 
		$message, 
		$moreinfo="", 
		$url="", 
		$image=""
	) {
		global $db;
		$timezone=-1;
		$now = gmdate("Y/m/j H:i:s", time() + 3600*($timezone+date("I")));
		$content = gmdate("Y/m/j H:i:s", time() + 3600*(1+date("I"))).' - '.($message);
		$rowcount = 0;
		$db->beginTransaction();
		$rowcount = $db->createScheduleds($now, $content);
		$rowcount = $rowcount + $db->createScheduleds_numbers($db->lastId(), $account);
		$retcommit = $db->commit();
		
		if ($rowcount == 2 && $retcommit == true) {
			return array(true, "SMS Ok");
		}
		else
			return array(false, "Erreur SMS");
	}
?>