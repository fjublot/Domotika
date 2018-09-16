<?php
	if ( isset($_REQUEST["asxml"]) ) {
		header('Content-Type: application/json; charset=utf-8');
		header("Cache-Control: no-cache");
	}
	$StatusMsg="";
	$ErrorMsg="";
	$json['debug'] = (string)$GLOBALS["config"]->general->debug;

	// traitement de l'url  pour création de la commande
	fn_Trace("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],"ws");
	if ( $GLOBALS["config"]->cartes && isset($_REQUEST['relai']) )
	{
		$getAuth = fn_GetAuth($_SESSION["AuthId"], 'relai', $_REQUEST['relai']);
		$json['getauth'] = $getAuth;
		if ( $getAuth ) {
			$relai = new relai($_REQUEST['relai']);
			$model = fn_GetModel("carte", $relai->carteid);
			$current_carte = new $model($relai->carteid);
			$json['carte'] = $current_carte->label;
			fn_Trace('Clic sur relai '.$_REQUEST['relai'],"ihm");
			$json['urlcarte'] = $current_carte->geturl(false);
			list($statusHttp, $responseHttp) = fn_GetContent($current_carte->geturl()."status.xml");
			if ($responseHttp != False) {
				$xmlStatus = simplexml_load_string($responseHttp);
				$json['statushttpcarte'] = $statusHttp;
				$json['type']  = $relai->type;
				$json['xmlid'] = $relai->xmlid;
				$json['value'] = $relai->getxmlvalue($xmlStatus);
				switch ($relai->type)
				{
					Case 'I':
						if ( $relai->getxmlvalue($xmlStatus) == "0" ) {
							list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $current_carte->SetRelai($relai->no, "1");
							if ($responseHttpRelai != False) {
								if ( $relai->messageon == "" )
									$StatusMsg = fn_GetTranslation('relai_on', $current_carte->label, $relai->label);
								else
									$StatusMsg = $relai->messageon;
							}
							else
								$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
						}
						else {
							list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $current_carte->SetRelai($relai->no, "0");
							if ($responseHttpRelai != False) {
								if ( $relai->messageoff == "" )
									$StatusMsg = fn_GetTranslation('relai_off', $current_carte->label, $relai->label);
								else
									$StatusMsg = $relai->messageoff;
							} 
							else
								$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));

						}
						break;
					Case 'F':
						list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $current_carte->FurtifRelai($relai->no, 1);
						if ($responseHttpRelai != False) {					
							if ( $relai->message == "" )
								$StatusMsg = fn_GetTranslation('relai_imp', $current_carte->label, $relai->label);
							else
								$StatusMsg = $relai->message;
						}
						else
							$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
						break;					
					Case 'ALLON' :
						list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $current_carte->setallon();
						$StatusMsg = fn_GetTranslation('relai_all_on', $current_carte->label);
						break;
						
					Case 'ALLOFF':
						list($urlRelai, $statusHttpRelai, $responseHttpRelai) = $current_carte->setalloff();
						$StatusMsg = fn_GetTranslation('relai_all_off', $current_carte->label);
						break;
				}
				$StatusMsg = str_replace("%label%", $relai->label, $StatusMsg);
				if ( $relai->type == 'F' )
					$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_impulsion')." %etat%", $StatusMsg);
				if ( $relai->valeur == 1 )
					$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_up'), $StatusMsg);
				else
					$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_down'), $StatusMsg);
				$StatusMsg = str_replace("%carte%", $current_carte->label, $StatusMsg);
				if ( $relai->push && $responseHttpRelai!=false) {
					if ( $relai->push == "on" && $current_carte->pushautonome != "1" && ( $relai->pushon == "all" or ( $relai->pushon == "on" and $relai->valeur == 1 ) or ( $relai->pushon == "off" and $relai->valeur == 0 ) ) ) {
						fn_PushTo($StatusMsg, $relai->pushto);
						$StatusMsg .= ' (push)';
					}
				}
			
			}
			else { // La carte n'a pas pu être contactée
				$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
			}
		}
	}
		elseif ( $GLOBALS["config"]->cartes && isset($_REQUEST['btn']) ) {
			$getAuth = fn_GetAuth($_SESSION["AuthId"], 'btn', $_REQUEST['btn']);
			$json['getauth'] = $getAuth;
			if ( $getAuth ) {
				$btn = new btn($_REQUEST['btn']);
				$model = fn_GetModel("carte", $btn->carteid);
				$current_carte = new $model($btn->carteid);
				fn_Trace('Clic sur btn '.$_REQUEST['btn'],"ihm");
				list($statusHttp, $responseHttp) = fn_GetContent($current_carte->geturl()."status.xml");
				if ($responseHttp != False) { // La carte a pu être contactée
					$xmlStatus = simplexml_load_string($responseHttp);
					$value = $btn->getxmlvalue($xmlStatus);
					$json['value'] = $value;
					if ( $value == "0" )	{
						list($urlBtn, $statusHttpBtn, $responseHttpBtn) = $current_carte->SetBtn($btn->no);
						if ($responseHttpBtn != False) {
							if ( $btn->messageup == "" )
								$StatusMsg = fn_GetTranslation('btn_up', $current_carte->label, $btn->label);
							else
								$StatusMsg = $btn->messageup;
						}
						else { // La carte n'a pas pu être contactée
							$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
						}
					}
					else {
						list($urlBtn, $statusHttpBtn, $responseHttpBtn) = $current_carte->SetBtn($btn->no);
						if ($responseHttpBtn != False) {
							if ( $btn->messagedn == "" )
								$StatusMsg = fn_GetTranslation('btn_dn', $current_carte->label, $btn->label);
							else
								$StatusMsg = $btn->messagedn;



								}
						else { // La carte n'a pas pu être contactée
							 $ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
						}
					}

					$StatusMsg = str_replace("%label%", $btn->label, $StatusMsg);
					if ( $btn->valeur == 1 )
						$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_open'), $StatusMsg);
					else
						$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_close'), $StatusMsg);
					$StatusMsg = str_replace("%carte%", $current_carte->label, $StatusMsg);
					if ( $btn->push && $responseHttpBtn!=false) {
						if ( $btn->push == "on" && $current_carte->pushautonome != "on" && ( $btn->pushon == "all" or ( $btn->pushon == "on" and $btn->valeur == 1 ) or ( $btn->pushon == "off" and $btn->valeur == 0 ) ) ) {
							fn_PushTo($StatusMsg, $btn->pushto);
							$StatusMsg .= ' (push)';
						}
					}
				}
				else {
					$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $current_carte->label)));
				}
			}
		}
		else { // ($StatusMsg == "")
			$StatusMsg = fn_GetTranslation('no_rights');
		}

	fn_Trace('Action '.utf8_encode($StatusMsg),"ihm");
	if (isset($urlBtn))
		$json['url'] = $urlBtn;
	if (isset($urlRelai))
		$json['url'] = $urlRelai;
	if (isset($statusHttpBtn))
		$json['statushttp'] = $statusHttpBtn;
	if (isset($statusHttpRelai))
		$json['statushttp'] = $statusHttpRelai;
	$json['message'] = $StatusMsg;
	$json['error'] = $ErrorMsg;
	echo json_encode($json);
	?>