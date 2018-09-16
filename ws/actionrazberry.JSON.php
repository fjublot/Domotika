<?php
	if ( isset($_REQUEST["asxml"]) ) {
		header('Content-Type: application/json; charset=utf-8');
		header("Cache-Control: no-cache");
	}
	$StatusMsg="";
	$ErrorMsg="";
	$json['debug'] = (string)$GLOBALS["config"]->general->debug;
	
	// traitement de l'url  pour création de la commande
	fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"xml");
	if ( $GLOBALS["config"]->cartes && isset($_REQUEST['razdevice']) )
	{
		$getAuth = fn_GetAuth($_SESSION["AuthId"], 'razdevice', $_REQUEST['razdevice']);
		$json['getauth'] = $getAuth;
		if ( $getAuth ) {
			$razdevice = new razdevice($_REQUEST['razdevice']);
			$model = fn_GetModel("carte", $razdevice->carteid);
			$current_carte = new $model($razdevice->carteid);
			$json['carte'] = $current_carte->label;
			$json['urlcarte'] = $current_carte->geturl(false);
			list($errorStatus, $messageStatus) = $current_carte->get_status();
			$json['errorStatus'] = $errorStatus;
			$json['messageStatus'] = $messageStatus;
			$json['type']  = $razdevice->type;
			$json['deviceid']  = $razdevice->deviceid;
			$json['value'] = $razdevice->getxmlvalue($current_carte->status);
			fn_Trace('Clic sur razdevice '.$_REQUEST['razdevice'],"ihm");
			switch ($razdevice->type) {
				Case 'I':
					if ( $razdevice->getxmlvalue($current_carte->status) == 0 ) { // le device est éteint il faut l'allumer
						list($urlRazdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $current_carte->SetRelai($razdevice->numero, 1);
					}
					else {
						list($urlRazdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $current_carte->SetRelai($razdevice->numero, 0);
					}
					break;
				Case 'F':
					list($urlRazdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $current_carte->FurtifRelai($razdevice->numero, 1);
					break;					
				Case 'ALLON' :
					list($urlRazdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $current_carte->setallon();
					break;
					
				Case 'ALLOFF':
					list($urlRazdevice, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg) = $current_carte->setalloff();
					break;
			}

			$json['urlRazdevice'] = $urlRazdevice;
			$json['responseHttp'] = $responseHttp;

			
			
		}
		else { // La carte n'a pas pu être contactée
			$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
		}
	}
	

	fn_Trace('Action '.utf8_encode($StatusMsg),"ihm");
	$json['message'] = $StatusMsg;	
	$json['error'] = $ErrorMsg;

	echo json_encode($json);		
?>