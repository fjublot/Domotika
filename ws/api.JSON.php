<?php
	header("Content-type: application/json; charset=utf-8");
	fn_trace('sms : http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] , "sms");
	$json['url'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];		
	if (( isset($_REQUEST["gsm"])) && isset($_REQUEST["sms"])) {
		$json['gsm'] = $gsm;
		$json['sms'] = $sms;
	}

	if ( isset($GLOBALS["config"]->cartes) && isset($_REQUEST["sms"])) { 
		preg_match ("/commande\[(.*?)]/i", $sms, $commandes);
		$commandesms="";
		if (count($commandes)>=2) {
			$commandesms = $commandes[1];
		}
		else {
		$commandesms = "";
		}
		
		$json['commandesms'] = $commandesms;
		$json['apikey'] = $apikey;
		
		//Recherche de l'utilisateur dans le fichier de conf
		//Cas d'un sms
		if ( $gsm != "") {
			$xpath = "//users/user[telmobile='".str_replace(' ','+',$gsm)."']";
			$json['xpath'] = $xpath;
			$ListUser = $GLOBALS["config"]->xpath($xpath);
			// Si  l'utilisateur a été trouvé
			if ( count($ListUser) == 1 ) {
				foreach($ListUser as $user) {
					// Si le compte n'est pas actif
					if ( $user->actif != 'on' ) {
						fn_trace((string)$user->label.' compte non actif', "acces");
						$json['message'] = fn_GetTranslation('compte_non_actif');
						pushto(fn_GetTranslation('compte_non_actif'),$user->pushto,'',false);
					}
					else {
						// Si le compte est actif
						$_SESSION['privilege'] = (int)$user->privilege;
						$_SESSION["LoginConn"] = (string)$user->label;
						$_SESSION["AuthId"] = (string)$user->attributes()->numero;
						$_SESSION["timezone"] = (string)$user->timezone;
						$currentuser = new user($user->attributes()->numero);
						date_default_timezone_set($_SESSION["timezone"]);
						fn_trace((string)$user->label.' connecte (sms)', "acces");
						$json['connect'] = fn_GetTranslation('push_good_connexion',$_SESSION["LoginConn"],$_SESSION["AuthId"]);
						eval($commandesms);
						
					}
				}
			}
		}
	}	
		else {
				$json['gsm'] =  'inconnu';
		}
		
		// Cas d'une commande par api (class, numero, command)
		if ( $apikey != "") {
		
				$xpath = "//users/user[apikey='".$apikey."']";
				$json['xpath'] = $xpath;
				$ListUser = $GLOBALS["config"]->xpath($xpath);
				// Si  l'utilisateur a été trouvé
				if ( count($ListUser) == 1 ) {
					foreach($ListUser as $user) {
						// Si le compte n'est pas actif
						if ( $user->actif != 'on' ) {
							fn_trace((string)$user->label.' compte non actif', "acces");
							$json['error'] = fn_GetTranslation('compte_non_actif');;							pushto(fn_GetTranslation('compte_non_actif'),$user->pushto,'',false);
						}
						else { // Si le compte est actif
							$_SESSION['privilege'] = (int)$user->privilege;
							$_SESSION["LoginConn"] = (string)$user->label;
							$_SESSION["AuthId"] = (string)$user->attributes()->numero;
							$_SESSION["timezone"] = (string)$user->timezone;
							$currentuser = new user($user->attributes()->numero);
							date_default_timezone_set($_SESSION["timezone"]);
							fn_trace((string)$user->label.' connecte (apikey)', "acces");
							$json['connect'] = fn_GetTranslation('push_good_connexion',$_SESSION["LoginConn"],$_SESSION["AuthId"]);
							$json['class'] = $class;
							$json['numero'] = $numero;
							$element = new $class($numero);
							if (property_exists($class, 'carteid'))
								$json['carte'] = $element->carteid;
							$json['command'] = $command;
							switch ($command)
							{
								Case 'SetOn':
									list($json['value'], $json['message'], $json['error'], $json['urlRelai'], $json['statusHttp']) = $element->SetOn();
									$json['label'] = $element->label;
									
									break;
								Case 'SetOff':
									list($json['value'], $json['message'], $json['error'], $json['urlRelai'], $json['statusHttp']) = $element->SetOff();
									$json['label'] = $element->label;
									break;
								Case 'Switch':
									list($json['value'], $json['message'], $json['error'], $json['urlRelai'], $json['statusHttp']) = $element->SetSwitch();
									$json['label'] = $element->label;
									break;
								Case 'GetState':
									$json['value'] = $element->GetState();
									$json['label'] = $element->label;
									break;
								Default:
									$json['value'] = 'NaN';
									break;
							}
						}
					
					}				
				}					
		}
	echo json_encode($json);
	?>