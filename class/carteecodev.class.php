<?php
/*----------------------------------------------------------------*
* Titre : CarteEcodev.php                                        *
* Classe de carte                                                 *
*-----------------------------------------------------------------*/
class carteecodev extends carte
{
	public $portm2m, $user, $password, $status;
	public function __construct($numero="", $info = null)
	{
        $this->config_class = "carte";
        parent::__construct($numero, $info);
		$this->subclass = array("cnt", "variable", "vartxt");
		$this->socket = false;
		return $this;
	}
	public function __destruct()
	{
		if ( $this->socket !== false )
        socket_close($this->socket);
		$this->socket = false;
		parent::__destruct();
	}
	public function form($page=null)
	{
		$return  = parent::form();
		$return .= fn_HtmlInputField('portm2m', $this->portm2m, 'text', 'port_m2m', 'carte.portm2m', '', false);
		$return .= fn_HtmlInputField('user', $this->user, 'text', 'compte', 'carte.user', "", false);
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
						\'#portm2m\' : {
							validation : \'number\',
							allowing : \'range[1;16385]\'
						},
					}
				};
		'.PHP_EOL;

		return $return;
	}
	public function receive_form($list_data = array())
	{
		list($status, $message) = parent::receive_form(array("portm2m", "user", "password"));
		$this->update();
		return array($status, $message);
	}
	public function required_field()
	{
		return array_key_exists("user", $_POST);
	}
	public function save($list_data = array())
	{
		$to_save = array("portm2m", "user", "password");
		$new = false;
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		$return = parent::save($to_save);
		/*
		if ( $this->active == "on" )
		{
			$this->get_status();
			if ( $this->status === false )
			{
				$this->del();
				return fn_GetTranslation('pb_acces_carte');
			}
			if ( $new )
			{
                for ($i = 0; $i<=1; $i++) // on traite les 2 compteurs de l ecodev
                {
                    $class = "cnt";
                    $xmlid='count'.$i;
                    $status = $this->status->xpath("//".$xmlid);
                    $current = new $class();
                    $current->update();
                    $current->no = $i;
                    $current->carteid = $this->numero;
                    $current->xmlid = $xmlid;

                    $xpathModele='//username'.($i+3);
                    $status = $this->status->xpath($xpathModele);
                    $lab=$status[0];
                    $current->label = 'Compteur '.$lab." C".$this->numero;
                    
                    $xpathModele='//c'.($i+1).'_eau';
                    $status = $this->status->xpath($xpathModele);
                    if ( $status[0] == 'selected' )
                    {
                        $current->unite = 'm3';
                        $xpathModele="//c".($i+1).'_unite';
                        $status1 = $this->status->xpath($xpathModele);
                        $xpathModele="//c".($i+1).'_impulsion';
                        $status2 = $this->status->xpath($xpathModele);
                        $current->formule = '$cnt*0.001*'.$status1[0].'/'.$status2[0];
                    }
                    $xpathModele='//c'.($i+1).'_gaz';
                    $status = $this->status->xpath($xpathModele);
                    if ( $status[0] == 'selected' )
                    {
                        $current->unite = 'm3';
                        $xpathModele="//c".($i+1).'_unite';
                        $status1 = $this->status->xpath($xpathModele);
                        $xpathModele="//c".($i+1).'_impulsion';
                        $status2 = $this->status->xpath($xpathModele);
                        $current->formule = '$cnt*0.001*'.$status1[0].'/'.$status2[0];
                    }
                    $xpathModele='//c'.($i+1).'_elec';
                    $status = $this->status->xpath($xpathModele);
                    if ( $status[0] == 'selected' )
                    {
                        $current->unite = 'kwatt-heure';
                        $xpathModele="//c".($i+1).'_unite';
                        $status1 = $this->status->xpath($xpathModele);
                        $xpathModele="//c".($i+1).'_impulsion';
                        $status2 = $this->status->xpath($xpathModele);
                        $current->formule = '$cnt*0.001*'.$status1[0].'/'.$status2[0];
                    }
                    $xpathModele='//c'.($i+1).'_fuel';
                    $status = $this->status->xpath($xpathModele);
                    if ( $status[0] == 'selected' )
                    {
                        $current->unite = 'litre';
                        $xpathModele="//c".($i+1).'_debit_fuel';
                        $status = $this->status->xpath($xpathModele);
                        $current->formule = '$cnt*'.$status[0];
                    }
                    $return .= "<br>\n".$current->save();
                    fn_InitAuthAllUser($class, $current->numero);
                }
                
                // on traite la teleinfoEDF
                
                $l_objet = array("cnt" => array("_BASE" => 'compteur_BASE', "_HCHC" => 'compteur_HC', "_HCHP" => 'compteur_HP', "_BBRHCJB" => 'compteur_HCB', "_BBRHPJB" => 'compteur_HPB', "_BBRHCJW" => 'compteur_HCW', "_BBRHPJW" => 'compteur_HPW', "_BBRHCJR" => 'compteur_HCR', "_BBRHPJR" => 'compteur_HPR', "_EJPHN" => 'compteur_EJPHN', "_EJPHPM" => 'compteur_EJPHPM'), "variable" => array("_IINST" => 'intensite_inst',"_IINST1" => 'intensite_inst1', "_IINST2" => 'intensite_inst2', "_IINST3" => 'intensite_inst3', "_PPAP" => 'puissance_apparente'), "vartxt" => array("_OPTARIF" => 'option_tarif', "_DEMAIN" => 'couleur_demain', "_PTEC" => 'tarif_en_cours'));
                
                $l_unit = array("_BASE" => 'kwatt-heure', "_HCHC" => 'kwatt-heure', "_HCHP" => 'kwatt-heure', "_BBRHCJB" => 'kwatt-heure', "_BBRHPJB" => 'kwatt-heure', "_BBRHCJW" => 'kwatt-heure', "_BBRHPJW" => 'kwatt-heure', "_BBRHCJR" => 'kwatt-heure', "_BBRHPJR" => 'kwatt-heure', "_EJPHN" => 'kwatt-heure', "_EJPHPM" => 'kwatt-heure', "_IINST" => 'ampere', "_IINST1" => 'ampere', "_IINST2" => 'ampere', "_IINST3" => 'ampere', "_PPAP" => 'watt');
                
                $l_formule = array("_BASE" => '$cnt/1000', "_HCHC" => '$cnt/1000', "_HCHP" => '$cnt/1000', "_BBRHCJB" => '$cnt/1000', "_BBRHPJB" => '$cnt/1000', "_BBRHCJW" => '$cnt/1000', "_BBRHPJW" => '$cnt/1000', "_BBRHCJR" => '$cnt/1000', "_BBRHPJR" => '$cnt/1000', "_EJPHN" => '$cnt/1000', "_EJPHPM" => '$cnt/1000');
                
                
                for ($count = 1; $count <= 2; $count++)
                {
                    foreach( $l_objet as $class => $id_objets)
                    {
                        foreach( $id_objets as $id_objet => $label)
                        {
                            $current_id_objet = 'T'.$count.$id_objet;
                            $xpathModele = '//'.$current_id_objet;
                            $nodes = $this->status->xpath($xpathModele);
                            if ( count($nodes) != 0 )
                            {
                                foreach ($nodes as $item)
                                {
                                    $current = new $class();
                                    $current->update();
                                    $current->label = fn_GetTranslation($label, $count);
                                    $current->xmlid = $current_id_objet;
                                    $current->carteid = $this->numero;
                                    if ( isset($l_unit{$id_objet}) )
                                        $current->unite = $l_unit{$id_objet};
                                    if ( isset($l_formule{$id_objet}) )
                                        $current->formule = $l_formule{$id_objet};
                                    $current->no = "";
                                    $return .= "<br>\n".$current->save();
                                    fn_InitAuthAllUser($class, $current->numero);
                                }
                            }
                        }
                    }
                }
			}
		}
		
		$xpath = "//*[carteid='".$this->numero."']";
		$nodes = $GLOBALS["config"]->xpath($xpath);
		if ( count($nodes) != 0 )
		{
			foreach ($nodes as $item)
			{
				$class = $item->getName();
				$current = new $class((string)$item->attributes()->numero, $item);
				if ( isset($current->active) && $current->active != $this->active )
				{
					$current->active = $this->active;
					$current->save();
				}
			}
		}
		*/
		return $return;
	}
	public function geturl($withpassword = true)
	{
		$url = 'http://';
		if ( $this->user != '' && $withpassword == true)
		{
			$url .= $this->user.':'.$this->password.'@';
		}
		$url .= $this->ip;
		if ( $this->port != '' )
		{
			$url .= ':'.$this->port;
		}
		return $url."/";
	}
	public function send_commande($commande)
	{
		if ( $this->socket == false )
		{
			$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if ($this->socket == false)
				return fn_GetTranslation('cant_create_socket', socket_strerror(socket_last_error($this->socket)));
			$result = socket_connect($this->socket, $this->ip, (int) $this->portm2m);
			if ($result == false)
				return fn_GetTranslation('cant_connect', socket_strerror(socket_last_error($this->socket)));
		}
		//fn_Trace("send : ".$commande." to ".$this->label, "M2M");
		socket_write($this->socket, $commande, strlen($commande));
		$result = socket_read($this->socket, 64);
		$result = trim($result);
		while ( $result == "" and $result !== false )
		{
			$result = socket_read($this->socket, 64);
			$result = trim($result);
		}
		//fn_Trace("receive : ".$result." from ".$this->label, "M2M");
		return $result;
	}
    
    public function get_status() {	
		$errorStatus= 0;
		$message="";
		$this->status = false;
        if ( $this->active != 'on' ) { // La carte n'est pas active
			$errorStatus++;
			$message = fn_GetTranslation('desactivation_carte', $this->label);
		}
		else { // La carte est active
			if ( $this->portm2m == "" ) { // Si mode HTTP
				$url = $this->geturl()."globalstatus.xml";
				list($statusHttp, $responseHttp) = fn_GetContent($url);
				if ($responseHttp != false) { // La carte a pu être contactée
					$this->status = simplexml_load_string($responseHttp);
					if ( $this->status == false ) {
						$errorStatus++;
						$message = ucfirst(fn_GetTranslation('treatmenterror'));
					}
					else {
						$this->update_time = time();
						$message = ucfirst(fn_GetTranslation('done'));
					}
					/*
					else
					$status = $this->status->xpath('//'.$this->xmlid);
					if ( count($status) != 0 ) {
						$url = $this->geturl()."globalstatus.xml";
						list($statusHttp, $responseHttp) = fn_GetContent($url);
						if ($responseHttp != False) {
							$this->status = simplexml_load_string($responseHttp);
							if ( $this->status == false )
								$errorStatus++;
						}
					}
					else
						return false;
					*/

				}
				else {
					$errorStatus++; // Erreur sur chargement du status.xml
					$message = ucfirst(fn_GetTranslation('error_communication', $this->label));
				}
			}
			else { // si Mode M2M
				$this->status = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
				$this->update_time = time();
				$result = $this->send_commande("GetT102");
				if ( (substr($result, 0, 1) == "?") || (substr($result, 0, 7) != "GetT102") ) {
					$this->portm2m = "";
					//return $this->get_status();
				}

				$l_objet = array("_BASE" => '04', "_HCHC" => '05', "_HCHP" => '06', "_BBRHCJB" => '09', "_BBRHPJB" => '10', "_BBRHCJW" => '11', "_BBRHPJW" => '12', "_BBRHCJR" => '13', "_BBRHPJR" => '14', "_EJPHN" => '07', "_EJPHPM" => '08', "_IINST" => '18',"_IINST1" => '19', "_IINST2" => '20', "_IINST3" => '21', "_PPAP" => '27', "_OPTARIF" => '02', "_DEMAIN" => '17', "_PTEC" => '16');
				
				for ($i = 1; $i <= 2; $i++) {
					foreach ( $l_objet as $id => $cmd) {
						$xmlid = 'T'.$i.$id;
						$m2mcmd = 'GetT'.$i.$cmd;
						$result = $this->send_commande($m2mcmd);
						list($cmd, $value) = explode("=", $result);
						$this->status->addChild($xmlid, $value);
					}
				}
				
				for ($count = 0; $count <= 1; $count++) {
					$result = $this->send_commande("GetCount".($count+1));
					list($cmd, $value) = explode("=", $result);
					$this->status->addChild('count'.$count, $value);
				}
				
			}
		}
		return array($errorStatus, $message);
	}

	public function backpush($message, $class, $numero)
	{
        //
    }

	public function update()
	{
		if ( ! isset($this->active) )
			$this->active = "1";
		if ( isset($this->version) )
			unset($this->version);
		parent::update();
	}

    public function config_push()
	{
		// echo fn_GetTranslation('config_push_done');
    }
}
?>