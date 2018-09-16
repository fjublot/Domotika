<?php
	function fn_SendMail($message) {
		$headers = 'From: '.$GLOBALS["config"]->general->nameadmin.' <'.$GLOBALS["config"]->general->mailadmin.'> '. "\r\n" . 'X-Mailer: PHP/' . phpversion();
		mail($GLOBALS["config"]->general->mailadmin, "Carte IPX", $message, $headers);
	}
?>