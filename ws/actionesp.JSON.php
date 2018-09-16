<?php
	if ( isset($_REQUEST["asxml"]) ) {
		header('Content-Type: application/json; charset=utf-8');
		header("Cache-Control: no-cache");
	}
	$StatusMsg="";
	$ErrorMsg="";
	$json['debug'] = (string)$GLOBALS["config"]->general->debug;
	
	// traitement de l'url  pour création de la commande
	if ( $espdevice !="")  {
		$getAuth = fn_GetAuth($_SESSION["AuthId"], 'espdevice', $espdevice);
		$json['getauth'] = $getAuth;
		if ( $getAuth ) { //Si habilité
			$currentespdevice = new espdevice($espdevice);
			$json['value'] = $currentespdevice->GetState();
			fn_Trace('Clic sur espdevice '.$espdevice,"ihm");
			if ( $json['value'] == 0 ) { // le device est éteint il faut l'allumer
				list($urlEspdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $currentespdevice->SetOn();
			} else {
				list($urlEspdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $currentespdevice->SetOff();
			}
			$json['urlEspdevice'] = $urlEspdevice;
			$json['responseHttp'] = $responseHttp;
			if ($responseHttp == false)
				$json['error'] = $StatusMsg;
			else
				$json['message'] = $StatusMsg;	
		}
		else { // La carte n'a pas pu être contactée
			$json['error'] = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));

		}
	}
	

	fn_Trace('Action '.utf8_encode($StatusMsg),"ihm");
	echo json_encode($json);		
?>