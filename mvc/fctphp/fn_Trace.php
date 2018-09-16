<?php 
	function fn_Trace($message, $type = "error", $timezone = null) {
		global $db;
		//if ( log_level_to_int($GLOBALS["config_xml"]->log) <= log_level_to_int($type) )
		if ( getenv("HTTP_X_FORWARDED_FOR") )
			$ClientIP = getenv("HTTP_X_FORWARDED_FOR");
		else if ( getenv("REMOTE_ADDR") )
			$ClientIP = getenv("REMOTE_ADDR");
		else
			$ClientIP = "127.0.0.1";
		if ( isset($_SESSION["AuthId"]) )
			$AuthId = $_SESSION["AuthId"];
		else
			$AuthId = '100000';
		if (date_default_timezone_get()!=ini_get('date.timezone')) {
			$timezone_old = date_default_timezone_get();
		}
		else {
			date_default_timezone_set('Europe/Paris');
			$timezone_old = date_default_timezone_get();
		}
		date_default_timezone_set('UTC');
		if ( $timezone == null && isset($_SESSION["Timezone"]) && $_SESSION["Timezone"]!="")
			$timezone = $_SESSION["Timezone"];
		if ( $timezone == null && isset($GLOBALS["config"]) )
			$timezone = $GLOBALS["config"]->general->timezone;
		
		if ( preg_match("!^(.*)http://(.*):(.*)@(.*)$!", $message, $regs) )
			$message = $regs[1]."http://".$regs[2].":XXXX@".$regs[4];
		$microtime = microtime(true);
		$time = floor($microtime);
		$strtime = Date('Y-m-d H:i:s', $time );
		$microtime = (int)(($microtime -$time)*1000);
		if (ip2long($ClientIP) == false)
			$ip=0;
		else
			$ip=ip2long($ClientIP);

		$return['authId']	= $AuthId;
		$return['type']     = $type;
		$return['ip']       = $ip;
		$return['message']  = $message;
		$return['timeutc']  = $strtime;
		$return['timezone'] = $timezone;
		$return['microtime']= $microtime;
		$return['creation']= false;			
		$return['traceid']= false;		
		
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on') {
			$db->begintransaction();
			$creation =  $db->createTrace($AuthId, $type, $ip, $message, $strtime, $timezone, $microtime); //On retourne le nombre de lignes ajoutés
			if ($creation==1) {
				$return['creation']= true;
				$return['traceid'] = $db->lastId();
			}
			$db->commit();
		}
		else {
			if ( $fp = @fopen('trace/'.$type.'_'.date('Y-m-d').'.html', 'a') ) {
				$status = fwrite($fp, date('d/m/Y H:i:s')." - ".$ClientIP." - ".$AuthId." - ".$message.PHP_EOL."<br>");
				$status = fclose($fp);
				$return['creation']= true;
			}
		}
		if ($timezone_old != null)
			date_default_timezone_set($timezone_old);
			
		return $return;
	}
?>