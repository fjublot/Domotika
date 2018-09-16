<?php
/*----------------------------------------------------------------*
* Titre : espdevice.php                                               *
* Classe de espdevice                                                 *
*----------------------------------------------------------------*/
require_once($GLOBALS["mvc_path"]."top.php");
class espdevice extends top
{
	public	$creation, $ip, $gpio, 
			$imageon,  $messageon,  $msgpushon,
			$imageoff, $messageoff, $msgpushoff,
			$message, $push, $pushon, $activationscenario, $deactivationscenario, $active,
			$valeur, $status, $type,
			$update_time;
	public $pushto = array();
	public function __construct($numero = "", $info = null)
	{
		parent::__construct($numero, $info);
		if ( ! isset($this->label) )
		{   $this->creation = true;
			$this->imageon = "on_v.png";
			$this->imageoff = "off.png";
			$this->messageon = "%label% - %etat%";
			$this->messageoff = "%label% - %etat%";
			$this->msgpushon = "%label% %etat%";
			$this->msgpushoff = "%label% %etat%";
			$this->message = "%label% - %etat%";
			$this->push = "off";
			$this->label = 'espDevice';
			$this->active = "on";
			$this->pushon = "all";
		}
		else {
			$this->creation=false;
		}
		if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageon) )
			$this->imageon = "config/images/".$this->imageon;
		else
			$this->imageon = 'images/relais/'.$this->imageon;
		if ( file_exists($GLOBALS["root_path"]."/config/images/".$this->imageoff) )
			$this->imageoff = "config/images/".$this->imageoff;
		else
			$this->imageoff = 'images/relais/'.$this->imageoff;
		return $this;
	}
	public function del()
	{
		fn_DelAuth(get_class($this), $this->numero);
		return parent::del();
	}
	public function form($page=null)
	{
		$return  =  fn_HtmlStartPanel($this->label, ucfirst(fn_GetTranslation(__class__)), __class__, $this->numero);
		$return .= '<form name="ajaxform" id="ajaxform" class="form-horizontal form-label-left" novalidate>';
		if (isset($_REQUEST["HTTP_REFERER"]))
			$return .= fn_HtmlHiddenField ('HTTP_REFERER',urlencode($_REQUEST["HTTP_REFERER"]));
  			elseif (isset($_SERVER["HTTP_REFERER"]))
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER',urlencode($_SERVER["HTTP_REFERER"]));
  			else
  				$return .=  fn_HtmlHiddenField('HTTP_REFERER', '');
  			$return .=  fn_HtmlHiddenField('class',__class__);
        
			if (isset($this->numero)) {
  				$return .=  fn_HtmlHiddenField('numero',$this->numero);
			}
		$return .= fn_HtmlHiddenField('action','action');
		$return .= fn_HtmlInputField('label', $this->label, 'text', 'espdevice_name', 'espdevice.label', '');
		$return .= fn_HtmlBinarySelectField('active', $this->active, 'active', 'espdevice.active',true);
		$return .= fn_HtmlInputField('ip', $this->ip, 'text', 'ip_address', 'espdevice.ip', '');
		$return .= fn_HtmlInputField('gpio', $this->gpio, 'text', 'gpio_number', 'espdevice.gpio', '');
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('notification')), 'notification', true);
		$return .= fn_HtmlBinarySelectField('push', $this->push, 'send_push', 'relai.push');
		$return .= fn_HtmlSelectField('pushon', 'push_on', 'espdevice.pushon','',false);
		$return .= fn_HtmlSelectField('pushto', 'push_account', 'espdevice.pushto[]','',true);
		$return .= fn_HtmlInputField('msgpushon', $this->msgpushon, 'text', 'msgpush_on', 'espdevice.msgpushon', '');
		$return .= fn_HtmlInputField('msgpushoff', $this->msgpushoff, 'text', 'msgpush_off', 'espdevice.msgpushoff', '');
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation(__class__).' '.fn_GetTranslation('on')), __class__ .'on', true);
		$return .= fn_HtmlInputField('messageon', $this->messageon, 'text', 'message_on', 'espdevice.messageon', '');
		//return .= fn_HtmlSelectField('imageon', 'image_on', 'relai.imageon',"UpdateImage(this.id);",false,true);
		$return .= fn_HtmlButtonPicto('imageon', $this->imageon, 'image_on', 'espdevice.imageon');
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation(__class__).' '.fn_GetTranslation('off')), __class__ . 'off', true);
		$return .= fn_HtmlInputField('messageoff', $this->messageoff, 'text', 'message_off', 'espdevice.messageoff', '');
		$return .= fn_HtmlInputField('message', $this->message, 'text', 'message', 'espdevice.message', '');
		//$return .= fn_HtmlSelectField('imageoff', 'image_off', 'relai.imageoff',"UpdateImage(this.id);",false,true);
		$return .= fn_HtmlButtonPicto('imageoff', $this->imageoff, 'image_off', 'espdevice.imageoff');
		$return .= fn_HtmlEndFieldset();
		$return .= fn_HtmlStartFieldset(ucfirst(fn_GetTranslation('scenarii')), 'scenarii', true);
		$return .= fn_HtmlSelectField('activationscenario', 'activation_scenario', 'espdevice.activationscenario',"",false,true);
		$return .= fn_HtmlSelectField('desactivationscenario', 'desactivation_scenario', 'espdevice.desactivationscenario',"",false,true);
		$return .= fn_HtmlEndFieldset();
		$return .= '<script src="ressources/previewimage/jquery.previewimage.js"></script>';
		return $return;

	}
  public function js() {
	$return = '
            AjaxLoadSelectJson("pushon", "class=pushon_type", false, "'.$this->pushon.'" );
	        AjaxLoadSelectJson("pushto", "class=pushto", true, "'.implode(',',$this->pushto).'" );
	        AjaxLoadSelectJson("desactivationscenario", "class=scenario", true, "'.$this->deactivationscenario.'" );
	        AjaxLoadSelectJson("activationscenario", "class=scenario", true, "'.$this->activationscenario.'");
			jQuery(\'.previewimage\').preimage();';
	$return .='/* Règles de validation */
			var configvalidate = {
				// ordinary validation config
				form : \'#ajaxform\',
				// reference to elements and the validation rules that should be applied
				validate : {
					\'#label\' : {
					    validation : \'length\',
						length : \'5-20\'
					}
				}
			};
	';
		
		// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_imageon").click(function () {jQuery("#modal_imageon").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_imageon").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_imageon", "dossier=' . get_class($this) . 's");});';
		// Pour afficher la modal sur le click du bouton
		$return .='jQuery("#BT_modal_imageoff").click(function () {jQuery("#modal_imageoff").modal("show");});';
		// Pour charger le contenu de la modal lors de son chargement	
		$return .='jQuery("#modal_imageoff").on("show.bs.modal", function () {AjaxLoadThumbnails("#modalbody_imageoff", "dossier=' . get_class($this) . 's");});';

		
		return $return;
	}
	
	public function receive_form($list_data = null)
	{
		list($status, $message) = parent::receive_form(array("label", "ip", "gpio", "imageon",  "messageon",  "msgpushon", "imageoff", "messageoff", "msgpushoff", "message", "push", "pushon", "activationscenario", "deactivationscenario", "active"));
		if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];
		$this->creation=false;
		return array($status, $message);
	}
	public function save($list_data = null)
	{
		if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];
		return parent::save(array("label", "ip", "gpio", "imageon",  "messageon",  "msgpushon", "imageoff", "messageoff", "msgpushoff", "message", "push", "pushon", "activationscenario", "deactivationscenario", "active"));
	}
	public function update()
	{
		if ( ! isset($this->activationscenario) )
			$this->activationscenario = "";
		if ( ! isset($this->deactivationscenario) )
			$this->deactivationscenario = "";
		if ( preg_match("!^.*/(.*)$!", $this->imageon, $regs) )
			$this->imageon = $regs[1];
		if ( preg_match("!^.*/(.*)$!", $this->imageoff, $regs) )
			$this->imageoff = $regs[1];
		if ( ! isset($this->active) )
			$this->active = "on";
		if ( ! isset($this->pushon) )
			$this->pushon = "all";
		if ( ! in_array($this->pushon, array("all", "on", "off" ) ) )
			$this->pushon = "all";
		if ( $this->messageon == 'on')
 			$this->messageon = '%carte% - %label% - %etat%';
			$this->msgpushon = '%carte% - %label% - %etat%';
		if ( $this->messageoff == 'off' )
			$this->messageoff = '%carte% - %label% - %etat%';
			$this->msgpushoff = '%carte% - %label% - %etat%';
		if ( $this->message == '' )
			$this->message = "%carte% - %label% - %etat%";
		parent::update();
	}
	public function disp($commandactive=true, $jsremove=false)
	{
		$retour = "";
		$classcss='noteditable';
		if ($jsremove)
			$classcss='';		
		if ( $this->active == "on" ) {
			if ( $this->valeur == 0 ) {
				$image = $this->imageoff;
			}
			else {
				$image = $this->imageon;
			}
			$retour = '<div class="Container animated flipInY ' . $GLOBALS["config"]->csscontainer .' ' .  $classcss.'" data-id="'.$this->config_class.'_'.$this->numero.'"'. PHP_EOL;
			$retour .= '>'. PHP_EOL;
			$retour .= '<div class="Entete '.get_class($this).'_label_'.$this->numero.'" >'.$this->label.'</div>'. PHP_EOL;
				//$retour .= '<img class="Image Pointer '.get_class($this).'_image_'.$this->numero.'" id="'.get_class($this).'_image_'.$this->numero.'"';
				$retour .= '<img class="Image Pointer '.get_class($this).'_image_'.$this->numero.'" ';
				if ( isset($_SESSION['Privilege']) && $_SESSION['Privilege'] >= (int)$GLOBALS["config"]->access->modrelai )
				{
					if ($commandactive==true)
						$retour .= ' onclick="AjaxAction(\'espdevice\', \''.$this->numero.'\',\'\',\'\');"';
				}
				$retour .= ' alt="espdevice" src="'.$image.'"/>';
			$retour.=    '<div class="Caption"></div>'. PHP_EOL;
			$retour.='<i class="js-remove fa fa-trash-o fa-2x"></i>';	
			//$retour .= '<input type=hidden id="ctrl_'.$this->numero.'" value="'.$this->ctrlcarte.'">';
			$retour .= '</div>'.PHP_EOL;
			//$retour .= '</div>'.PHP_EOL;		
		}
		return $retour;
	}
	public function getxmlvalue($xmlStatus = false)
	{	$value=0;
		if ($xmlStatus == false)
			return "";
		$xpath = "//devices[id='".$this->xmlid."']";
		$nodes = $xmlStatus->xpath($xpath);
		if ( count($nodes) != 0 )
		{
			foreach ($nodes as $item)
			{
				$this->valeur = (string)$item->metrics->level;
			}
		}
		else
			$this->valeur = "";
		
		return $this->valeur;
	}
	public function asxml($detail = false)
	{
		$return = "";
		if ( $this->active = "on" )
		{
			$return = "<".get_class($this)." numero='".$this->numero."'>";
			$return .= "<value>".$this->GetState()."</value>" ;
			$infobulle='';
			if ( $this->valeur == 0 )
			{
				$return .= "<image>".$this->imageoff."</image>" ;
				if ($this->type=='I')
				{
					$infobulle =fn_GetTranslation('turn_on', get_class($this)."s");
				}
				elseif ($this->type=='F')
				{
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s");
				}
				elseif ($this->type=='ALLON')
				{
					$infobulle =fn_GetTranslation('turn_all_on', get_class($this)."s");
				}
				elseif ($this->type=='ALLOFF')
				{
					$infobulle =fn_GetTranslation('turn_all_off', get_class($this)."s");
				}
			}
			else
			{
				$return .= "<image>".$this->imageon."</image>" ;
				if ($this->type == 'I')
				{
					$infobulle =fn_GetTranslation('turn_off', get_class($this)."s");
				}
				elseif ($this->type=='F')
				{
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s");
				}
				elseif ($this->type=='ALLON')
				{
					$infobulle = fn_GetTranslation('turn_all_on', get_class($this)."s");
				}
				elseif ($this->type=='ALLOFF')
				{
					$infobulle =fn_GetTranslation('turn_all_off', get_class($this)."s");
				}
			}
	/*		if ( $this->ctrlcarte != "" && $this->ctrlbtn != "" )
			{
				$return .= "<ctrlcarte>".$this->ctrlcarte."</ctrlcarte>";
				$return .= "<ctrlbtn>".$this->ctrlbtn."</ctrlbtn>";
			}
			$return .= '<action>'.$action.'</action>';
			$return .= '<valeur>'.$this->valeur.'</valeur>';
	*/
			$return .= '<infobulle>'.$infobulle.'</infobulle>';
			if ( $detail )
			{
				$return .= "<label>".$this->label."</label>";
			}
			$return .= "</".get_class($this).">";
		}
		return $return;
	}  
  

	// asjson
	// Renvoi un tableau du status du cnt pouvant être encodé en json 
	public function asjson($detail = false) {
		if ( $this->active = "on" ) {
			$infobulle="";
			if ( $this->valeur == 0 ) { // Si le relai est ouvert
				$image = $this->imageoff;
				
				if ($this->type=='I') 
					$infobulle =fn_GetTranslation('turn_on', get_class($this)."s");
				elseif ($this->type=='F') 
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s");
				elseif ($this->type=='ALLON') 
					$infobulle =fn_GetTranslation('turn_all_on', get_class($this)."s");
				elseif ($this->type=='ALLOFF') 
					$infobulle =fn_GetTranslation('turn_all_off', get_class($this)."s");
			}
			else { // Si le relai est fermé
				$image = $this->imageon;
				if ($this->type == 'I') 
					$infobulle =fn_GetTranslation('turn_off', get_class($this)."s");
				elseif ($this->type=='F')
					$infobulle =fn_GetTranslation('pulse', get_class($this)."s");
				elseif ($this->type=='ALLON')
					$infobulle = fn_GetTranslation('turn_all_on', get_class($this)."s");
				elseif ($this->type=='ALLOFF')
					$infobulle =fn_GetTranslation('turn_all_off', get_class($this)."s");
			}
			
			if ( $detail )
				$espdevicejson = array('numero' => $this->numero, 'label' => $this->label, 'type' => $this->type, 'value' => $this->valeur, 'infobulle' => $infobulle, 'image' => $image);
			else
				$espdevicejson = array('numero' => $this->numero, 'value' => $this->valeur, 'infobulle' => $infobulle, 'image' => $image);
		}
		return $espdevicejson;
	}
	
	public function backpush($etat, $update_time)
	{
		$numero_scenario = 0;
		switch ($etat)
		{
			Case 'On':
				if ( $this->msgpushon != '' )
				{
					$Msg = $this->msgpushon;
				}
				$this->valeur = 99;
				$numero_scenario = $this->activationscenario;
				break;
			Case 'Off':
				if ( $this->type == 'F' )
				{
					$Msg = '';
				}
				else
				{
					if ( $this->msgpushoff != '')
					{
						$Msg = $this->msgpushoff;
					}
 				}
				$this->valeur = 0;
				$numero_scenario = $this->deactivationscenario;
				break;
			Default:
				$Msg = "Etat %label% incomprehensible ".$etat;
				fn_Trace("Etat espdevice incomprehensible ".$etat, "push");
				break;
		}
		$Msg = str_replace("%label%", $this->label, $Msg);
		if ( $this->type == 'F' )
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_impulsion')." %etat%", $Msg);
		if ( $this->valeur == 99 )
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_down'), $Msg);
		else
			$Msg = str_replace("%etat%", fn_GetTranslation('etat_up'), $Msg);
		$this->update_time = $update_time;
		$this->mysql_save();
		$send = $this->pushon == "all" or ( $this->pushon == "on" and $this->valeur == 1 ) or ( $this->pushon == "off" and $this->valeur == 0 );
		if ( $this->push == "on" && $Msg != "" && $send )
		{
			//fn_Trace("Carte ".$carte->label." Relai ".$this->label." : pushto ".$this->pushto.'->'.$Msg, "push");
      		foreach($this->pushto as $compte_pushto)
		{
			if ( $this->push == "on" && $Msg != "" && $send )
			{
				if ( isset($carte) )
					fn_Trace("Carte ".$carte->label." espdevice ".$this->label." : pushto ".$compte_pushto.'->'.$Msg, "push");
				else
					fn_Trace("espdevice ".$this->label." : pushto ".$compte_pushto.'->'.$Msg, "push");
			fn_PushTo($Msg, $compte_pushto);
			}
		}
    }  
		if ( $numero_scenario != 0 )
		{
			$scenario = new scenario($numero_scenario);
			$scenario->run();
		}
		return $Msg;
	}
	public function moveUp()
	{
	    if ($this->previousSibling)
	    {
	        $this->parentNode->insertBefore($this, $this->previousSibling);
	    }
	}
	public function moveDown()
	{
	    if ($this->nextSibling)
	    {
	        $this->parentNode->insertBefore($this->nextSibling, $this);
	    }
	}

	public function disp_list() {
		$checked="";
		if ($this->active=="on") $checked="checked";
		$xpath  = '//type'.__class__.'s/type'.__class__.'[@numero="'.$this->type.'"]';
		$labeltype = fn_GetByXpath($xpath, 'bal', 'label');
		$return  = '<a href="?app=Mn&amp;page=Add&amp;class=' . __class__ . '&amp;numero=' . $this->numero . '&amp;del=true" class="'. $GLOBALS["classDispList"] .'">';
		//return .= '	<div class="ribbon blue"><span class="double">'.$labelcarteid.'<br>'.__class__.' ('.$this->no.')</span></div>';
		$return .= '	<div class="well profile_view">';
		$return .= '		<div class="col-sm-12">';
		$return .= '			<div>';
		$return .= '                <span class="col-xs-9 left brief searchable filterable" style="margin-top: 4px;" data-beginletter="'.strtoupper(substr($this->label,0,1)).'">';
		$return .= '					<span class="badge bg-red">'.$this->numero.'</span>';
		$return .= '					<span class="h2">'.$this->label.'</span>';
		$return .= '					</span>';
		$return .= '			        <input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checked.' >';
		$return .= '            </div>';
		$return .= '			<div class="left col-xs-12">';
		//$return .= '				<p class="searchable">'.ucfirst(fn_GetTranslation("send_push")).' : <input type="checkbox" data-toggle="toggle" data-size="small" disabled  data-style="pull-right" '.$checkedpush.' ></p>';		
		$return .= '			</div>';
/*
		$return .= '			<div class="right col-xs-4 text-center">';
		//$return .= '				<img src="'.$this->picto.'" alt="" class="img-circle img-responsive">';
		$return .= '			</div>';
*/
		$return .= '		</div>';
		$return .= '	</div>';
		$return .= '</a>';
		echo $return;
	}
	
	public function SetOn()	{
		$errorStatus = 0;
		$url = $this->geturl()."/control?cmd=gpio,".$this->gpio . ",1";
		list($statusHttp, $responseHttp) = fn_GetContent($url);
		if ($responseHttp != false) { // La carte a pu être contactée
			$this->status = json_decode($responseHttp);
			if ( $this->status == false ) {
				$errorStatus++;
				$message = ucfirst(fn_GetTranslation('treatmenterror'));
			}
			else {
				$this->update_time = time();
				$message = ucfirst($this->messageon);
				$message = str_replace("%label%", $this->label, $message);
				$message = str_replace("%etat%", fn_GetTranslation('etat_down'), $message);
			}
		}
		else {
			$errorStatus++; // Erreur sur chargement du status.xml
			$message = ucfirst(fn_GetTranslation('error_communication', $this->label));

		}
		return array($url, $statusHttp, $responseHttp, $message, $errorStatus);
		
	}

	public function SetOff() {
		$errorStatus = 0;
		$url = $this->geturl()."/control?cmd=gpio,".$this->gpio . ",0";
		list($statusHttp, $responseHttp) = fn_GetContent($url);
		if ($responseHttp != false) { // La carte a pu être contactée
			$this->status = json_decode($responseHttp);
			if ( $this->status == false ) {
				$errorStatus++;
				$message = ucfirst(fn_GetTranslation('treatmenterror'));
			}
			else {
				$this->update_time = time();
				$message = ucfirst($this->messageoff);
				$message = str_replace("%label%", $this->label, $message);
				$message = str_replace("%etat%", fn_GetTranslation('etat_up'), $message);
			}
		}
		else {
			$errorStatus++; // Erreur sur chargement du status.xml
			$message = ucfirst(fn_GetTranslation('error_communication', $this->label));
		}
		return array($url, $statusHttp, $responseHttp, $message, $errorStatus);		
	}
	
	public function GetState()
	{	
		$this->get_status();
		/*
		if ($this->status != false)
			$this->valeur = $this->status->{'state'};
		else
			$this->valeur = null;
		*/
		return $this->valeur;
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
			/*$url = $this->geturl()."/control?cmd=status,gpio,".$this->gpio;
			list($statusHttp, $responseHttp) = fn_GetContent($url);
			if ($responseHttp != false) { // La carte a pu être contactée
				$this->status = json_decode($responseHttp);
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
			*/
			$this->mysql_load();
			return $this->valeur;
		}
	}

	public function geturl($withpassword = true) {
		$url = 'http://' . $this->ip;
		return $url;
	}
		
	public function SetEspDevice ($value, $when=NULL) {
		$this->valeur = $value;
		if ( is_null($when) )
			$this->update_time = time();
		else
			$this->update_time = $when;
		$this->mysql_save();
	}
}
?>