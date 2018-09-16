<?php
/*----------------------------------------------------------------*
* Titre : carte_rasponewire.php                                   *
* Classe de carte                                                 *
* Carte destinée à exploiter les données d un serveur owserver    *
* www.owfs.org                                                    *
* Devices supportés : sondes de température code 28               *
* Utilise la librairie ownet php                                  *
*-----------------------------------------------------------------*/

class carterasponewire extends carte
{
	public $portm2m, $status;
	public function __construct($numero="", $info = null)
	{
		$this->config_class = "carte";
		parent::__construct($numero, $info);
		$this->subclass = array("an");
		return $this;
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function advance_form()
	{
		//$OnChangePortm2m="AjaxUpdateImgValid('?app=Ws&page=verifxml&type=port&port='+this.value, 'portm2m');";
        if ( $this->portm2m == "" )
			$this->portm2m='4304';
		$return  = fn_HtmlInputField('portm2m', $this->portm2m, 'text', 'port_m2m', 'carteonewire.portm2m', '', false);
		return $return;
	}
	public function js()
	{
 		$return = parent::js();
		//$return .='AjaxUpdateImgValid("?app=Ws&page=verifxml&type=port&port='.$this->portm2m.'", "portm2m");';
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
		list($status, $message) = parent::receive_form(array("portm2m"));
		$this->update();
		return array($status, $message);
	}
	public function required_field()
	{
		return array_key_exists("portm2m", $_POST);
	}
	public function save($list_data = array())
	{
		$to_save = array("portm2m");
		$new = false;
		if ( ! isset($this->numero) || $this->numero == "" )
			$new = true;
		$return = parent::save($to_save);
		
		if ( $this->active == "on" ) // = on recherche des sondes
		{
			$this->get_status();
			if ( $this->status === false )
			{
				$this->del();
				return fn_GetTranslation('pb_acces_carte');
			}
            $status = $this->status;
            //$i = 0;  // il faut apporter une correction ici : $i doit être égal au nombre d elements deja existants +1
                     // sinon l ajout de nouveaux capteurs peut modifier la numerotation des capteurs existants
 
			foreach ($status as $node => $value) {
                if ( !$new )  // on gere les nouvelles sondes qui seront ajoutées au moment d une sauvegarde
                {
                    $xpath = "//*[xmlid='".$node."']";
                    $res = $GLOBALS["config"]->xpath($xpath);
                    if ( count($res) != 0 )  // on teste l existence d une sonde portant le meme identifiant 
                    {
                        $msg = fn_GetTranslation('sensor_exist', $node);
                        //$return['message'] = $return['message']." ".$msg;
						continue;
                    }
                }
                $detail = explode(".", $node);
                if (($detail[0] == "28") || ($detail[0] == "10"))  {
                    $current = new an();
                    $current->update();
                    $current->carteid = $this->numero;
                    $xmlid = $node;
                    $current->xmlid = $xmlid;
                    $current->label = $xmlid;
                    $current->type = "DS18";
                    $current->precision = "2";
					$current->displayformat = "brut";
                    $nodesave = $current->save();
					if ($nodesave['errcode']==0) {
						$msg = fn_GetTranslation('sensor_add', $node);
                        $return['message'] = $return['message'].PHP_EOL.$msg;
						$return['reload'] = true;
						fn_InitAuthAllUser('an', $current->numero);
					}
                }
            }
		}
		/*
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
	public function get_status()
	{
		$this->status = false;
		$errorStatus= 0;
		$message="";
	
		$this->status = false;
        if ( $this->active != 'on' )
		{
			$errorStatus++;
			$message = fn_GetTranslation('desactivation_carte', $this->label);
		}

		$url = 'tcp://'.$this->ip.':'.$this->portm2m;
        $ow=new OWNet($url,5,true);
        $result=$ow->dir("/");
        if ( $result == NULL ) {
			$errorStatus++;
			$message = ucfirst(fn_GetTranslation('cant_connect', $url));
        }
		else {
			$this->status = new SimpleXMLElement('<?xml version="1.0"?><response></response>');
			$this->update_time = time();
			$devices=explode(",",$result["data"]);
			foreach ($devices as $device) {
				$detail = explode(".", $device);
				if ($detail[0]=="/28")    // cas des sondes de temperatures DS18X20
				{
					$xmlid="28".".".$detail[1];
					$value=$ow->read(($device."/temperature12"));
					$this->status->addChild($xmlid, $value);
     				echo '<device>'.$value.'</device>';
				}
				if ( $detail[0]=="/10" )    // cas des sondes de temperatures DS18X20
				{
					$xmlid="10".".".$detail[1];
					$value=$ow->read(($device."/temperature"));
					$this->status->addChild($xmlid, $value);
				}
			}
		}
		unset ($ow);
        //return $this->status;
		return array($errorStatus, $message);
	}

	public function update()
	{
	}
}
?>