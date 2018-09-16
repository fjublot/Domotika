<?php
/*----------------------------------------------------------------*
* Titre : razberry.php                                               *
* Classe de carte                                                 *
*-----------------------------------------------------------------*/
class carterazberry extends carte
{
	public $user, $password;
	public $status; // Variable pour connaitre l'état de la carte lors de sa creation/modification
	public $createsubclass; // Variable booleen pour autoriser la création des sous objets (relais, ans, cnts, btns)

	public function __construct($numero="", $info = null) {
		$this->config_class = "carte";
		parent::__construct($numero, $info);
		$this->subclass = array("razdevice");
		return $this;
	}

	public function save($list_data = array()) {
		$to_save = array("user", "password");
		$new = false;
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		$return = parent::save($to_save);
		/*
		$xpath = "//*[carteid='".$this->numero."']";
		$nodes = $GLOBALS["config"]->xpath($xpath);
		if ( count($nodes) != 0 ) {
			foreach ($nodes as $item) {
				$class = $item->getName();
				$current = new $class((string)$item->attributes()->numero, $item);
				if ( isset($current->active) && $current->active != $this->active ) {
					$current->active = $this->active;
					$current->save();
				}
			}
		}
		*/
		return $return;
	}
	
		public function advance_form()
	{
		$return  = fn_HtmlInputField('user', $this->user, 'text', 'compte', 'carte.user', "", false);
 		$return .= fn_HtmlInputField('password', $this->password, 'text','password', 'carte.password', '');
		return $return;
	}

	public function js()
	{
 		$return = parent::js();
				$return .='/* Règles de validation */
				var configvalidate = {
					// ordinary validation config
					form : \'#ajaxform\',
					// reference to elements and the validation rules that should be applied
					validate : {
						\'#label\' : {
						    validation : \'length\',
							length : \'1-20\'
						},
						\'#port\' : {
							validation : \'number\',
							allowing : \'range[1;16385]\'
						},
					}
				};
		';
		return $return;
	}
	
	public function receive_form($list_data = array()) {
		list($status, $message) = parent::receive_form(array("user", "password"));
		$this->update();
		return array($status, $message);
	}

	public function setalloff() {
			fn_Trace("Carte ".$this->numero." : set all off", "carte", $this->timezone);
			$url = $this->geturl().'preset.htm?led1=0&led2=0&led3=0&led4=0&led5=0&led6=0&led7=0&led8=0';
			fn_Trace($url, "carte", $this->timezone);
			return file($url);
	}
	
	public function setallon() {
			fn_Trace("Carte ".$this->numero." : set all on", "carte", $this->timezone);
			$url = $this->geturl().'preset.htm?led1=1&led2=1&led3=1&led4=1&led5=1&led6=1&led7=1&led8=1';
			fn_Trace($url, "carte", $this->timezone);
			return file($url);
	}
	
	public function SetRelai($razdevice_id, $value) {
		//$url = $this->geturl().'ZWaveAPI/Run/devices['.$razdevice_id.'].Basic.Set('.$value.')';
		//$url = $this->geturl().'ZAutomation/api/v1/devices/'.$razdevice->deviceid.'/command/on';
		//$url = $this->geturl().'ZAutomation/api/v1/devices/'.$razdevice->deviceid.'/command/off';

		$StatusMsg="";
		$ErrorMsg="";
		$razdevice = new razdevice($razdevice_id);
		if ($value==1) 
			$value=99;
			$url = $this->geturl().'ZWaveAPI/Run/devices['.$razdevice->deviceid.'].Basic.Set('.$value.')';
		
		fn_Trace($url, "carte", $this->timezone);
		
		list($statusHttp, $responseHttp) = fn_GetContent($url, $this->user, $this->password);
		if ( $value > 0 ) { 
			if ($responseHttp != False) {
				if ( $razdevice->messageon == "" )
					$StatusMsg = fn_GetTranslation('relai_on', $this->label, $razdevice->label);
				else
					$StatusMsg = $razdevice->messageon;
			}
			else
				$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $this->label)));
		}
		else {
			if ($responseHttp != False) {
				if ( $razdevice->messageoff == "" )
					$StatusMsg = fn_GetTranslation('relai_off', $this->label, $razdevice->label);
				else
					$StatusMsg = $razdevice->messageoff;
			} 
			else
				$ErrorMsg = htmlspecialchars(ucfirst(fn_GetTranslation('error_communication', $this->label)));

		}
	

		$StatusMsg = str_replace("%label%", $razdevice->label, $StatusMsg);
		if ( $value != 0 )
			$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_down'), $StatusMsg);
		else
			$StatusMsg = str_replace("%etat%", fn_GetTranslation('etat_up'), $StatusMsg);
		
		$StatusMsg = str_replace("%carte%", $this->label, $StatusMsg);

		if ( $razdevice->push && $responseHttp!=false) {
				if ( $razdevice->push == "on"  && ( $razdevice->pushon == "all" or ( $razdevice->pushon == "on" and $razdevice->valeur != 0 ) or ( $razdevice->pushon == "off" and $razdevice->valeur == 0 ) ) ) {
					fn_PushTo($StatusMsg, $razdevice->pushto);
					$StatusMsg .= ' (push)';
				}
		}
		return array($url, $statusHttp, $responseHttp, $StatusMsg, $ErrorMsg);	
	}

	public function get_status() {
		$this->status = false;
		$errorStatus= 0;
		$message="";
		
		if ( $this->active != 'on' ) { // La carte n'est pas active
			$errorStatus++;
			$message = fn_GetTranslation('desactivation_carte', $this->label);
		}
		else { // La carte est active
			$old_error_niveau = error_reporting(E_ERROR);
			$url = $this->geturl()."ZAutomation/api/v1/devices";
			list($statusHttp, $responseHttp) = fn_GetContent($url, $this->user, $this->password);
					if ($responseHttp != false) { // La carte a pu être contactée
						$json = @json_decode($responseHttp, false);
						$json2xml = new json2xml();
						$xmlStatus = $json2xml->export($json);
						$this->status = simplexml_load_string($xmlStatus);
						if ( $this->status == false ) {
							$errorStatus++;
							$message = ucfirst(fn_GetTranslation('treatmenterror'));
						}
						else {
							$this->update_time = time();
							$message = ucfirst(fn_GetTranslation('done'));
						}
					}
					else {
						$errorStatus++; // Erreur sur chargement du status.xml
						$message = ucfirst(fn_GetTranslation('error_communication', $this->label));
					}
			error_reporting($old_error_niveau);
			$this->update_time = time();
		}
		return array($errorStatus, $message);
	}
}
?>