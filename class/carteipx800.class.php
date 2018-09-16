<?php
/*----------------------------------------------------------------*
* Titre : ipx800.php                                               *
* Classe de carte                                                 *
*-----------------------------------------------------------------*/
class carteipx800 extends carte
{
	public $portm2m, $user, $password, $nb_extension, $pushautonome;
	public $status; // Variable pour connaitre l'état de la carte lors de sa creation/modification
	public $createsubclass; // Variable booleen pour autoriser la création des sous objets (relais, ans, cnts, btns)

	public function __construct($numero="", $info = null)
	{
		$this->config_class = "carte";
		parent::__construct($numero, $info);
		$this->subclass = array("relai", "btn", "cnt", "an");
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
		$return .= fn_HtmlInputField('portm2m', $this->portm2m, 'text', 'port_m2m', 'carte.portm2m', "", false);
		$return .= fn_HtmlInputField('user', $this->user, 'text', 'compte', 'carte.user', "", false);
 		$return .= fn_HtmlInputField('password', $this->password, 'text','password', 'carte.password', '');
		$return .= fn_HtmlInputField('nb_extension', $this->nb_extension, 'text', 'nb_extension', 'carte.nb_extension', "", false);
		$return .= fn_HtmlBinarySelectField('pushautonome', $this->pushautonome, 'carte_autonome', 'carte.pushautonome');
		$return .= fn_HtmlBinarySelectField('createsubclass', $this->createsubclass, 'carte_createsubclass', 'carte.createsubclass');
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
						\'#nb_extension\' : {
							validation : \'number\',
							allowing : \'range[0;4]\'
						},
					}
				};
		'.PHP_EOL;
						/*
						\'#portm2m\' : {
							validation : \'number\',
							allowing : \'range[0;16385]\'
						},
						*/
		$html  = '<button class="actionbutton btn btn-primary col-sm-9 col-xs-12 pull-right" type="submit" id="BT_ConfigCarte" name="BT_ConfigCarte">'.fn_GetTranslation('send_config_carte').'</button>';
		$return .='$(\'#input-btns\').append(\''.$html.'\');'.PHP_EOL;
		//onclick="jQuery(\"#action\").val(this.id);"
		return $return;
	}
	public function receive_form($list_data = array())
	{
		if ( isset($_POST["nb_extension"]) && $_POST["nb_extension"] > 3 )
		{
			$status =1;
			$message = fn_GetTranslation('nb_extension_max');
		}
		list($status, $message) = parent::receive_form(array("portm2m", "user", "password", "nb_extension", "pushautonome", "createsubclass"));
		$this->update();
		return array($status, $message);
	}
	public function required_field()
	{
		return array_key_exists("nb_extension", $_POST);
	}
	public function save($list_data = array()) {
		$to_save = array("portm2m", "user", "password", "nb_extension", "pushautonome");
		$new = false;
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		$return = parent::save($to_save);
		
		if ($this->createsubclass == "on") {
			if ( $this->active == "on" ) {
				$this->get_status();
				if ( $this->status === false ) {
					$result[] = array('class' => $this->config_class, 'numero' => $this->numero, 'reload' => false, 'message' => fn_GetTranslation('pb_acces_carte'));
					$return = $result;
				}
				else {
					// Recherche ou commence l'indice count
					$xpathModele='//count0';
					$status = $this->status->xpath($xpathModele);
					if ( count($status) == 0 )
						$count_flag_indice = 1;
					else
						$count_flag_indice = 0;
					
					foreach($this->subclass as $class) {
						$i = 0;
						do {
							$find = false;
							$xmlid = $class.$i;
							if ( $class == "cnt" ) {
								if ( $count_flag_indice == 1 )
									$xmlid='count'.($i+1);
								else
									$xmlid='count'.$i;
							}
							elseif ( $class == "relai" ) {
								$xmlid='led'.$i;
								if ( $i >= ($this->nb_extension+1) * 8 ) {
									break;
								}
							}
							elseif ( $class == "btn" ) {
								if ( $i >= ($this->nb_extension+1) * 8 ) {
									break;
								}
							}
							elseif ( $class == "an" ) {
								$xmlid='an'.($i+1);
							}
							$status = $this->status->xpath("//".$xmlid);
							if ( count($status) == 0 && $class == "an" ) {
								$xmlid = 'analog'.$i;
								$status = $this->status->xpath("//".$xmlid);
							}
							if ( count($status) != 0 ) {
								$current = new $class();
								$current->update();
								$current->no = $i;
								$current->carteid = $this->numero;
								$current->xmlid = $xmlid;
								if ( $class == "an" ) {
									$current->label = 'Analog C'.$this->numero."A".$current->no;
								}
								elseif ( $class == "cnt" ) {
									$current->label = 'Compteur C'.$this->numero."C".$current->no;
								}
								elseif ( $class == "relai" ) {
									$current->label = 'Relais C'.$this->numero."R".$current->no;
									$current->xmlid = 'led'.($current->no+1);
								}
								elseif ( $class == "btn" ) {
									$current->label = 'Etat C'.$this->numero."B".$current->no;
									$current->xmlid = 'btn'.($current->no+1);
								}
								$return .= "<br>\n".$current->save();
								fn_InitAuthAllUser($class, $current->numero);
								$find = true;
								$i++;
							}
						} while($find);
					}
				}
			}
		}
		
    /*foreach($this->subclass as $class)
		{
			for ($i = ($this->nb_extension+1) * 8+1; $i < 5 * 8; $i++)
			{
				$current = new $class($i);
				$current->del();
			}
		}
    */
    
		// Suppression des objets supérieurs au indices max autorisés par la carte et désactivation des objets liés à une carte désactivée
		$xpath = "//*[carteid='".$this->numero."']";
		$nodes = $GLOBALS["config"]->xpath($xpath);
		if ( count($nodes) != 0 ) {
			foreach ($nodes as $item) {
				$class = $item->getName();
				$current = new $class((string)$item->attributes()->numero, $item);
				if ( $current->no >= (($this->nb_extension+1) * 8) ) {
					$current->del();
				}
				/*
				if ( isset($current->active) && $current->active != $this->active ) {
					$current->active = $this->active;
					$current->save();
				}
				*/
				
			}
		}

    
		return $return;
	}

	public function geturl($withpassword = true)
	{
		$url = 'http://';
/*		if ( $this->user != '' && $withpassword == true)
		{
			$url .= $this->user.':'.$this->password.'@';
		}
*/
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
	public function setalloff()
	{
		if ( $this->portm2m == "" )
		{
			fn_Trace("Carte ".$this->numero." : set all off", "carte", $this->timezone);
			$url = $this->geturl().'preset.htm?led1=0&amp;led2=0&amp;led3=0&amp;led4=0&amp;led5=0&amp;led6=0&amp;led7=0&amp;led8=0';
			$urlSecure = $this->geturl(false).'preset.htm?led1=0&amp;led2=0&amp;led3=0&amp;led4=0&amp;led5=0&amp;led6=0&amp;led7=0&amp;led8=0';
			fn_Trace($url, "carte", $this->timezone);
			list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
			return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
		}
		else
		{
			return $this->send_commande("Bit=00000000");
		}
	}
	public function setallon()
	{
		if ( $this->portm2m == "" )
		{
			fn_Trace("Carte ".$this->numero." : set all on", "carte", $this->timezone);
			$url = $this->geturl().'preset.htm?led1=1&amp;led2=1&amp;led3=1&amp;led4=1&amp;led5=1&amp;led6=1&amp;led7=1&amp;led8=1';
			$urlSecure = $this->geturl(false).'preset.htm?led1=1&amp;led2=1&amp;led3=1&amp;led4=1&amp;led5=1&amp;led6=1&amp;led7=1&amp;led8=1';
			fn_Trace($url, "carte", $this->timezone);
			list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
			return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
		}
		else
		{
			return $this->send_commande("Bit=11111111");
		}
	}
	public function SetRelai($relai_id, $value)
	{
		$relai_id=$relai_id+1;
		fn_Trace("Carte ".$this->numero." : set relai ".$relai_id." ".$value, "carte", $this->timezone);
		if ( $this->portm2m == "" ) {
			if ($relai_id > 7) {
				$url = $this->geturl().'preset.htm?set'.$relai_id.'='.$value;
				$urlSecure = $this->geturl(false).'preset.htm?set'.$relai_id.'='.$value;
			}
			else {
				$url = $this->geturl().'preset.htm?led'.$relai_id.'='.$value;
				$urlSecure = $this->geturl(false).'preset.htm?led'.$relai_id.'='.$value;
			}
			fn_Trace($url, "carte", $this->timezone);
			list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
			return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
		}
		else
		{
			if ( $relai_id < 10 )
				$relai_id = "0".$relai_id;
			return $this->send_commande("Set".$relai_id.$value);
		}
	}

	public function SetBtn($btn_id)
	{
		$btn_id=($btn_id)+100;
		fn_Trace("Carte ".$this->numero." : set btn ".$btn_id /*." ".$value*/, "carte", $this->timezone);
		$url = $this->geturl().'leds.cgi?led='.$btn_id;
		$urlSecure = $this->geturl(false).'leds.cgi?led='.$btn_id;
		fn_Trace($urlSecure, "carte", $this->timezone);
		list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
		return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
	}
	
	public function set_relai_tab($relai_id, $ta, $tb, $label = "")
	{
		$relai_id=$relai_id+1;
		fn_Trace("Carte ".$this->numero." : set relai ".$relai_id." ta=".$ta." tb=".$tb, "carte", $this->timezone);
		$url = $this->geturl().'protect/settings/output1.htm?output='.$relai_id.'&amp;delayon='.$ta.'&amp;delayoff='.$tb."&amp;relayname=".urlencode($label);
		$urlSecure = $this->geturl(false).'protect/settings/output1.htm?output='.$relai_id.'&amp;delayon='.$ta.'&amp;delayoff='.$tb."&amp;relayname=".urlencode($label);
		fn_Trace($url, "carte", $this->timezone);
		/*$old_error_niveau = error_reporting(E_PARSE);
		error_reporting($old_error_niveau);*/
		list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
		return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
	}
	public function FurtifRelai($relai_id, $value)
	{
		$relai_id=$relai_id+1;
		if ( $this->portm2m == "" )
		{
			fn_Trace("Carte ".$this->numero." : furtif relai ".$relai_id." ".$value, "carte", $this->timezone);
			$url = $this->geturl().'preset.htm?RLY'.$relai_id.'='.$value;
			$urlSecure = $this->geturl(false).'preset.htm?RLY'.$relai_id.'='.$value;
			fn_Trace($url, "carte", $this->timezone);
			list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
			return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
		}
		else
		{
			if ( $relai_id < 10 )
				$relai_id = "0".$relai_id;
			return $this->send_commande("Set".$relai_id.$value."p");
		}
	}
	public function SetCnt($cnt_id, $value = 0)
	{
		if ( $this->portm2m == "" )
		{
			fn_Trace("Carte ".$this->numero." : reset compteur ".$cnt_id." ".$value, "carte", $this->timezone);
			$url = $this->geturl().'/preset.htm?SetCounter'.$cnt_id.'='.$value;
			$urlSecure = $this->geturl(false).'/preset.htm?SetCounter'.$cnt_id.'='.$value;
			fn_Trace($url, "carte", $this->timezone);
			list($statusHttpBtn, $responseHttpBtn) = fn_GetContent($url);
			return array($urlSecure, $statusHttpBtn, $responseHttpBtn);	
		}
		else
		{
			return $this->send_commande("ResetCount".$cnt_id);
		}
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
			if ( $this->portm2m == "" ) { // Si mode HTTP
				$url = $this->geturl()."status.xml";
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
				}
				else {
					$errorStatus++; // Erreur sur chargement du status.xml
					$message = ucfirst(fn_GetTranslation('error_communication', $this->label));
				}
			}
				
			else { // M2M
				$this->status = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
				$this->update_time = time();
				$result = $this->send_commande("GetOutputs");
				if ( substr($result, 0, 1) == "?" ) {
					$this->portm2m = "";
					return $this->get_status();
				}
				//list($cmd, $result) = explode("=", $result);
				$count = 0;
				foreach(str_split($result) as $value )
				{
					$this->status->addChild('led'.$count, $value);
					$count++;
				}
				$result = $this->send_commande("GetInputs");
				list($cmd, $result) = explode("=", $result);
				$count = 0;
				
				foreach(str_split($result) as $value )
				{
					$value = ($value +1)%2;
					$this->status->addChild('btn'.$count, $value);
					$count++;
				}
				for ($count = 0; $count <= 3; $count++)
				{
					$result = $this->send_commande("GetAn".($count+1));
					list($cmd, $value) = explode("=", $result);
					$this->status->addChild('analog'.$count, $value);
				}
				
				for ($count = 0; $count <= 2; $count++)
				{
					$result = $this->send_commande("GetCount".($count+1));
					list($cmd, $value) = explode("=", $result);
					$this->status->addChild('count'.$count, $value);
				}
				return array(0, "toto");
			}
		}
		return array($errorStatus, $message);

	}
	public function backpush($message, $class, $numero)
	{
		$this->update_time = time();
		list($objet, $etat) = explode(" ", $message);
		fn_Trace("Carte ".$this->label ."(".$this->numero.") : backpush (".$class." ".$numero.") = " . $message, "push", $this->timezone);
		$current = new $class($numero);
		if ( isset($current) ) {
			$MsgPush = $current->backpush($etat, $this->update_time);
			return $MsgPush;
		}
	}
	public function config_push()
	{
		// version inférieur à 3.05.00
		$url = $this->geturl().'protect/settings/push.htm?';
		$url .= 'url='.$_SERVER["HTTP_HOST"];
		$url .= '&port='.$_SERVER["SERVER_PORT"];
		$url .= '&path='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/push.php?carte='.$this->numero.'&message=');
		//$file = @file($url);
		$file = fn_GetContent($url);
		if ( $file === false )
			$status = fn_GetTranslation('update_status_ko');
		else
			$status = fn_GetTranslation('update_status_ok');
		echo fn_GetTranslation('active_update_push', $status)."<br>".PHP_EOL;
		$url = $this->geturl().'protect/settings/push.htm?';
		$url .= 'pushstate=1';
		for ($i = 1; $i <= 16; $i++)
			$url .= '&plk'.$i.'=1';
		//$file = @file($url);
		$file = fn_GetContent($url);	
		if ( $file === false )
			$status = fn_GetTranslation('update_status_ko');
		else
			$status = fn_GetTranslation('update_status_ok');
		echo fn_GetTranslation('update_push_old', $status)."<br>".PHP_EOL;
		flush ();
		fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
		/*for ($i = 1; $i <= 4; $i++)
		{
			$url = $this->geturl().'protect/settings/output1.htm?name=relayoutput'.$i.'&relayname=Relais'.$i;
			fn_Trace($url, "carte", $this->timezone);
			file($url);
		}
		for ($i = 5; $i <= 8; $i++)
		{
			$url = $this->geturl().'protect/settings/output2.htm?name=relayoutput'.$i.'&relayname=Relais'.$i;
			fn_Trace($url, "carte", $this->timezone);
			file($url);
		}
		*/
		for ($i = 0; $i < ($this->nb_extension+1)*8; $i++)
		{
			// input
			$url = $this->geturl().'protect/settings/push1.htm?';
			$url .= 'channel='.$i;
			$url .= '&server='.$_SERVER["HTTP_HOST"];
			$url .= '&port='.$_SERVER["SERVER_PORT"];
			$url .= '&enph=1';
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_push_path_btn', $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			$url = $this->geturl().'protect/settings/push1.htm?';
			$url .= 'channel='.$i;
			$url .= '&cmd1='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/push.php?carte='.$this->numero.'&message=Input'.($i+1).'%20Close');
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_push_btn', "close", $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			$url = $this->geturl().'protect/settings/push1.htm?';
			$url .= 'channel='.$i;
			$url .= '&cmd2='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/push.php?carte='.$this->numero.'&message=Input'.($i+1).'%20Open');
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_push_btn', "open", $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			// output
			$url = $this->geturl().'protect/settings/push2.htm?';
			$url .= 'channel='.(32+$i);
			$url .= '&server='.$_SERVER["HTTP_HOST"];
			$url .= '&port='.$_SERVER["SERVER_PORT"];
			$url .= '&enph=1';
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_push_path_relai', $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			$url = $this->geturl().'protect/settings/push2.htm?';
			$url .= 'channel='.(32+$i);
			$url .= '&cmd1='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/push.php?carte='.$this->numero.'&message=Relay'.($i+1).'%20On');
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_push_relai', "on", $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			$url = $this->geturl().'protect/settings/push2.htm?';
			$url .= 'channel='.(32+$i);
			$url .= '&cmd2='.urlencode(dirname($_SERVER["REQUEST_URI"]).'/push.php?carte='.$this->numero.'&message=Relay'.($i+1).'%20Off');
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_push_relai', "off", $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			$current = new relai(get_class($this)."-".$this->numero."-relai-".($i+1));
			$url = $this->geturl().'protect/settings/output1.htm';
			$url .= '?output='.($i+1);
			$url .= '&relayname='.urlencode($current->label);
			$url .= '&delayon='.$current->ta;
			$url .= '&delayoff='.$current->tb;
			//$file = @file($url);
			$file = fn_GetContent($url);
			if ( $file === false )
				$status = fn_GetTranslation('update_status_ko');
			else
				$status = fn_GetTranslation('update_status_ok');
			echo fn_GetTranslation('update_relai_ta_tb', $current->label, $i, $status)."<br>".PHP_EOL;
			fn_Trace("Carte ".$this->numero." : config push - ".$url, "carte", $this->timezone);
			usleep(1);
			flush ();
		}
		echo fn_GetTranslation('config_push_done');
    }
	public function update() {
		if ( ! isset($this->nb_extension) )
			$this->nb_extension = 3;
	}

	
}
?>