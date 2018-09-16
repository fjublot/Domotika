<?php
	header('Content-Type: application/json; charset=utf-8');
	$pushurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	fn_Trace($pushurl , "push-in");
	if ( $ip == "") $ip = $_SERVER["REMOTE_ADDR"];
	$entree['pushurl'] = $pushurl;
	$entree['message'] = $message;
	$entree['carteid'] = $carteid;
	$entree['camera']  = $camera;
	$entree['ip']      = $ip;
	$entree['class'] = $class;
	$entree['numero'] = $numero;
	$entree['value'] = $value;

	
	
	if ( isset($GLOBALS["config"]->cartes) && $carteid!="" && $message!="" ) {
		preg_match("/^(.*) ([^ ]*)$/", $message, $regs);
		$xmlid = '';
		$actionipx = '';
		if (isset($regs[1]))
			$xmlid = $regs[1];
		if (isset($regs[2])) 
			$actionipx = $regs[2]; 

		$xpath = "//*/*[xmlid='".$xmlid."' and carteid='".$carteid."']";
		fn_Trace($xpath , "xpath");

		$xmlinfo = $GLOBALS["config"]->xpath($xpath);
		$analyse['xmlid']  = $xmlid;
		$analyse['actionipx'] = $actionipx; 
		$analyse['xpath']     = $xpath;

		if ( count($xmlinfo) == 1 ) {
			$info = $xmlinfo[0];
			$model = fn_GetModel("carte", $carteid);
			$currentcarte = new $model($carteid);
			if ( $currentcarte->numero == $carteid && $currentcarte->ip == $ip ) {
				$class = $info->getName();
				$trace = fn_Trace("Carte ".$currentcarte->label . "(".$currentcarte->numero.") identifiée declenche un push ".$message, "push", $currentcarte->timezone);
				$numero = $info->attributes()->numero;
				$analyse['uniquelabel']   = true;
				$analyse['model']    = $model;
				$analyse['carteid']  = $currentcarte->numero;
				$analyse['carte']    = $currentcarte->label . "(".$currentcarte->numero.")";
				$analyse['timezone'] = $currentcarte->timezone;
				$analyse['class']    = $class;
				$analyse['no']   	 = @$numero['0'];
				$backmsg = $currentcarte->backpush($message, $class, $info->attributes()->numero);
				$backpush['backmsg'] = $backmsg;
			}
			else
				$backpush['backmsg'] = "Emetteur inconnu";
		}
		else {
			$analyse['uniquelabel']   = false;
			foreach($GLOBALS["config"]->cartes->carte as $info) {
				$model = fn_GetModel("carte", $info->attributes()->numero);
				$currentcarte = new $model($info->attributes()->numero, $info);
				if ( $currentcarte->numero == $carteid && $currentcarte->ip == $ip ) {
						$analyse['model']    = $model;
						$analyse['carteid']  = $currentcarte->numero;
						$analyse['carte']    = $currentcarte->label . "(".$currentcarte->numero.")";
						$analyse['timezone'] = $currentcarte->timezone;
						
					if ( preg_match("/^btn([0-8])$/", $xmlid, $regs) ) {
						$xpath = "//btns/btn[no='".$regs[1]."' and carteid='".$currentcarte->numero."']";
						$analyse['xpath']    = $xpath;
						$xmlinfo = $GLOBALS["config"]->xpath($xpath);
						$analyse['class']    = 'btn';
						$analyse['no']   = @$regs[1]['0'];
						if (count($xmlinfo==0)) {
							$analyse['exitbtn']   = false;
							$backpush['backmsg'] = false;
						}

						elseif ( count($xmlinfo) == 1 ) {
							$analyse['uniquebtn']   = true;
							$info = $xmlinfo[0];
							$trace = fn_Trace("Back push carte : ".$currentcarte->numero.' btn : '.$info->attributes()->numero, "push", $currentcarte->timezone);
							$backmsg=$currentcarte->backpush($message, "btn", $info->attributes()->numero);
							$backpush['backmsg'] = $backmsg;
						}
						else {
							$analyse['uniquebtn']   = false;
							$backpush['backmsg'] = false;
						}
					}
					elseif ( preg_match("/^led([0-8])$/", $xmlid, $regs) ) {
						$xpath = "//relais/relai[no='".$regs[1]."' and carteid='".$currentcarte->numero."']";
						$analyse['xpath']    = $xpath;
						$xmlinfo = $GLOBALS["config"]->xpath($xpath);
						$analyse['class']    = 'relai';
						$analyse['no']   = @$regs[1]['0'];
						if (count($xmlinfo==0)) {
							$analyse['exitrelai']   = false;
							$backpush['backmsg'] = false;
						}
						elseif ( count($xmlinfo) == 1 ) {
							$analyse['uniquerelai']   = true;
							$info = $xmlinfo[0];
							$trace = fn_Trace("Back push carte : ".$currentcarte->numero.' relai : '.$info->attributes()->numero, "push", $currentcarte->timezone);
							$backmsg=$currentcarte->backpush($message, "relai", $info->attributes()->numero);
							$backpush['backmsg'] = $backmsg;
						}
						else {
							$analyse['uniquerelai']   = false;
							$backpush['backmsg'] = false;
						}
					}
					else {
						//echo '<li>Message incomprehensible '.$message.'</li>';
						$trace = fn_Trace(ucfirst(fn_GetTranslation("incomprehensiblemessage",$message)), "push");
						$backpush['backmsg'] = false;
					}
				}
			}
		}
	}
	// Push d'un espeasy
	if ( isset($GLOBALS["config"]->espdevices) && $class=="espdevice" && $numero!="" && $value!="") {
		$analyse['class']  = $class;
		$analyse['numero'] = $numero;
		$analyse['value']  = $value;

		$espdevice = new espdevice($numero);
		$espdevice->SetEspDevice($value);
		
		/*$backmsg = $currentcarte->backpush($message, $class, $info->attributes()->numero);
					$backpush['backmsg'] = $backmsg;
				}
				else
					$backpush['backmsg'] = "Emetteur inconnu";
			}
		*/
	}
	
	/*
	if ( isset($GLOBALS["config"]->cameras) ) {
		foreach($GLOBALS["config"]->cameras->camera as $info) {
			$current = new camera($info->attributes()->numero, $info);
			if ( $current->numero == $camera || $current->host == $ip && $current->pushscenario != "" ) {
				$scenario = new scenario($current->pushscenario);
				fn_Trace("Camera ".$current->numero." declenche un push", "push");
				$scenario->run();
			}
		}
	}
	
	if ( isset($_SERVER["CONTENT_TYPE"]) && isset($HTTP_RAW_POST_DATA) ) {
		$xpath = "//*[@numero='".$_SERVER["CONTENT_TYPE"]."']";
		$xmlinfo = $GLOBALS["config"]->xpath($xpath);
		if ( count($xmlinfo) == 1 ) {
			if ( ! is_dir ('captures/'.date("Y")) )
				mkdir('captures/'.date("Y"));
			if ( ! is_dir ('captures/'.date("Y/m")) )
				mkdir('captures/'.date("Y/m"));
			if ( ! is_dir ('captures/'.date("Y/m/d")) )
				mkdir('captures/'.date("Y/m/d"));
			if ( $fp = @fopen('captures/'.date("Y/m/d").'/push_'.date("H-i-s").'.'.$xmlinfo[0]->ext, 'a') ) {
				fwrite($fp, $HTTP_RAW_POST_DATA);
				fclose($fp);
				$image   = new image();
				@image::ResizeImage('captures/'.date("Y/m/d").'/push_'.date("H-i-s").'.'.$xmlinfo[0]->ext, 'captures/'.date("Y/m/d").'/thumb/push_'.date("H-i-s").'.'.$xmlinfo[0]->ext, 0, 90);
			}
		}
	}
	*/
	if (!isset($backpush)) $backpush['backmsg'] = false;
	if (!isset($analyse))  $analyse['backmsg']  = false;
	
	$alert=false;
	
	if (($backpush['backmsg'] != false) && ($backpush['backmsg'] !="") && ($backpush['backmsg'] !="()")){
		if (isset($analyse['carteid']) &&
		isset($analyse['class']) &&
		isset($analyse['no']))  
		{		
			
			$alert = fn_Alert($analyse['carteid'], $analyse['class'], $analyse['no'], $backpush['backmsg'], $trace['traceid'], $trace['timeutc'], $trace['timezone'], $trace['microtime']);
			if (!$alert['creation']) {
				$alert['alertid'] = false;
				$alert['traceid'] = false;
			}
		}
	}
	
	if (!isset($trace))  $trace = false;
	
		$results = array(
		"entree" => $entree,
		"analyse" => $analyse,
		"backpush" => $backpush,
		"trace" => $trace,
		"alert" => $alert
	);

	echo json_encode($results);

?>