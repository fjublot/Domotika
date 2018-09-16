<?php 
	function fn_Alert($carteid, $class, $no, $message, $traceid=null, $timeutc, $timezone, $microtime) {
		global $db;
		if (date_default_timezone_get()!=ini_get('date.timezone')) {
			$timezone_old = date_default_timezone_get();
		}
		else {
			date_default_timezone_set('Europe/Paris');
			$timezone_old = date_default_timezone_get();
		}
		date_default_timezone_set('UTC');
		if ( $timezone == null && isset($_SESSION["Timezone"]) )
			$timezone = $_SESSION["Timezone"];
		if ( $timezone == null && isset($GLOBALS["config"]) )
			$timezone = $GLOBALS["config"]->general->timezone;
		$return['creation']= false;			
		$return['alertid']= false;			
		
		if ( isset($GLOBALS["config"]->general->mysql) && $GLOBALS["config"]->general->mysql == 'on') {
			$db->begintransaction();
			$creation =  $db->createAlert($carteid, $class, $no, $message, $timeutc, $timezone, $microtime, $traceid); //On retourne le nombre de lignes ajoutés
			if ($creation==1) {
				$return['creation']= true;
				$return['alertid'] = $db->lastId();
				$return['traceid'] = $traceid;
			}
			$db->commit();
		}
		if ($timezone_old != null)
			date_default_timezone_set($timezone_old);
		
		return $return;
	}
?>